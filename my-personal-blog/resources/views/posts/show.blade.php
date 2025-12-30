@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <article class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
        <div class="text-sm text-gray-600 mb-4">{{ $post->created_at->format('d.m.Y') }} • {{ $post->category?->name }}</div>

        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="" class="w-full h-64 object-cover mb-4">
        @endif

        <div class="prose max-w-none">{!! nl2br(e($post->content)) !!}</div>

        <div class="mt-6">
            <h3 class="font-semibold">Теги:</h3>
            <div class="flex gap-2 mt-2">
                @foreach($post->tags as $tag)
                    <span class="px-2 py-1 bg-gray-100 rounded">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold">Комментарии ({{ $post->comments->count() }})</h3>
            <ul class="mt-3 space-y-3">
                @foreach($post->comments as $comment)
                    <li class="border rounded p-3 bg-gray-50">{{ $comment->text }}</li>
                @endforeach
            </ul>
        </div>
    </article>
</div>
@endsection
