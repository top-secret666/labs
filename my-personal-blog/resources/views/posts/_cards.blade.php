@foreach ($posts as $post)
    <div class="bg-white rounded-lg shadow-md overflow-hidden h-full flex flex-col">
        @if ($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
        @endif
        <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center mb-2 text-sm text-gray-600">
                <span>{{ $post->created_at->format('d.m.Y') }}</span>
                <span class="mx-2">•</span>
                    <span class="text-blue-600">{{ $post->category?->name }}</span>
            </div>
                <h2 class="text-xl font-semibold mb-2 text-black">{{ $post->title }}</h2>
                <p class="mb-4 text-black">{{ \Illuminate\Support\Str::limit($post->content, 150) }}</p>
            <div class="mt-auto flex flex-col">
                <div class="w-full mb-3">
                    <div class="flex flex-wrap gap-2 max-w-full">
                        @foreach ($post->tags as $tag)
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="w-full flex items-center justify-start">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('posts.edit', $post) }}" class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-sm hover:bg-blue-100">Редактировать</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Удалить пост? Это действие необратимо.');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-200">Удалить</button>
                            </form>
                    </div>
                </div>

                <div class="w-full mt-2">
                    <a href="{{ route('posts.show', $post) }}" class="text-blue-800 font-semibold">Читать →</a>
                </div>
            </div>
        </div>
    </div>
@endforeach
