# Лабораторная работа 1: Создание нового приложения Laravel

**Автор:** Бизюк Андрей  
**Дата:** 18 января 2026 г.

---

## Цель
Научиться устанавливать Laravel через laravel/installer, настраивать окружение и подключать SQLite.

## Ход работы

### 1. Установка Laravel Installer

- Проверил наличие PHP 8.1+, Composer и расширений mbstring, xml, sqlite3.
- Установил Laravel Installer глобально:
  ```bash
  composer global require laravel/installer
  ```
- Добавил путь к Composer-библиотекам в PATH:
  ```bash
  export PATH="$PATH:$HOME/.config/composer/vendor/bin"
  ```
- Проверил версию:
  ```bash
  laravel --version
  # Laravel Installer 5.24.0
  ```

### 2. Создание проекта через laravel/installer

- Перешёл в рабочую директорию и создал проект:
  ```bash
  laravel new my-personal-blog
  ```
- В интерактивном меню выбрал:
  - Starter kit: No
  - Database: SQLite
  - Initialize Git: Yes

### 3. Настройка SQLite

- Создал файл базы данных:
  ```bash
  touch database/database.sqlite
  ```
- В файле `.env` указал абсолютный путь к базе данных:
  ```dotenv
  DB_CONNECTION=sqlite
  DB_DATABASE=/workspaces/labs/my-personal-blog/database/database.sqlite
  ```

### 4. Проверка подключения к БД

- Проверил подключение через Tinker:
  ```bash
  php artisan tinker
  >>> DB::connection()->getPdo();
  ```
  - Подключение успешно.

### 5. Запуск приложения

- Запустил сервер:
  ```bash
  php artisan serve --host=0.0.0.0 --port=8000
  ```
- Открыл в браузере http://localhost:8000 — стартовая страница Laravel отображается.

---

## Вывод

В результате работы был создан и настроен проект Laravel с использованием SQLite. Приложение успешно запускается и готово к дальнейшей разработке.
