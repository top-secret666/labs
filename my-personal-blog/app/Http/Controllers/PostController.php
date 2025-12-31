<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'latest');
        $category = $request->query('category');
        $search = trim((string) $request->query('search', ''));

        $query = Post::with(['category', 'tags', 'comments']);

        if ($search !== '') {
            // Ищем заголовки, которые начинаются с введённого слова (prefix search)
            $query->where('title', 'LIKE', "{$search}%");
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        switch ($sort) {
            case 'popular':
                $query->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();

        // Статистика для сайдбара
        $stats = [
            'posts' => Post::count(),
            'comments' => Comment::count(),
            'popular_tag' => Tag::withCount('posts')->orderBy('posts_count', 'desc')->first()?->name,
        ];

        if ($request->ajax()) {
            return view('posts._cards', ['posts' => $posts, 'highlight' => (bool)($search || $category)])->render();
        }

        return view('posts.index', compact('posts', 'sort', 'categories', 'category', 'search', 'stats'));
    }

    public function show(Post $post)
    {
        $post->load(['tags', 'comments']);
        return view('posts.show', compact('post'));
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

