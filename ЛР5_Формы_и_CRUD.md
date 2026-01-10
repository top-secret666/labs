# ЛР5 — Формы для работы с сущностями (CRUD)

**Автор:** Бизюк Андрей

**Дата выполнения:** 10 января 2026

## Цель
Реализовать CRUD-операции для сущности "Пост" через формы, добавить серверную валидацию, обработку ошибок и стилизацию форм с Tailwind CSS.

## Краткое содержание выполненной работы
- Добавлены маршруты CRUD: `Route::resource('posts', PostController::class)->except(['show']);` и явный маршрут `Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');` ([routes/web.php](my-personal-blog/routes/web.php)).
- В `PostController` реализованы методы `create`, `store`, `edit`, `update`, `destroy` и `show`. Валидация, уникальность заголовка для `store`/`update`, обработка загрузки и удаления изображения, синхронизация тегов — находятся в [app/Http/Controllers/PostController.php](my-personal-blog/app/Http/Controllers/PostController.php).
- Шаблоны форм/страниц:
  - `resources/views/posts/_form.blade.php` — общий partial для `create` и `edit`, в нём: вывод ошибок, поля `title`, `category_id`, `tags[]`, `image`, `content`, чекбокс `remove_image` для удаления изображения и кнопка отправки.
  - `resources/views/posts/create.blade.php` и `resources/views/posts/edit.blade.php` — обёрнуты в белую карточку с паддингом и тенью для читабельности.
- UI/UX: все поля и кнопки стилизованы Tailwind-ом — единый стиль для инпутов, textarea, select; primary-кнопки с тенью и focus-ring; кнопки `Редактировать`/`Удалить`/`Читать` в карточках постов отформатированы для удобства.
- Удаление изображения: в `update` при `remove_image` удаляется файл из `storage` и поле `image` обнуляется.
- CSRF-защита: все формы содержат `@csrf`.

## Проверка соответствия заданию (чеклист)
- Создать формы для добавления, редактирования и удаления постов: Выполнено — `create`/`edit` страницы и форма в `_form.blade.php`; кнопка удаления реализована в карточках и вызывает `posts.destroy`.
- Реализовать валидацию данных на стороне сервера: Выполнено — правила в `store` и `update` в `PostController` (включая уникальность заголовка для соответствующего метода).
- Настроить отображение ошибок ввода: Выполнено — блок ошибок `@if ($errors->any()) ...` и поле-уровневые сообщения `@error('field')` в `_form.blade.php`.
- Использовать Tailwind CSS для стилизации форм: Выполнено — поля/кнопки/карточки оформлены Tailwind-классами; формы обёрнуты в белые карточки.

## Основные файлы (реализация)
- Контроллер: [my-personal-blog/app/Http/Controllers/PostController.php](my-personal-blog/app/Http/Controllers/PostController.php)
- Маршруты: [my-personal-blog/routes/web.php](my-personal-blog/routes/web.php)
- Partial формы: [my-personal-blog/resources/views/posts/_form.blade.php](my-personal-blog/resources/views/posts/_form.blade.php)
- Create/Edit страницы: [my-personal-blog/resources/views/posts/create.blade.php](my-personal-blog/resources/views/posts/create.blade.php), [my-personal-blog/resources/views/posts/edit.blade.php](my-personal-blog/resources/views/posts/edit.blade.php)
- Карточки/список: [my-personal-blog/resources/views/posts/_cards.blade.php](my-personal-blog/resources/views/posts/_cards.blade.php), [my-personal-blog/resources/views/posts/index.blade.php](my-personal-blog/resources/views/posts/index.blade.php)

## Команды для локальной проверки
1. Убедитесь, что симлинк для storage создан (если ещё не создан):

```bash
cd /workspaces/labs/my-personal-blog
php artisan storage:link
```

2. Запустить сервер для визуальной проверки:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

3. Проверка операций (через браузер):
- Открыть `http://127.0.0.1:8000/posts` — создать, отредактировать, удалить пост.

## Замечания и рекомендации
- Пока авторизация не настроена в проекте, кнопки редактирования/удаления отображаются всем (для тестирования). Рекомендуется ограничить видимость действий `edit`/`destroy` только для владельца записи после добавления auth (пример: `@if(auth()->check() && auth()->id() === $post->user_id)`).
- Для надёжной поддержки Unicode-поиска рекомендую использовать FTS5/ICU.
- Рекомендую добавить feature-тесты (PHPUnit) для CRUD-потока.

## Вывод
Все пункты лабораторной работы 5 выполнены: формы создания и редактирования, валидация, сообщения об ошибках, загрузка/удаление изображений, привязка тегов, и удаление сущности реализованы и доступны для тестовой проверки.

---
Файлы отчёта и реализация находятся в каталоге `my-personal-blog` репозитория.
