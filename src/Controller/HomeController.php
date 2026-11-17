<?php

namespace App\Controller;

use App\Main\View;
use App\Repository\CategoryRepository;

final class HomeController
{
    private CategoryRepository $categoryRepository;
    private View $view;

    public function __construct(CategoryRepository $categoryRepository, View $view)
    {
        $this->categoryRepository = $categoryRepository;
        $this->view = $view;
    }

    public function index(): string
    {
        return $this->view->render('home.tpl', [
            'categories' => $this->categoryRepository->withLatestPosts(3),
        ]);
    }
}
