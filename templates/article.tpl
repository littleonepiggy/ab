{extends file='layout.tpl'}

{block name=title}{$post.title} — Блог{/block}

{block name=content}
    <article class="mx-auto" style="max-width: 760px;">
        <a class="link-secondary text-decoration-none" href="/">← Все статьи</a>

        <header class="mt-3 mb-4">
            <div class="d-flex flex-wrap gap-2 mb-2">
                {foreach $post.categories as $category}
                    <a class="badge text-bg-light text-decoration-none" href="/category/{$category.slug}">
                        {$category.name}
                    </a>
                {/foreach}
            </div>

            <h1 class="display-5 mb-3">{$post.title}</h1>
            <p class="lead text-muted">{$post.description}</p>

            <div class="d-flex flex-wrap gap-3 small text-muted">
                <time datetime="{$post.published_at}">{$post.published_at|date_format:"d.m.Y"}</time>
                <span>Просмотры: {$post.views}</span>
            </div>
        </header>

        {if !empty($post.image)}
            <img class="img-fluid rounded mb-4" src="{$post.image}" alt="{$post.title}">
        {/if}

        <div class="fs-5" style="white-space: pre-line">{$post.content}</div>
    </article>

    {if $similar}
        <section class="mt-5">
            <h2 class="h3 mb-3">Похожие статьи</h2>

            <div class="row g-4">
                {foreach $similar as $related}
                    <div class="col-md-4">
                        <article class="card h-100">
                            {if !empty($related.image)}
                                <img src="{$related.image}" class="card-img-top" alt="{$related.title}">
                            {/if}

                            <div class="card-body d-flex flex-column">
                                <h3 class="h6 card-title">
                                    <a class="text-decoration-none" href="/article/{$related.slug}">{$related.title}</a>
                                </h3>

                                <p class="card-text small text-muted">{$related.description}</p>

                                <div class="mt-auto d-flex justify-content-between gap-2 small text-muted">
                                    <time datetime="{$related.published_at}">
                                        {$related.published_at|date_format:"d.m.Y"}
                                    </time>
                                    <span>Просмотры: {$related.views}</span>
                                </div>
                            </div>
                        </article>
                    </div>
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
