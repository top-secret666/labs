<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Создаём тестового пользователя, если ещё нет
        \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Создать 5 категорий (если таблица пустая)
        if (Category::count() === 0) {
            Category::factory(5)->create();
        }

        // Создать 10 тегов (если таблица пустая)
        if (Tag::count() === 0) {
            Tag::factory(10)->create();
        }

        // Создать 20 постов с комментариями и привязать теги (если нет постов)
        if (Post::count() === 0) {
            Post::factory(20)
                ->has(Comment::factory(3))
                ->create()
                ->each(function ($post) {
                    $tags = Tag::inRandomOrder()->take(3)->pluck('id');
                    $post->tags()->attach($tags);

                    // Добавим случайные лайки для поста
                    $users = User::inRandomOrder()->take(5)->pluck('id');
                    foreach ($users as $userId) {
                        Like::create([
                            'user_id' => $userId,
                            'likeable_type' => get_class($post),
                            'likeable_id' => $post->id,
                        ]);
                    }
                });

            // Добавим лайки к некоторым комментариям
            Comment::inRandomOrder()->take(10)->get()->each(function ($comment) {
                $userId = User::inRandomOrder()->first()->id;
                Like::create([
                    'user_id' => $userId,
                    'likeable_type' => get_class($comment),
                    'likeable_id' => $comment->id,
                ]);
            });
        }

        // Если нет лайков, создадим некоторые лайки для существующих постов и комментариев
        if (Like::count() === 0) {
            Post::inRandomOrder()->take(10)->get()->each(function ($post) {
                $count = rand(1, 5);
                $users = User::inRandomOrder()->take($count)->pluck('id');
                foreach ($users as $userId) {
                    Like::create([
                        'user_id' => $userId,
                        'likeable_type' => get_class($post),
                        'likeable_id' => $post->id,
                    ]);
                }
            });

            Comment::inRandomOrder()->take(10)->get()->each(function ($comment) {
                $userId = User::inRandomOrder()->first()->id;
                Like::create([
                    'user_id' => $userId,
                    'likeable_type' => get_class($comment),
                    'likeable_id' => $comment->id,
                ]);
            });
        }
    }
}
