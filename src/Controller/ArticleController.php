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

    public function index()
    {
        return $this->view->render('article.tpl', [
            'title' => 'Статьи',
        ]);
    }
}
