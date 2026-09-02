<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProcessStep;

class ProcessStepsSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            ['icon' => 'fa-regular fa-comments',    'title' => 'Consultation', 'description' => 'We understand your goals and requirements.',     'sort_order' => 1],
            ['icon' => 'fa-regular fa-file-lines',  'title' => 'Planning',     'description' => 'We create a personalized plan for you.',          'sort_order' => 2],
            ['icon' => 'fa-solid fa-folder-open',   'title' => 'Application',  'description' => 'We assist you in the complete process.',           'sort_order' => 3],
            ['icon' => 'fa-regular fa-star',        'title' => 'Success',      'description' => 'Achieve your dream with our support.',             'sort_order' => 4],
        ];

        foreach ($steps as $step) {
            ProcessStep::create($step);
        }
    }
}
