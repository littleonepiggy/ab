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

        $category['posts'] = $this->postRepository->byCategory((int) $category['id']);

        return $this->view->render('category.tpl', [
            'title' => $category['name'],
            'category' => $category,
        ]);
    }
}
