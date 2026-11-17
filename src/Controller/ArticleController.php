<?php

namespace App\Controller;

use App\Main\View;

final class ArticleController
{
    private View $view;

    public function __construct(View $view)
    {
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
        $categories = [
            [
                'name' => 'Lorem',
                'slug' => 'lorem',
                'description' => 'Lorem.',
                'posts' => [
                    [
                        'title' => 'Lorem Ipsum',
                        'slug' => 'test-1',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'published_at' => '2026-11-14',
                        'views' => 128,
                    ],
                    [
                        'title' => 'Lorem Ipsum',
                        'slug' => 'test-2',
                        'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                        'published_at' => '2026-11-10',
                        'views' => 94,
                    ],
                    [
                        'title' => 'Lorem Ipsum.',
                        'slug' => 'test-3',
                        'description' => 'Nulla facilisi.',
                        'published_at' => '2026-11-06',
                        'views' => 76,
                    ],
                ],
            ],
            [
                'name' => 'Ipsum',
                'slug' => 'ipsum',
                'description' => 'Ipsum.',
                'posts' => [
                    [
                        'title' => 'Lorem Ipsum',
                        'slug' => 'test-1',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'published_at' => '2026-11-14',
                        'views' => 128,
                    ],
                    [
                        'title' => 'Lorem Ipsum',
                        'slug' => 'test-2',
                        'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                        'published_at' => '2026-11-10',
                        'views' => 94,
                    ],
                    [
                        'title' => 'Lorem Ipsum.',
                        'slug' => 'test-3',
                        'description' => 'Nulla facilisi.',
                        'published_at' => '2026-11-06',
                        'views' => 76,
                    ],
                ],
            ],
        ];

        foreach ($categories as &$category) {
            usort(
                $category['posts'],
                static fn (array $a, array $b): int => strcmp($b['published_at'], $a['published_at'])
            );

            $category['posts'] = array_slice($category['posts'], 0, 3);
        }
        unset($category);

        return $categories;
    }
}
