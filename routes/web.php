<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\EligibilityFieldController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Web\ApplicationController as WebApplicationController;
use App\Http\Controllers\Web\HomePageController;
use App\Http\Controllers\Web\AboutPageController;
use App\Http\Controllers\Admin\AdminAboutUsController;
use App\Http\Controllers\Web\ContactPageController;
use App\Http\Controllers\Web\FaqPageController;
use App\Http\Controllers\Web\ServicePageController;
use App\Http\Controllers\Web\LegalPageController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\WebsiteManageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SuccessStoryController;
use App\Http\Controllers\Admin\ProcessStepController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Web\BlogController as WebBlogController;
use App\Http\Controllers\Web\StudentAuthController;
use App\Http\Controllers\Web\StudentDashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Web\UserNoticeController;
use App\Http\Controllers\Agent\AgentAuthController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentStudentController;
use App\Http\Controllers\Agent\AgentDocumentController;
use App\Http\Controllers\Admin\AdminAgentController;
use App\Http\Controllers\Admin\AdminDocumentRemovalController;

// ─── Public Web Routes ────────────────────────────────────────

// Homepage
Route::get('/', [HomePageController::class, 'index'])->name('home');
Route::get('/institution', function () {
    return view('pages.web.institutions');
})->name('institution');

// Application Routes
Route::prefix('apply')->name('apply.')->group(function () {
    Route::get('/', [WebApplicationController::class, 'index'])->name('index');
    Route::get('/{program}/eligibility', [WebApplicationController::class, 'showEligibility'])->name('eligibility');
    Route::post('/{program}/eligibility', [WebApplicationController::class, 'checkEligibility'])->name('eligibility.check');
    Route::get('/{program}/form', [WebApplicationController::class, 'showForm'])->name('form');
    Route::post('/{program}/form', [WebApplicationController::class, 'submitForm'])->name('form.submit');
    Route::get('/{program}/success/{application}', [WebApplicationController::class, 'showSuccess'])->name('success');
});

// About Us
Route::get('/about-us', [AboutPageController::class, 'index'])->name('aboutUs');

// Contact Us
Route::get('/contact-us', [ContactPageController::class, 'index'])->name('contact');
Route::post('/contact-us', [ContactPageController::class, 'store'])->name('contact.store');

// Legal Pages
Route::get('/privacy-policy', [LegalPageController::class, 'show'])->defaults('slug', 'privacy-policy')->name('privacy-policy');
Route::get('/terms-conditions', [LegalPageController::class, 'show'])->defaults('slug', 'terms-conditions')->name('terms-conditions');

// Visa Points Calculator
Route::get('/visa-calculator', function () {
    return view('pages.web.visaCalculator');
})->name('visa.calculator');

// FAQs
Route::get('/faqs', [FaqPageController::class, 'index'])->name('faqs.index');

