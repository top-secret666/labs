<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', ['title' => 'Главная страница']);
    }

    public function about()
    {
        return view('pages.about', ['title' => 'О нас']);
    }

    public function contact()
    {
        return view('pages.contact', ['title' => 'Контакты']);
    }

    public function showPost($slug)
    {
        return view('pages.post', ['slug' => $slug, 'title' => $slug]);
    }
}
