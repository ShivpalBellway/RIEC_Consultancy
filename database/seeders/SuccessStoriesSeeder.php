<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuccessStoriesSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('success_stories')->count() > 0) {
            return;
        }

        $items = [
            [
                'name' => 'Min Jae Kim',
                'role' => 'Student, Canada',
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'review' => 'REIAC guided me through my university application and visa process. Today, I am studying at my dream university!'
            ],
            [
                'name' => 'Sarah Lee',
                'role' => 'Student, Australia',
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'review' => 'Their team is professional, friendly and always available to help. Highly recommended!'
            ],
            [
                'name' => 'Prof. Park',
                'role' => 'University Partner',
                'image' => 'https://randomuser.me/api/portraits/women/65.jpg',
                'review' => 'Thanks to REIAC , our institution found the right partner abroad.'
            ],
        ];

        foreach ($items as $i => $item) {
            DB::table('success_stories')->insert([
                'name' => $item['name'],
                'role' => $item['role'],
                'image' => $item['image'],
                'review' => $item['review'],
                'sort_order' => $i,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
