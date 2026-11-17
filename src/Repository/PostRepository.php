<?php

namespace App\Repository;

use PDO;

final class PostRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function byCategory(int $categoryId, string $sort = 'date', int $limit = 9, int $offset = 0): array
    {
        $orderBy = match ($sort) {
            'views' => 'p.views DESC',
            default => 'p.published_at DESC',
        };

        $statement = $this->pdo->prepare(
            "SELECT p.id, p.title, p.slug, p.image, p.description, p.views, p.published_at
             FROM posts AS p
             JOIN post_category AS pc ON pc.post_id = p.id
             WHERE pc.category_id = ?
             ORDER BY {$orderBy}
             LIMIT ? OFFSET ?"
        );

        $statement->bindValue(1, $categoryId, PDO::PARAM_INT);
        $statement->bindValue(2, $limit, PDO::PARAM_INT);
        $statement->bindValue(3, $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countByCategory(int $categoryId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM post_category WHERE category_id = ?'
        );
        $statement->execute([$categoryId]);

        return (int) $statement->fetchColumn();
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
