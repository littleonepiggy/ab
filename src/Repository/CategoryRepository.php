<?php

namespace App\Repository;

use PDO;

final class CategoryRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function withLatestPosts(int $limit = 3): array
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
             LIMIT ?'
        );

        foreach ($categories as &$category) {
            $posts->bindValue(1, $category['id'], PDO::PARAM_INT);
            $posts->bindValue(2, $limit, PDO::PARAM_INT);
            $posts->execute();
            $category['posts'] = $posts->fetchAll();
        }
        unset($category);

        return $categories;
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT c.id, c.name, c.slug, c.description
             FROM categories AS c
             WHERE c.slug = ?'
        );
        $statement->execute([$slug]);
        $category = $statement->fetch();

        return $category === false ? null : $category;
    }
}
