<?php

namespace App\Controller;

use App\Main\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

final class ArticleController
{
    private PostRepository $postRepository;
    private CategoryRepository $categoryRepository;
    private View $view;

    public function __construct(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        View $view
    ) {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->view = $view;
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

        $postId = (int) $post['id'];

        if (!isset($_SESSION['viewed_posts'][$postId])) {
            $this->postRepository->incrementViews($postId);
            $_SESSION['viewed_posts'][$postId] = true;
            $post['views']++;
        }

        $post['categories'] = $this->categoryRepository->byPost($postId);

        $categoryIds = array_map(
            static fn (array $category): int => (int) $category['id'],
            $post['categories']
        );

        return $this->view->render('article.tpl', [
            'title' => $post['title'],
            'post' => $post,
            'similar' => $this->postRepository->similar((int) $post['id'], $categoryIds),
        ]);
    }
}