// Blogs
Route::get('/blogs', [WebBlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [WebBlogController::class, 'show'])->name('blogs.show');

// Services
Route::get('/services', [ServicePageController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServicePageController::class, 'show'])->name('services.show');

Route::get('/notices', [UserNoticeController::class, 'index'])->name('frontend.notices.index');
Route::get('/notices/{notice}', [UserNoticeController::class, 'show'])->name('frontend.notices.show');
Route::get('/notices/{notice}/download/{index}', [UserNoticeController::class, 'downloadFile'])->name('frontend.notices.download');

// Student Auth
Route::get('/login', function () {
    return redirect()->route('student.login');
})->name('login');

Route::get('/student/login', [StudentAuthController::class, 'showLogin'])->name('student.login');
Route::post('/student/login', [StudentAuthController::class, 'login'])->middleware('throttle:5,1')->name('student.login.post');
Route::get('/student/login/otp', [StudentAuthController::class, 'showOtp'])->name('student.otp.show');
Route::post('/student/login/otp', [StudentAuthController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('student.otp.verify');
Route::post('/student/login/otp/resend', [StudentAuthController::class, 'resendOtp'])->middleware('throttle:3,1')->name('student.otp.resend');
Route::get('/student/register', [StudentAuthController::class, 'showRegister'])->name('student.register');
Route::post('/student/register', [StudentAuthController::class, 'register'])->middleware('throttle:5,1')->name('student.register.post');

Route::middleware('auth')->group(function () {
    Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/applications/{application}', [StudentDashboardController::class, 'show'])->name('student.applications.show');
    Route::get('/student/applications/{application}/attachments/{fieldKey}', [StudentDashboardController::class, 'downloadAttachment'])
        ->where('fieldKey', '[A-Za-z0-9_-]+')
        ->name('student.applications.attachments.download');
});

// ─── Agent Portal Routes ─────────────────────────────────────────
Route::prefix('agent')->name('agent.')->group(function () {
    // Auth (Guest)
    Route::get('/login', [AgentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AgentAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
    Route::get('/register', [AgentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AgentAuthController::class, 'register'])->middleware('throttle:5,1')->name('register.post');

    // Protected Agent Routes
    Route::middleware('agent.auth')->group(function () {
        Route::post('/logout', [AgentAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

        // Student Management
        Route::resource('students', AgentStudentController::class);

        // Documents Upload & Removal Request
        Route::post('/students/{student}/documents/upload-batch', [AgentDocumentController::class, 'uploadBatch'])->name('documents.upload-batch');
        Route::post('/students/{student}/documents/upload', [AgentDocumentController::class, 'upload'])->name('documents.upload');
        Route::post('/students/{student}/documents/submit', [AgentDocumentController::class, 'submitForReview'])->name('documents.submit');
        Route::post('/documents/{document}/request-removal', [AgentDocumentController::class, 'requestRemoval'])->name('documents.request-removal');
    });
});

// ─── Admin Routes ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (no middleware)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Two-Factor Authentication (2FA)
    Route::get('/otp', [TwoFactorController::class, 'showOtpVerification'])->name('otp.show');
    Route::post('/otp/verify', [TwoFactorController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('otp.verify');
    Route::post('/otp/resend', [TwoFactorController::class, 'resendOtp'])->middleware('throttle:3,1')->name('otp.resend');

    // Protected (requires admin session)
    Route::middleware('admin.auth')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Agents Management (Admin)
        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/', [AdminAgentController::class, 'index'])->name('index');
            Route::post('/{agent}/status', [AdminAgentController::class, 'updateStatus'])->name('update-status');
        });

        // Document Removal Requests (Admin)
        Route::prefix('document-removals')->name('document-removals.')->group(function () {
            Route::get('/', [AdminDocumentRemovalController::class, 'index'])->name('index');
            Route::post('/{document}/approve', [AdminDocumentRemovalController::class, 'approve'])->name('approve');
            Route::post('/{document}/reject', [AdminDocumentRemovalController::class, 'reject'])->name('reject');
        });

        // Students Management (Admin)
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('store');
            Route::get('/{student}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('update');
            Route::get('/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'show'])->name('show');
            Route::put('/{student}/university', [\App\Http\Controllers\Admin\StudentController::class, 'updateUniversity'])->name('update-university');
            Route::put('/{student}/status', [\App\Http\Controllers\Admin\StudentController::class, 'updateStatus'])->name('update-status');
            Route::post('/{student}/documents/upload', [\App\Http\Controllers\Admin\StudentController::class, 'uploadDocument'])->name('documents.upload');
            Route::post('/{student}/offer-letter', [\App\Http\Controllers\Admin\StudentController::class, 'uploadOfferLetter'])->name('upload-offer-letter');
            // Document Verify / Reject
            Route::post('/documents/{document}/verify', [\App\Http\Controllers\Admin\StudentController::class, 'verifyDocument'])->name('documents.verify');
            Route::post('/documents/{document}/reject', [\App\Http\Controllers\Admin\StudentController::class, 'rejectDocument'])->name('documents.reject');
        });

        Route::get('/activity-logs/export-excel', [ActivityLogController::class, 'exportExcel'])->name('activity-logs.export-excel');
        Route::get('/activity-logs/export-csv', [ActivityLogController::class, 'exportCsv'])->name('activity-logs.export-csv');
        Route::get('/activity-logs/stats', [ActivityLogController::class, 'stats'])->name('activity-logs.stats');
        Route::post('/activity-logs/delete-old', [ActivityLogController::class, 'deleteOld'])->name('activity-logs.delete-old');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');


        Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
        Route::get('/notices/create', [NoticeController::class, 'create'])->name('notices.create');
        Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
        Route::get('/notices/{notice}', [NoticeController::class, 'show'])->name('notices.show');
        Route::get('/notices/{notice}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
        Route::put('/notices/{notice}', [NoticeController::class, 'update'])->name('notices.update');
        Route::delete('/notices/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
        Route::get('/notices/{notice}/download/{index}', [NoticeController::class, 'downloadFile'])->name('notices.download');
        Route::post('/notices/{notice}/toggle-status', [NoticeController::class, 'toggleStatus'])->name('notices.toggle-status');
        Route::post('/notices/{notice}/toggle-pinned', [NoticeController::class, 'togglePinned'])->name('notices.toggle-pinned');
        Route::post('/notices/bulk-delete', [NoticeController::class, 'bulkDelete'])->name('notices.bulk-delete');

        // Application setup selectors
        Route::get('/eligibility-setup', [ProgramController::class, 'eligibilitySetup'])->name('eligibility-setup');
        Route::get('/form-builder-setup', [ProgramController::class, 'formBuilderSetup'])->name('form-builder-setup');

        // Programs CRUD
        Route::resource('programs', ProgramController::class);

        // Eligibility Fields (nested under program)
        Route::prefix('programs/{program}/eligibility')->name('programs.eligibility.')->group(function () {
            Route::get('/', [EligibilityFieldController::class, 'index'])->name('index');
            Route::get('/create', [EligibilityFieldController::class, 'create'])->name('create');
            Route::post('/', [EligibilityFieldController::class, 'store'])->name('store');
            Route::get('/{field}/edit', [EligibilityFieldController::class, 'edit'])->name('edit');
            Route::put('/{field}', [EligibilityFieldController::class, 'update'])->name('update');
            Route::delete('/{field}', [EligibilityFieldController::class, 'destroy'])->name('destroy');
        });

        // Form Builder (nested under program)
        Route::prefix('programs/{program}/form-builder')->name('programs.form-builder.')->group(function () {
            Route::get('/', [FormBuilderController::class, 'index'])->name('index');

            // Sections
            Route::post('/sections', [FormBuilderController::class, 'storeSection'])->name('sections.store');
            Route::delete('/sections/{section}', [FormBuilderController::class, 'destroySection'])->name('sections.destroy');

            // Fields (nested under section)
            Route::get('/sections/{section}/fields/create', [FormBuilderController::class, 'createField'])->name('fields.create');
            Route::post('/sections/{section}/fields', [FormBuilderController::class, 'storeField'])->name('fields.store');
            Route::get('/sections/{section}/fields/{field}/edit', [FormBuilderController::class, 'editField'])->name('fields.edit');
            Route::put('/sections/{section}/fields/{field}', [FormBuilderController::class, 'updateField'])->name('fields.update');
            Route::delete('/sections/{section}/fields/{field}', [FormBuilderController::class, 'destroyField'])->name('fields.destroy');
        });

        // Applications Management
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
            Route::get('/export', [AdminApplicationController::class, 'export'])->name('export');
            Route::get('/{application}/attachments/{fieldKey}', [AdminApplicationController::class, 'downloadAttachment'])
                ->where('fieldKey', '[A-Za-z0-9_-]+')
                ->name('attachments.download');
            Route::get('/{application}', [AdminApplicationController::class, 'show'])->name('show');
            Route::put('/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('update-status');
        });

        // (Students management already registered above)

        // Site Settings
        Route::prefix('site')->name('site.')->group(function () {
            Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
            Route::post('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
        });

        // Partners
        Route::resource('partners', PartnerController::class);
        Route::post('/partners/{partner}/toggle-status', [PartnerController::class, 'toggleStatus'])
            ->name('partners.toggle-status');

        // Services
        Route::resource('services', ServiceController::class);
        Route::post('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])
            ->name('services.toggle-status');

        // Features
        Route::resource('features', FeatureController::class);
        Route::post('/features/{feature}/toggle-status', [FeatureController::class, 'toggleStatus'])
            ->name('features.toggle-status');

        // Success Stories
        Route::resource('success-stories', SuccessStoryController::class);
        Route::post('/success-stories/{successStory}/toggle-status', [SuccessStoryController::class, 'toggleStatus'])
            ->name('success-stories.toggle-status');

        // Website Manage
        Route::get('/site/manage', [WebsiteManageController::class, 'index'])->name('site.manage');

        // Contact Page Settings
        Route::get('/contact-settings', [ContactSettingController::class, 'edit'])->name('contact-settings.edit');
        Route::post('/contact-settings', [ContactSettingController::class, 'update'])->name('contact-settings.update');

        // Privacy Policy and Terms & Conditions
        Route::get('/legal-pages', [\App\Http\Controllers\Admin\LegalPageController::class, 'edit'])->name('legal-pages.edit');
        Route::put('/legal-pages', [\App\Http\Controllers\Admin\LegalPageController::class, 'update'])->name('legal-pages.update');

        // Stats Section
        Route::get('/stats', [StatsController::class, 'edit'])->name('stats.edit');
        Route::post('/stats', [StatsController::class, 'update'])->name('stats.update');

        // Process Steps
        Route::resource('process-steps', ProcessStepController::class);
        Route::post('/process-steps/{processStep}/toggle-status', [ProcessStepController::class, 'toggleStatus'])
            ->name('process-steps.toggle-status');

        // FAQs
        Route::resource('faqs', FaqController::class);
        Route::post('/faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])
            ->name('faqs.toggle-status');

        // Blogs
        Route::post('blogs/upload-image', [AdminBlogController::class, 'uploadImage'])->name('blogs.upload-image');
        Route::post('blogs/{blog}/toggle-publish', [AdminBlogController::class, 'togglePublish'])->name('blogs.toggle-publish');
        Route::resource('blogs', AdminBlogController::class);

        // Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

        Route::get('/about', [AdminAboutUsController::class, 'index'])->name('about.index');
        Route::get('/about/edit', [AdminAboutUsController::class, 'edit'])->name('about.edit');
        Route::put('/about', [AdminAboutUsController::class, 'update'])->name('about.update');

        // Activity Logs Routes


    });
});
