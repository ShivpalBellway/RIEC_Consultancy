<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('stats')->count() > 0) {
            return;
        }

        $items = [
            ['icon' => 'fa-solid fa-shield-halved', 'number' => '500+', 'title' => 'Successful', 'subtitle' => 'Admissions'],
            ['icon' => 'fa-solid fa-building-columns', 'number' => '100+', 'title' => 'Partner', 'subtitle' => 'Institutions'],
            ['icon' => 'fa-solid fa-globe', 'number' => '15+', 'title' => 'Countries', 'subtitle' => 'Covered'],
            ['icon' => 'fa-solid fa-users', 'number' => '98%', 'title' => 'Student', 'subtitle' => 'Satisfaction'],
        ];

        foreach ($items as $i => $item) {
            DB::table('stats')->insert([
                'icon' => $item['icon'],
                'number' => $item['number'],
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
