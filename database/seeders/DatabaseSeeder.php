<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Program;
use App\Models\EligibilityField;
use App\Models\FormSection;
use App\Models\FormField;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            FeaturesSeeder::class,
            StatsSeeder::class,
            ServicesSeeder::class,
            SuccessStoriesSeeder::class,
            NoticeSeeder::class,
            AdditionalAdminsSeeder::class,
        ]);
        Admin::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'      => 'REIAC Admin',
                'password'  => '12345678',   // will be hashed by mutator
                'is_active' => true,
            ]
        );

        // ── Sample Programs ────────────────────────────────────
        $programs = [
            [
                'name'         => 'Bachelor / Associate Degree',
                'country'      => 'Nepal & Bangladesh',
                'program_type' => 'bachelor',
                'description'  => 'Undergraduate degree program for students from Nepal & Bangladesh.',
                'is_active'    => true,
                'sort_order'   => 1,
            ],
            [
                'name'         => 'Language Program',
                'country'      => 'Nepal & Bangladesh',
                'program_type' => 'language',
                'description'  => 'Korean language program for students from Nepal & Bangladesh.',
                'is_active'    => true,
                'sort_order'   => 2,
            ],
            [
                'name'         => 'Bachelor / Associate Degree',
                'country'      => 'Sri Lanka',
                'program_type' => 'bachelor',
                'description'  => 'Undergraduate degree program for students from Sri Lanka.',
                'is_active'    => true,
                'sort_order'   => 3,
            ],
            [
                'name'         => 'Master Degree',
                'country'      => 'Any Country',
                'program_type' => 'master',
                'description'  => 'Postgraduate degree program open to students from any country.',
                'is_active'    => true,
                'sort_order'   => 4,
            ],
        ];

        foreach ($programs as $programData) {
            $program = Program::create($programData);

            // ── Default Eligibility Fields per program ────────
            $this->seedEligibilityFields($program);

            // ── Default Form Sections per program ─────────────
            $this->seedFormSections($program);
        }
    }

    private function seedEligibilityFields(Program $program): void
    {
        $fields = [
            [
                'label'       => 'Minimum Age',
                'field_key'   => 'min_age',
                'field_type'  => 'number',
                'is_required' => true,
                'min_value'   => '15',
                'max_value'   => '35',
                'unit'        => 'years',
                'placeholder' => 'Enter your age',
                'sort_order'  => 1,
            ],
            [
                'label'       => 'SEE GPA / O/L Score / Bachelor GPA',
                'field_key'   => 'see_gpa',
                'field_type'  => 'number',
                'is_required' => true,
                'min_value'   => '1.6',
                'max_value'   => '4.0',
                'unit'        => 'GPA',
                'placeholder' => 'Enter your GPA (e.g. 3.2)',
                'sort_order'  => 2,
            ],
            [
                'label'       => 'HSC GPA / A/L Score / Percentage',
                'field_key'   => 'hsc_gpa',
                'field_type'  => 'number',
                'is_required' => false,
                'min_value'   => '1.6',
                'max_value'   => '5.0',
                'unit'        => 'GPA',
                'placeholder' => 'Enter your HSC GPA',
                'sort_order'  => 3,
            ],
            [
                'label'       => 'IELTS Overall Score',
                'field_key'   => 'ielts_score',
                'field_type'  => 'number',
                'is_required' => false,
                'min_value'   => '4.0',
                'max_value'   => '9.0',
                'unit'        => 'Band Score',
                'placeholder' => 'Enter IELTS score (optional)',
                'validation_message' => 'IELTS score must be between 4.0 and 9.0',
                'sort_order'  => 4,
            ],
            [
                'label'       => 'TOPIK Level',
                'field_key'   => 'topik_level',
                'field_type'  => 'select',
                'is_required' => false,
                'options'     => ['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5', 'Level 6'],
                'placeholder' => 'Select TOPIK level (if any)',
                'sort_order'  => 5,
            ],
            [
                'label'       => 'Graduation Year',
                'field_key'   => 'graduation_year',
                'field_type'  => 'number',
                'is_required' => true,
                'min_value'   => '2015',
                'max_value'   => '2026',
                'unit'        => 'year',
                'placeholder' => 'Enter graduation year (e.g. 2023)',
                'sort_order'  => 6,
            ],
        ];

        foreach ($fields as $field) {
            EligibilityField::create(array_merge($field, [
                'program_id' => $program->id,
                'is_active'  => true,
            ]));
        }
    }

    private function seedFormSections(Program $program): void
    {
        $sections = [
            ['name' => 'Personal Information',       'sort_order' => 1],
            ['name' => 'Passport Details',           'sort_order' => 2],
            ['name' => 'Academic Information',       'sort_order' => 3],
            ['name' => 'Country Specific Information','sort_order'=> 4],
            ['name' => 'Language Proficiency',       'sort_order' => 5],
        ];

        foreach ($sections as $sectionData) {
            $section = FormSection::create(array_merge($sectionData, [
                'program_id' => $program->id,
                'is_active'  => true,
            ]));

            // Add default fields to "Personal Information" section
            if ($sectionData['name'] === 'Personal Information') {
                $personalFields = [
                    ['label'=>'Full Name (as per passport)', 'field_key'=>'full_name',   'field_type'=>'text',  'is_required'=>true,  'sort_order'=>1],
                    ['label'=>'Date of Birth',               'field_key'=>'dob',          'field_type'=>'date',  'is_required'=>true,  'sort_order'=>2],
                    ['label'=>'Gender',                      'field_key'=>'gender',        'field_type'=>'select','is_required'=>true,  'options'=>['Male','Female','Other'], 'sort_order'=>3],
                    ['label'=>'Nationality',                 'field_key'=>'nationality',   'field_type'=>'text',  'is_required'=>true,  'sort_order'=>4],
                    ['label'=>'Phone Number',                'field_key'=>'phone',         'field_type'=>'phone', 'is_required'=>true,  'sort_order'=>5],
                    ['label'=>'Email Address',               'field_key'=>'email',         'field_type'=>'email', 'is_required'=>true,  'sort_order'=>6],
                ];
                foreach ($personalFields as $f) {
                    FormField::create(array_merge($f, ['section_id'=>$section->id,'is_active'=>true]));
                }
            }
        }
    }
}
