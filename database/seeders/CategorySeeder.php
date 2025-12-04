<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Islamic Studies' => 'Articles and content about Islamic studies',
            'Quality Assurance' => 'Quality assurance and testing methodologies',
            'Training' => 'Training programs and workshops',
            'Research' => 'Research papers and findings',
            'Technology' => 'Technology and innovation in quality assurance',
            'News' => 'Latest news and updates',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }
    }
}
