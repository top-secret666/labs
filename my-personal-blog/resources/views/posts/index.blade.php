@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-black mb-6">Все посты</h1>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="flex items-center gap-4">
                <a href="{{ route('posts.index', array_merge(request()->all(), ['sort' => 'latest'])) }}" data-sort="latest"
                    class="px-4 py-2 {{ $sort==='latest' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md">Новые</a>
                <a href="{{ route('posts.index', array_merge(request()->all(), ['sort' => 'popular'])) }}" data-sort="popular"
                    class="px-4 py-2 {{ $sort==='popular' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md">Популярные</a>
          </div>

        <div class="flex items-center gap-4">
            <form id="filters" method="GET" action="{{ route('posts.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="sort" id="sort-input" value="{{ $sort }}">
                <select name="category" class="border rounded px-3 py-2 text-black bg-white">
                    <option value="">Все категории</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (int)$category === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="mode" id="search-mode" class="border rounded px-3 py-2 text-black bg-white">
                    <option value="prefix" {{ (request('mode','prefix') === 'prefix') ? 'selected' : '' }}>Начинается с</option>
                    <option value="contains" {{ (request('mode','prefix') === 'contains') ? 'selected' : '' }}>Содержит</option>
                </select>

                <input id="search-input" type="text" name="search" value="{{ $search ?? '' }}" placeholder="Поиск по заголовку" class="border rounded px-3 py-2 text-black bg-white" />
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Поиск</button>
            </form>
        </div>
    </div>

    <div class="md:flex md:gap-6">
        <div class="md:flex-1">
            <div id="posts-grid" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @include('posts._cards', ['posts' => $posts, 'highlight' => ($search || $category)])
            </div>

            <div id="loading" class="mt-6 text-center hidden">Загрузка...</div>

            <nav id="pagination-links" class="mt-8">
                {{ $posts->links() }}
            </nav>
        </div>

        <aside class="md:w-1/3 mt-6 md:mt-0">
            <div class="bg-white p-6 rounded-lg shadow-md">
                 <h3 class="text-gray-600 mb-4">Статистика блога</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="text-black">Всего постов: <strong>{{ $stats['posts'] }}</strong></li>
                    <li class="text-black">Всего комментариев: <strong>{{ $stats['comments'] }}</strong></li>
                    <li class="text-black">Популярный тег: <strong>{{ $stats['popular_tag'] ?? '-' }}</strong></li>
                </ul>
            </div>
        </aside>
    </div>

    <script>
        (function(){
            let page = {{ $posts->currentPage() }};
            const lastPage = {{ $posts->lastPage() }};
            let loading = false;

            const buildUrl = (p) => {
                const params = new URLSearchParams(window.location.search);
                params.set('page', p);
                return window.location.pathname + '?' + params.toString();
            };

            const loadMore = async () => {
                if (loading) return;
                if (page >= lastPage) return;
                loading = true;
                document.getElementById('loading').classList.remove('hidden');
                page++;
                try {
                    const res = await fetch(buildUrl(page), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (res.ok) {
                        const html = await res.text();
                        const container = document.getElementById('posts-grid');
                        // create temp wrapper
                        const temp = document.createElement('div');
                        temp.innerHTML = html;
                        // append children
                        Array.from(temp.children).forEach(child => container.appendChild(child));

                        if (page >= lastPage) {
                            // remove pagination links if present
                            const p = document.getElementById('pagination-links');
                            if (p) p.style.display = 'none';
                            window.removeEventListener('scroll', handleScroll);
                        }
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    loading = false;
                    document.getElementById('loading').classList.add('hidden');
                }
            };

            const handleScroll = () => {
                if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 700)) {
                    loadMore();
                }
            };

            window.addEventListener('scroll', handleScroll);

            // Если пользователь нажимает на ссылку сортировки, отправляем форму с текущими полями
            document.querySelectorAll('[data-sort]').forEach(el => {
                el.addEventListener('click', function(e){
                    const sort = this.getAttribute('data-sort');
                    const form = document.getElementById('filters');
                    if (!form) return; // fallback to link
                    e.preventDefault();
                    const sortInput = document.getElementById('sort-input');
                    if (sortInput) sortInput.value = sort;
                    form.submit();
                });
            });

            // Trim search input before submitting the filters form
            const filtersForm = document.getElementById('filters');
            if (filtersForm) {
                filtersForm.addEventListener('submit', function(e){
                    const si = document.getElementById('search-input');
                    if (si) {
                        si.value = si.value.trim();
                    }
                    const sortInput = document.getElementById('sort-input');
                    if (sortInput && !sortInput.value) {
                        sortInput.value = 'latest';
                    }
                });
            }
        })();
    </script>
</div>
@endsection
