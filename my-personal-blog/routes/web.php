<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;


use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use App\Models\Post as PostModel;

// Статические страницы
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Динамический маршрут с параметром
Route::get('/post/{slug}', [PageController::class, 'showPost'])->where('slug', '[a-z0-9-]+');

// Обработка формы контактов (POST)
Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
        'message' => 'required|string',
    ]);

    // Здесь можно отправить письмо, сохранить в БД или в лог. Пока просто флеш и редирект.
    session()->flash('success', 'Сообщение отправлено. Спасибо!');

    return redirect()->route('contact');
})->name('contact.send');

// Маршруты для создания/редактирования постов
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
});

// Список постов и просмотр
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
