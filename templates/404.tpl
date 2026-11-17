{extends file='layout.tpl'}

{block name=title}{$title} — Блог{/block}

{block name=content}
    <div class="py-5">
        <h1 class="h2 mb-3">{$title}</h1>
        <p class="text-muted mb-4">Такой страницы нет или она была удалена.</p>
        <a class="btn btn-primary" href="/">На главную</a>
    </div>
{/block}
