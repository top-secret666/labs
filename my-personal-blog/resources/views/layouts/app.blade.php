<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Блог' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        <style>
            [x-cloak]{display:none !important;}
            /* fallback: скрыть mobile-only на экранах >= md, если утилиты Tailwind не применены */
            @media (min-width: 768px) {
                .mobile-only { display: none !important; }
            }
        </style>
</head>
<body class="bg-gray-100">
    <!-- Навигационное меню -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">Мой Блог</a>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-800">О нас</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-800">Контакты</a>
                </div>

                <div x-data="{ isOpen: false }" class="mobile-only">
                    <div class="flex items-center md:hidden">
                        <button @click="isOpen = !isOpen" class="p-2 text-gray-800 bg-white border rounded-md hover:bg-gray-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16"/>
                            </svg>
                        </button>
                        <div x-show="isOpen" x-cloak class="absolute top-16 right-4 bg-white shadow rounded-md mt-2 py-2 w-40 z-50">
                            <a href="{{ route('about') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">О нас</a>
                            <a href="{{ route('contact') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Контакты</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Контент страницы -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-4">
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @yield('content')
    </div>
</body>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</html>
