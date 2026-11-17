{extends file='layout.tpl'}

{block name=title}{$category.name} — Блог{/block}

{block name=content}
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="/">← На главную</a>
        <h1 class="h2 mt-2 mb-2">{$category.name}</h1>
        <p class="text-muted mb-0">{$category.description}</p>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <span class="small text-muted">Сортировка:</span>
        <a class="btn btn-sm {if $sort == 'date'}btn-primary{else}btn-outline-primary{/if}"
           href="/category/{$category.slug}?sort=date">По дате</a>
        <a class="btn btn-sm {if $sort == 'views'}btn-primary{else}btn-outline-primary{/if}"
           href="/category/{$category.slug}?sort=views">По просмотрам</a>
    </div>

    <div class="row g-4">
        {foreach $category.posts as $post}
            <div class="col-md-6 col-xl-4">
                <article class="card h-100">
                    {if !empty($post.image)}
                        <img src="{$post.image}" class="card-img-top" alt="{$post.title}">
                    {/if}

                    <div class="card-body d-flex flex-column">
                        <h2 class="h5 card-title">
                            <a class="text-decoration-none" href="/article/{$post.slug}">{$post.title}</a>
                        </h2>

                        <p class="card-text text-muted">{$post.description}</p>

                        <div class="mt-auto d-flex justify-content-between gap-2 small text-muted">
                            <time datetime="{$post.published_at}">{$post.published_at|date_format:"d.m.Y"}</time>
                            <span>Просмотры: {$post.views}</span>
                        </div>
                    </div>
                </article>
            </div>
        {foreachelse}
            <p class="text-muted">В этой категории пока нет статей.</p>
        {/foreach}
    </div>
{/block}
