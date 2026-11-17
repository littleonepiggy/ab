<?php

namespace App\Controller;

use App\Main\View;
use PDO;

final class ArticleController
{
    private PDO $pdo;
    private View $view;

    public function __construct(PDO $pdo, View $view)
    {
        $this->pdo = $pdo;
        $this->view = $view;
    }

    public function index(): string
    {
        return $this->view->render('home.tpl', [
            'categories' => $this->categories(),
        ]);
    }

    private function categories(): array
    {
        $categories = $this->pdo->query(
            'SELECT c.id, c.name, c.slug, c.description
             FROM categories AS c
             WHERE EXISTS (
                 SELECT 1
                 FROM post_category AS pc
                 WHERE pc.category_id = c.id
             )
             ORDER BY c.id'
        )->fetchAll();

        $posts = $this->pdo->prepare(
            'SELECT p.id, p.title, p.slug, p.image, p.description, p.views, p.published_at
             FROM posts AS p
             JOIN post_category AS pc ON pc.post_id = p.id
             WHERE pc.category_id = ?
             ORDER BY p.published_at DESC
             LIMIT 3'
        );

        foreach ($categories as &$category) {
            $posts->execute([$category['id']]);
            $category['posts'] = $posts->fetchAll();
        }
        unset($category);

        return $categories;
    }
}
