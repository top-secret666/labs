@foreach ($posts as $post)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if ($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
        @endif
        <div class="p-6">
                <div class="flex items-center mb-2 text-sm text-gray-600">
                <span>{{ $post->created_at->format('d.m.Y') }}</span>
                <span class="mx-2">•</span>
                    <span class="text-blue-600">{{ $post->category?->name }}</span>
            </div>
                <h2 class="text-xl font-semibold mb-2 text-black">{{ $post->title }}</h2>
                <p class="mb-4 text-black">{{ \Illuminate\Support\Str::limit($post->content, 150) }}</p>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    @foreach ($post->tags as $tag)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $tag->name }}</span>
                    @endforeach
                </div>
                <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:text-blue-800">Читать →</a>
            </div>
        </div>
    </div>
@endforeach
