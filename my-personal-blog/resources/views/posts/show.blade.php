@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <article class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-4 text-black">{{ $post->title }}</h1>
        <div class="text-sm mb-4 text-black">{{ $post->created_at->format('d.m.Y') }} • {{ $post->category?->name }}</div>

        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="" class="w-full h-64 object-cover mb-4">
        @endif

        <div class="prose max-w-none text-black">{!! nl2br(e($post->content)) !!}</div>

        <div class="mt-6">
            <h3 class="font-semibold">Теги:</h3>
            <div class="flex gap-2 mt-2">
                @foreach($post->tags as $tag)
                    <span class="px-2 py-1 bg-gray-100 rounded text-black">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>

        @if($post->media->isNotEmpty())
            <div class="mt-6">
                <h3 class="font-semibold text-black">Файлы:</h3>
                <div class="grid grid-cols-3 gap-4 mt-3">
                    @foreach($post->media as $media)
                        <div class="relative bg-white border rounded overflow-hidden">
                            @if (Str::startsWith($media->type, 'image'))
                                <img src="{{ Storage::disk('s3-fake')->url($media->path) }}" class="w-full h-48 object-cover">
                            @else
                                <div class="p-4">
                                    <p class="text-sm text-black">{{ basename($media->path) }}</p>
                                    <a href="{{ Storage::disk('s3-fake')->url($media->path) }}" class="text-blue-600 text-sm" target="_blank">Открыть</a>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('posts.deleteFile', $media) }}" class="absolute top-2 right-2">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white p-1 rounded">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-6">
            <h3 class="font-semibold text-black">Комментарии ({{ $post->comments->count() }})</h3>
            <ul class="mt-3 space-y-3">
                @foreach($post->comments as $comment)
                    <li class="border rounded p-3 bg-gray-50 text-black">{{ $comment->text }}</li>
                @endforeach
            </ul>
        </div>
    </article>
</div>
@endsection
