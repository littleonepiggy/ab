<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name=title}Блог{/block}</title>
    {* CSS появится после сборки Bootstrap: npm run css:build *}
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <header class="bg-white border-bottom">
        <div class="container py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <a class="h4 mb-0 text-dark text-decoration-none" href="/">Блог</a>

            <nav class="d-flex gap-3" aria-label="Основная навигация">
                <a class="link-secondary text-decoration-none" href="/">Главная</a>
            </nav>
        </div>
    </header>

    <main class="container py-4 py-lg-5 flex-grow-1">
        {block name=content}{/block}
    </main>

    <footer class="bg-white border-top py-3">
        <div class="container d-flex flex-wrap justify-content-between gap-2 small text-muted">
            <span>&copy; {$smarty.now|date_format:"%Y"} Блог</span>
            <a class="link-secondary text-decoration-none" href="/">На главную</a>
        </div>
    </footer>
</body>
</html>
