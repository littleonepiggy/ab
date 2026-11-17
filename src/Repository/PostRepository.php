<?php

namespace App\Repository;

use PDO;

final class PostRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function byCategory(int $categoryId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.title, p.slug, p.image, p.description, p.views, p.published_at
             FROM posts AS p
             JOIN post_category AS pc ON pc.post_id = p.id
             WHERE pc.category_id = ?
             ORDER BY p.published_at DESC'
        );
        $statement->execute([$categoryId]);

        return $statement->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.title, p.slug, p.image, p.description, p.content, p.views, p.published_at
             FROM posts AS p
             WHERE p.slug = ?'
        );
        $statement->execute([$slug]);
        $post = $statement->fetch();

        return $post === false ? null : $post;
    }
}
