@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h1 class="text-2xl font-bold text-black mb-4">Новый пост</h1>
        @include('posts._form', ['categories' => $categories, 'tags' => $tags])
    </div>
</div>
@endsection
