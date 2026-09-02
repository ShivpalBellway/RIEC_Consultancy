<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Program;
use App\Models\Application;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\ApplicationSubmittedMail;
use App\Models\SiteSetting;

class ApplicationController extends Controller
{
    private const APPLICATION_CONSENT_TEXT = 'I consent that my personal details will be saved in the system for application processing.';

    public function index()
    {
        $programs = Program::active()->ordered()->get();
        return view('pages.web.apply.index', compact('programs'));
    }

    public function showEligibility(Program $program)
    {
        if (!$program->is_active) {
            abort(404);
        }
        $fields = $program->eligibilityFields()->active()->get();
        return view('pages.web.apply.eligibility', compact('program', 'fields'));
    }

    public function checkEligibility(Request $request, Program $program)
    {
        if (!$program->is_active) {
            abort(404);
        }

        $fields = $program->eligibilityFields()->active()->get();
        $rules = [];
        $messages = [];
        $errors = [];
        $eligibilityAnswers = [];

        foreach ($fields as $field) {
            $key = $field->field_key;
            $val = $request->input($key);

            // Save for response tracking
            $eligibilityAnswers[$key] = [
                'label' => $field->label,
                'value' => $val,
                'unit'  => $field->unit,
            ];

            // Required validation
            if ($field->is_required && (is_null($val) || $val === '')) {
                $errors[$key] = $field->validation_message ?: "The {$field->label} field is required.";
                continue;
            }

            if (!is_null($val) && $val !== '') {
                // Min/Max checking for number
                if ($field->field_type === 'number') {
                    $numVal = floatval($val);
                    if (!is_null($field->min_value) && $field->min_value !== '') {
                        if ($numVal < floatval($field->min_value)) {
                            $errors[$key] = $field->validation_message ?: "{$field->label} must be at least {$field->min_value} {$field->unit}.";
                            continue;
                        }
                    }
                    if (!is_null($field->max_value) && $field->max_value !== '') {
                        if ($numVal > floatval($field->max_value)) {
                            $errors[$key] = $field->validation_message ?: "{$field->label} must not exceed {$field->max_value} {$field->unit}.";
                            continue;
                        }
                    }
                }
            }
        }

        if (!empty($errors)) {
            return back()
                ->withErrors($errors)
                ->withInput()
                ->with('eligibility_modal', 'error')
                ->with('eligibility_messages', array_values($errors));
        }

        // Store eligibility in session and show confirmation before opening form page
        Session::put("apply_eligibility_data_{$program->id}", $eligibilityAnswers);

        return redirect()->route('apply.eligibility', $program)
                         ->with('eligibility_modal', 'success');
    }

    public function showForm(Program $program)
    {
        if (!$program->is_active) {
            abort(404);
        }

        if (!Session::has("apply_eligibility_data_{$program->id}")) {
            return redirect()->route('apply.eligibility', $program)
                             ->with('error', 'Please complete the eligibility check first.');
        }

        if (!Auth::check()) {
            Session::put('url.intended', route('apply.form', $program));

            return redirect()->route('student.login')
                ->with('auth_notice', 'Please login or register before submitting your application.');
        }

        $sections = $program->formSections()->active()->with(['fields' => function($q) {
            $q->active()->orderBy('sort_order');
        }])->get();

        return view('pages.web.apply.form', compact('program', 'sections'));
    }

    public function submitForm(Request $request, Program $program)
    {
        if (!$program->is_active) {
            abort(404);
        }

        if (!Session::has("apply_eligibility_data_{$program->id}")) {
            return redirect()->route('apply.eligibility', $program)
                             ->with('error', 'Session expired. Please verify eligibility again.');
        }

        if (!Auth::check()) {
            Session::put('url.intended', route('apply.form', $program));

            return redirect()->route('student.login')
                ->with('auth_notice', 'Please login or register before submitting your application.');
        }

        $sections = $program->formSections()->active()->with(['fields' => function($q) {
            $q->active();
        }])->get();

        $attachments = [];
        $formAnswers = [];
        $validationRules = [];
        $validationMessages = [];

        // Dynamic validation rules based on form fields configured in database
        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                $key = $field->field_key;
                
                // Add validation rules
                $rules = [];
                if ($field->is_required) {
                    $rules[] = 'required';
                } else {
                    $rules[] = 'nullable';
                }

                if ($field->field_type === 'email') {
                    $rules[] = 'email';
                }

                if ($field->field_type === 'file') {
                    $rules[] = 'file|max:5120'; // max 5MB
                }

                $validationRules[$key] = implode('|', $rules);

                if ($field->validation_message) {
                    $validationMessages["{$key}.required"] = $field->validation_message;
                    $validationMessages["{$key}.email"] = $field->validation_message;
                    $validationMessages["{$key}.file"] = $field->validation_message;
                }
            }
        }

        $request->validate($validationRules, $validationMessages);

        // Process answers
        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                $key = $field->field_key;
                if ($field->field_type === 'file' && $request->hasFile($key)) {
                    $uploadedFile = $request->file($key);
                    
                    if ($field->store_in_system) {
                        $path = $uploadedFile->store('applications/attachments', 'local');
                        $formAnswers[$key] = [
                            'label' => $field->label,
                            'value' => $path,
                            'original_name' => $uploadedFile->getClientOriginalName(),
                            'mime_type' => $uploadedFile->getMimeType(),
                            'is_file' => true,
                            'store_in_system' => true
                        ];
                    } else {
                        $formAnswers[$key] = [
                            'label' => $field->label,
                            'value' => 'Sent via Email (Attachment: ' . $uploadedFile->getClientOriginalName() . ')',
                            'is_file' => true,
                            'store_in_system' => false
                        ];
                    }

                    // Collect files to attach to email
                    $attachments[] = [
                        'path' => $uploadedFile->getRealPath(),
                        'name' => $uploadedFile->getClientOriginalName(),
                        'mime' => $uploadedFile->getMimeType()
                    ];
                } else {
                    $formAnswers[$key] = [
                        'label' => $field->label,
                        'value' => $request->input($key)
                    ];
                }
            }
        }

        // Extracted basic contact fields or fallbacks
        $name = $request->input('full_name') ?: ($request->input('name') ?: 'Applicant');
        $email = $request->input('email') ?: 'applicant@example.com';
        $phone = $request->input('phone') ?: '0000000000';

        // Save application
        $application = Application::create([
            'user_id' => Auth::id(),
            'program_id' => $program->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'eligibility_answers' => Session::get("apply_eligibility_data_{$program->id}"),
            'form_answers' => $formAnswers,
            'consent_accepted' => true,
            'consent_text' => 'Consented at user registration.',
            'consent_accepted_at' => Auth::user()->consents_accepted_at ?: now(),
            'status' => 'pending'
        ]);

        // Send notification email with attachments to the configured recipient.
        $recipientEmail = SiteSetting::applicationRecipientEmail();

        if ($recipientEmail) {
            try {
                Mail::to($recipientEmail)->send(new ApplicationSubmittedMail($application, $attachments));
            } catch (\Exception $e) {
                logger()->error('Failed to send application email: ' . $e->getMessage());
            }
        } else {
            logger()->warning('Application notification email skipped because no valid recipient is configured.', [
                'application_id' => $application->id,
            ]);
        }

        // Clean session
        Session::forget("apply_eligibility_data_{$program->id}");

        return redirect()->route('apply.success', [$program, $application]);
    }

    public function showSuccess(Program $program, Application $application)
    {
        if ($application->user_id && $application->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.web.apply.success', compact('program', 'application'));
    }
}
