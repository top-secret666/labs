@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Свяжитесь с нами</h1>
        <x-form action="/contact" method="POST" buttonText="Отправить сообщение">
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-black">
            </div>
            <div>
                <label class="block text-gray-700">Сообщение</label>
                <textarea name="message" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-black" rows="4"></textarea>
            </div>
        </x-form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    const email = form.querySelector('input[type="email"]');
                    if (email && !email.value.includes('@')) {
                        e.preventDefault();
                        alert('Введите корректный email!');
                    }
                });
            }
        });
    </script>
@endsection
