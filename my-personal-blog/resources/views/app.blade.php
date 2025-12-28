<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        {{-- Alpine.js для интерактивных компонентов --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @viteReactRefresh
        @vite(['resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        {{-- Навигация --}}
        <nav class="bg-white dark:bg-gray-800 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    {{-- Логотип --}}
                    <div class="flex items-center gap-8">
                        <a href="/" class="text-xl font-bold text-gray-800 dark:text-white">
                            {{ config('app.name', 'Мой Блог') }}
                        </a>
                        
                        {{-- Десктопное меню --}}
                        <div class="hidden md:flex gap-8">
                            <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors">
                                Главная
                            </a>
                            <a href="{{ route('about') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors">
                                О нас
                            </a>
                            <a href="{{ route('contact') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors">
                                Контакты
                            </a>
                        </div>
                    </div>

                    {{-- Бургер-меню для мобильных --}}
                    <div x-data="{ isOpen: false }" 
                         class="md:hidden"
                         @keydown.escape="isOpen = false">
                        
                        {{-- Кнопка бургера --}}
                        <button @click="isOpen = !isOpen" 
                                class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors">
                            <svg x-show="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        {{-- Выпадающее меню --}}
                        <div x-show="isOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="isOpen = false"
                             class="absolute top-16 right-4 left-4 bg-white dark:bg-gray-800 shadow-lg rounded-md z-50 overflow-hidden"
                             style="display: none;">
                            <div class="py-2">
                                <a href="{{ route('home') }}"
                                   @click="isOpen = false"
                                   class="block px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors border-b dark:border-gray-700">
                                    Главная
                                </a>
                                <a href="{{ route('about') }}"
                                   @click="isOpen = false"
                                   class="block px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors border-b dark:border-gray-700">
                                    О нас
                                </a>
                                <a href="{{ route('contact') }}"
                                   @click="isOpen = false"
                                   class="block px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    Контакты
                                </a>
                            </div>
                        </div>

                        {{-- Затемнение фона при открытом меню --}}
                        <div x-show="isOpen" 
                             class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
                             style="display: none;"
                             @click="isOpen = false">
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Основное содержимое --}}
        <main>
            @inertia
        </main>

        {{-- Дополнительный скрипт для управления состоянием меню --}}
        <script>
            // Закрытие меню при изменении размера экрана
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) { // md breakpoint
                    const menu = document.querySelector('[x-data]');
                    if (menu && menu.__x && menu.__x.getState('isOpen')) {
                        menu.__x.setUnobservedData('isOpen', false);
                    }
                }
            });
        </script>
    </body>
</html>