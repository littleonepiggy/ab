# Блог на чистом PHP

Небольшой блог с категориями и статьями.

## Стек

- PHP 8.1+ (в Docker'е 8.4)
- MySQL 8
- Smarty 5
- SCSS + Bootstrap 5, сборка через Dart Sass
- Composer
- Docker Compose

## Что было реализовано

Всё по ТЗ: 
- Главная страница с категориями и 3 последними статьями
- Страница категорий со всеми категориями с пагинацией по 9 статей.
- Страница самой статьи со всей информацией о ней + три похожие статьи.
- Сидинг, докер образ

Заметки:
1. Не было сказано какая версия Smarty - использовал последнюю
2. Для нормализации создал доп. pivot таблицу post_category с отношением многие ко многим
3. Проверил корнер кейсы по типу категория без статей, 999 страница и прочее
4. Для вывода трёх похожих статей использовал коэффицент Жаккара 

## Запуск через Docker

```bash
docker compose up -d --build
docker compose exec php php database/seed.php
```

Сайт: `http://localhost:8080`

MySQL с хоста: `127.0.0.1:3307`, база `ab`, пользователь `ab`, пароль `ab`.

Схема применяется автоматически при первом создании volume. Если менял `database/schema.sql`:

```bash
docker compose down -v
docker compose up -d --build
docker compose exec php php database/seed.php
```

Остановка:

```bash
docker compose down
```

## Запуск локально

Требуются PHP 8.1+, Composer, MySQL и Node.js.

```bash
composer install
npm install
npm run css:build

mysql -u root -p -e "CREATE DATABASE ab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p ab < database/schema.sql
DB_PASSWORD=ваш_пароль php database/seed.php

DB_PASSWORD=ваш_пароль php -S 127.0.0.1:8000 -t public public/index.php

## Структура

```
public/              index.php, CSS и картинки
src/Controller/      HomeController, CategoryController, ArticleController
src/Repository/      SQL и работа с PDO
src/Main/            Router, View (Smarty), Database
templates/           шаблоны Smarty
database/            schema.sql и seed.php
assets/scss/         исходники SCSS
```

## SCSS

```bash
npm run css:build   # разовая сборка
npm run css:watch   # пересборка при изменениях
```

Скомпилированный CSS лежит в `public/assets/css/app.css`.

## Использование ИИ

ИИ использовался как вспомогательный инструмент: разбор требований, консультации по архитектуре, ревью и помощь с отдельными фрагментами кода. Итоговые решения проверялись и поддерживались мной.
