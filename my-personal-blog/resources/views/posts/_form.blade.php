@props(['post' => null, 'categories' => [], 'tags' => []])

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded-md mb-6">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}" enctype="multipart/form-data" class="space-y-6 text-black">
    @csrf
    @isset($post)
        @method('PUT')
    @endisset

    <div>
        <label class="block text-sm text-gray-700 mb-2">Заголовок *</label>
        <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" 
               class="block w-full px-3 py-2 bg-white border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('title') border-red-500 @enderror">
        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-700 mb-2">Категория *</label>
        <select name="category_id" class="block w-full px-3 py-2 bg-white border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm text-gray-700 mb-2">Теги</label>
        <select name="tags[]" multiple class="block w-full px-3 py-2 bg-white border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300" size="4">
            @php $selected = old('tags', isset($post) ? $post->tags->pluck('id')->toArray() : []); @endphp
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" {{ in_array($tag->id, $selected) ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm text-gray-700 mb-2">Изображение</label>
        <input type="file" name="image" class="block w-full text-sm text-gray-700">
        @if (isset($post) && $post->image)
            <img src="{{ asset('storage/' . $post->image) }}" class="mt-2 w-32">
            <div class="mt-2">
                <label class="flex items-center">
                    <input type="checkbox" name="remove_image" value="1" class="mr-2">
                    <span class="text-red-600">Удалить изображение</span>
                </label>
            </div>
        @endif
    </div>

    <div>
        <label class="block text-sm text-gray-700 mb-2">Прикрепить файлы (PDF, MP4, изображения)</label>
        <input type="file" name="files[]" multiple class="block w-full text-sm text-gray-700">
        @error('files.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    @isset($post)
        @if($post->media->isNotEmpty())
            <div>
                <h4 class="font-semibold mb-2">Существующие файлы</h4>
                <div class="space-y-2">
                    @foreach($post->media as $media)
                        <label class="flex items-center gap-3 p-2 border rounded">
                            <input type="checkbox" name="delete_files[]" value="{{ $media->id }}" class="w-4 h-4">
                            <span class="text-sm text-black">{{ basename($media->path) }}</span>
                            <a href="{{ route('posts.viewFile', $media) }}" class="ml-auto text-blue-600 text-sm" target="_blank">Открыть</a>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-1">Отметьте файлы для удаления при сохранении.</p>
            </div>
        @endif
    @endisset

    <div>
        <label class="block text-sm text-gray-700 mb-2">Содержание *</label>
        <textarea name="content" rows="6" 
                  class="block w-full px-3 py-2 bg-white border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('content') border-red-500 @enderror">{{ old('content', $post->content ?? '') }}</textarea>
        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 shadow focus:outline-none focus:ring-2 focus:ring-blue-300">
        {{ isset($post) ? 'Обновить' : 'Создать' }}
    </button>
</form>
