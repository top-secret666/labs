<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('posts.create', compact('categories', 'tags'));
    }

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'latest');
        $category = $request->query('category');
        $search = trim((string) $request->query('search', ''));
        $mode = $request->query('mode', 'prefix'); // 'prefix' or 'contains'

        $query = Post::with(['category', 'tags', 'comments']);

        if ($search !== '') {
            // Case-insensitive for Unicode: lower both sides (SQLite lower() may be limited,
            // but using mb_strtolower on PHP side helps for the query value).
            $searchLower = mb_strtolower($search, 'UTF-8');
            $pattern = $mode === 'contains' ? "%{$searchLower}%" : "{$searchLower}%";
            $query->whereRaw('LOWER(title) LIKE ?', [$pattern]);
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
        $validated = $request->validate([
            'title' => 'required|max:255|unique:posts,title',
            'content' => 'required|min:50',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $validated;
        $data['user_id'] = $request->user()->id ?? 1;

        // Сохраняем пост без изображения
        $post = Post::create($data);

        // Синхронизация тегов (если переданы)
        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        // Загрузка изображения
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->update(['image' => $path]);
        }

        return redirect()->route('posts.index')->with('success', 'Пост создан!');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|unique:posts,title,' . $post->id,
            'content' => 'required|min:50',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        $post->update($validated);

        // Синхронизация тегов
        $post->tags()->sync($validated['tags'] ?? []);

        // Удаление изображения
        if (!empty($validated['remove_image']) && $post->image) {
            Storage::disk('public')->delete($post->image);
            $post->update(['image' => null]);
        }

        // Обновление изображения
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $post->update(['image' => $path]);
        }

        return redirect()->route('posts.index')->with('success', 'Пост обновлен!');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Пост удален!');
    }
}

