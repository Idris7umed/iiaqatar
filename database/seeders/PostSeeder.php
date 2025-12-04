<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@iiaqatar.org')->first();
        $categories = Category::all();
        $tags = Tag::all();

        $posts = [
            [
                'title' => 'Welcome to IIAQATAR',
                'content' => 'Welcome to the Islamic Institute of Advanced Quality Assurance Training & Research. We are dedicated to providing world-class training and research in quality assurance...',
                'category_id' => $categories->where('name', 'News')->first()->id,
            ],
            [
                'title' => 'Introduction to Quality Assurance',
                'content' => 'Quality Assurance (QA) is a systematic process to determine whether products or services meet specified requirements. In this article, we explore the fundamentals...',
                'category_id' => $categories->where('name', 'Quality Assurance')->first()->id,
            ],
            [
                'title' => 'Islamic Ethics in Professional Practice',
                'content' => 'Islamic ethics provide a comprehensive framework for professional conduct. In this post, we examine how Islamic principles can guide quality assurance practices...',
                'category_id' => $categories->where('name', 'Islamic Studies')->first()->id,
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'content' => $postData['content'],
                'excerpt' => Str::limit($postData['content'], 150),
                'author_id' => $admin->id,
                'category_id' => $postData['category_id'],
                'status' => 'published',
                'published_at' => now(),
            ]);

            // Attach random tags
            $post->tags()->attach($tags->random(3)->pluck('id'));
        }
    }
}
