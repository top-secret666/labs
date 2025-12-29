<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $data['user_id'] = $request->user()->id ?? 1;

        $post = Post::create($data);

        // Парсинг тэгов: строка через запятую
        if (!empty($data['tags'])) {
            $names = array_filter(array_map('trim', explode(',', $data['tags'])));
            $tagIds = [];
            foreach ($names as $name) {
                if ($name === '') continue;
                $tag = Tag::firstOrCreate(
                    ['name' => $name],
                    ['slug' => Str::slug($name)]
                );
                $tagIds[] = $tag->id;
            }
            if (!empty($tagIds)) {
                $post->tags()->attach($tagIds);
            }
        }

        return redirect()->route('home')->with('success', 'Post created');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $post->update($data);

        // Обновляем тэги: синхронизируем
        $names = array_filter(array_map('trim', explode(',', $data['tags'] ?? '')));
        $tagIds = [];
        foreach ($names as $name) {
            if ($name === '') continue;
            $tag = Tag::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);

        return redirect()->route('home')->with('success', 'Post updated');
    }
}
