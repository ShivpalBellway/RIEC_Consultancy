<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('services')->count() > 0) {
            return;
        }

        $items = [
            [
                'icon' => 'fa-solid fa-graduation-cap',
                'image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=700&q=80',
                'title' => 'University Admissions',
                'excerpt' => 'We help students apply to top universities worldwide with the right guidance.',
            ],
            [
                'icon' => 'fa-regular fa-file-lines',
                'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=700&q=80',
                'title' => 'Visa & Documentation',
                'excerpt' => 'Expert support for visa applications, documents and interview preparation.',
            ],
            [
                'icon' => 'fa-solid fa-globe',
                'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=700&q=80',
                'title' => 'Institutional Partnerships',
                'excerpt' => 'We connect institutions and create meaningful academic collaborations.',
            ],
            [
                'icon' => 'fa-solid fa-users-gear',
                'image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=700&q=80',
                'title' => 'Career Counseling',
                'excerpt' => 'Personalized counseling to help students choose the right path for their future.',
            ],
        ];

        foreach ($items as $i => $item) {
            DB::table('services')->insert([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'excerpt' => $item['excerpt'],
                'image' => $item['image'],
                'icon' => $item['icon'],
                'sort_order' => $i,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
