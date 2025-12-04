<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Testing',
            'QA',
            'Automation',
            'Islamic Education',
            'Certification',
            'Professional Development',
            'Best Practices',
            'Standards',
            'Compliance',
            'Assessment',
        ];

        foreach ($tags as $name) {
            Tag::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
