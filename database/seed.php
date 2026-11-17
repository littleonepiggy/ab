<?php

use App\Main\Database;

require dirname(__DIR__) . '/vendor/autoload.php';

$pdo = Database::connect(require dirname(__DIR__) . '/config/database.php');

$categories = [
    [
        'name' => 'Lorem',
        'slug' => 'lorem',
        'description' => 'Lorem.',
        'posts' => [
            [
                'title' => 'Lorem Ipsum',
                'slug' => 'lorem-1',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'published_at' => '2026-11-14',
                'views' => 128,
                'extra_categories' => ['ipsum'],
            ],
            [
                'title' => 'Lorem Ipsum',
                'slug' => 'lorem-2',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-11-10',
                'views' => 94,
            ],
            [
                'title' => 'Lorem Ipsum.',
                'slug' => 'lorem-3',
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
                'slug' => 'ipsum-1',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'published_at' => '2026-11-14',
                'views' => 128,
            ],
            [
                'title' => 'Lorem Ipsum',
                'slug' => 'ipsum-2',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-11-10',
                'views' => 94,
            ],
            [
                'title' => 'Lorem Ipsum.',
                'slug' => 'ipsum-3',
                'description' => 'Nulla facilisi.',
                'published_at' => '2026-11-06',
                'views' => 76,
            ],
            [
                'title' => 'Lorem Ipsum 4',
                'slug' => 'ipsum-4',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-11-05',
                'views' => 210,
            ],
            [
                'title' => 'Lorem Ipsum 5',
                'slug' => 'ipsum-5',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-11-03',
                'views' => 190,
            ],
            [
                'title' => 'Lorem Ipsum 6',
                'slug' => 'ipsum-6',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-11-01',
                'views' => 170,
            ],
            [
                'title' => 'Lorem Ipsum 7',
                'slug' => 'ipsum-7',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-10-30',
                'views' => 150,
            ],
            [
                'title' => 'Lorem Ipsum 8',
                'slug' => 'ipsum-8',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-10-28',
                'views' => 130,
            ],
            [
                'title' => 'Lorem Ipsum 9',
                'slug' => 'ipsum-9',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-10-26',
                'views' => 110,
            ],
            [
                'title' => 'Lorem Ipsum 10',
                'slug' => 'ipsum-10',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-10-24',
                'views' => 90,
            ],
            [
                'title' => 'Lorem Ipsum 11',
                'slug' => 'ipsum-11',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-10-22',
                'views' => 70,
            ],
            [
                'title' => 'Lorem Ipsum 12',
                'slug' => 'ipsum-12',
                'description' => 'Quisque metus turpis, commodo eget tincidunt eget, vulputate in odio.',
                'published_at' => '2026-10-20',
                'views' => 50,
            ],
        ],
    ],
    [
        'name' => 'Dolor',
        'slug' => 'dolor',
        'description' => 'Dolor.',
        'posts' => [],
    ],
];

$pdo->beginTransaction();

$pdo->exec('DELETE FROM post_category');
$pdo->exec('DELETE FROM posts');
$pdo->exec('DELETE FROM categories');

$insertCategory = $pdo->prepare(
    'INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)'
);
$insertPost = $pdo->prepare(
    'INSERT INTO posts (title, slug, image, description, content, views, published_at)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$insertLink = $pdo->prepare(
    'INSERT INTO post_category (post_id, category_id) VALUES (?, ?)'
);

$postCount = 0;
$categoryIds = [];

foreach ($categories as $category) {
    $insertCategory->execute([
        $category['name'],
        $category['slug'],
        $category['description'],
    ]);

    $categoryIds[$category['slug']] = (int) $pdo->lastInsertId();
}

foreach ($categories as $category) {
    foreach ($category['posts'] as $post) {
        $insertPost->execute([
            $post['title'],
            $post['slug'],
            null,
            $post['description'],
            $post['description'],
            $post['views'],
            $post['published_at'],
        ]);

        $postId = (int) $pdo->lastInsertId();

        $insertLink->execute([$postId, $categoryIds[$category['slug']]]);

        foreach ($post['extra_categories'] ?? [] as $categorySlug) {
            $insertLink->execute([$postId, $categoryIds[$categorySlug]]);
        }

        $postCount++;
    }
}

$pdo->commit();

echo 'Засижены: ' . count($categories) . ' категории, ' . $postCount . " статей.\n";
