<?php

namespace App\Controller;

use App\Main\View;

final class ArticleController
{
    private $view;
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(): string
    {
        return $this->view->render('article.tpl', [
            'title' => 'Как устроен блог на чистом PHP',
            'article' => [
                'description' => 'Первый шаблон: общий layout, SCSS и вывод данных через Smarty.',
                'published_at' => '16 ноября 2026',
                'views' => 128,
            ],
        ]);
    }
}
