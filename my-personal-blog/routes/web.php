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

// CRUD для постов
Route::resource('posts', PostController::class)->except(['show']);
// Отдельный маршрут для просмотра одного поста
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

// (routes for posts are provided by resource above)

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
