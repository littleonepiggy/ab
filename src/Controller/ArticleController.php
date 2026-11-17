<?php

namespace App\Controller;

use App\Main\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

final class ArticleController
{
    private CategoryRepository $categoryRepository;
    private PostRepository $postRepository;
    private View $view;

    public function __construct(
        CategoryRepository $categoryRepository,
        PostRepository $postRepository,
        View $view
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->view = $view;
    }

    public function index(): string
    {
        return $this->view->render('home.tpl', [
            'categories' => $this->categoryRepository->withLatestPosts(3),
        ]);
    }

    public function category(string $slug): string
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if ($category === null) {
            http_response_code(404);

            return $this->view->render('404.tpl', [
                'title' => 'Категория не найдена',
            ]);
        }

        $sort = ($_GET['sort'] ?? '') === 'views' ? 'views' : 'date';
        $perPage = 9;
        $total = $this->postRepository->countByCategory((int) $category['id']);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min(max(1, (int) ($_GET['page'] ?? 1)), $totalPages);
        $offset = ($page - 1) * $perPage;

        $category['posts'] = $this->postRepository->byCategory(
            (int) $category['id'],
            $sort,
            $perPage,
            $offset
        );

        return $this->view->render('category.tpl', [
            'title' => $category['name'],
            'category' => $category,
            'sort' => $sort,
            'page' => $page,
            'total' => $total,
            'totalPages' => $totalPages,
            'prevPage' => max(1, $page - 1),
            'nextPage' => min($totalPages, $page + 1),
        ]);
    }

    public function show(string $slug): string
    {
        $post = $this->postRepository->findBySlug($slug);

        if ($post === null) {
            http_response_code(404);

            return $this->view->render('404.tpl', [
                'title' => 'Статья не найдена',
            ]);
        }

        $post['categories'] = $this->categoryRepository->byPost((int) $post['id']);

        return $this->view->render('article.tpl', [
            'title' => $post['title'],
            'post' => $post,
        ]);
    }
}
