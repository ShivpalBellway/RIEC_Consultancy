<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeaturesSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('features')->count() > 0) {
            return;
        }

        $items = [
            ['icon' => 'fa-solid fa-graduation-cap', 'title' => 'Global University Admissions', 'subtitle' => 'Top universities across the world.'],
            ['icon' => 'fa-regular fa-file-lines', 'title' => 'Visa & Application Support', 'subtitle' => 'End-to-end guidance for a smooth process.'],
            ['icon' => 'fa-regular fa-handshake', 'title' => 'Institutional Partnerships', 'subtitle' => 'Building strong academic collaborations.'],
            ['icon' => 'fa-regular fa-user', 'title' => 'Personalized Counseling', 'subtitle' => 'Tailored solutions for every student.'],
        ];

        foreach ($items as $i => $item) {
            DB::table('features')->insert([
                'icon' => $item['icon'],
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'sort_order' => $i,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
