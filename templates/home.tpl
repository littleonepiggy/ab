{extends file='layout.tpl'}

{block name=title}Главная — Блог{/block}

{block name=content}
    <div class="mb-4">
        <h1 class="h2 mb-1">Главная</h1>
        <p class="text-muted mb-0">Последние статьи по категориям</p>
    </div>

    {foreach $categories as $category}
        <section class="mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h2 class="h3 mb-1">{$category.name}</h2>
                    <p class="text-muted mb-0">{$category.description}</p>
                </div>

                <a class="btn btn-outline-primary" href="/category/{$category.slug}">Все статьи</a>
            </div>

            <div class="row g-4">
                {foreach $category.posts as $post}
                    <div class="col-md-6 col-xl-4">
                        <article class="card h-100">
                            {if !empty($post.image)}
                                <img src="{$post.image}" class="card-img-top" alt="{$post.title}">
                            {/if}

                            <div class="card-body d-flex flex-column">
                                <h3 class="h5 card-title">
                                    <a class="text-decoration-none" href="/article/{$post.slug}">{$post.title}</a>
                                </h3>

                                <p class="card-text text-muted">{$post.description}</p>

                                <div class="mt-auto d-flex justify-content-between gap-2 small text-muted">
                                    <time datetime="{$post.published_at}">{$post.published_at|date_format:"d.m.Y"}</time>
                                    <span>Просмотры: {$post.views}</span>
                                </div>
                            </div>
                        </article>
                    </div>
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p class="text-muted">Пока нет категорий со статьями.</p>
    {/foreach}
{/block}
