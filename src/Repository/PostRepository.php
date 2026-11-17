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

    public function incrementViews(int $postId): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE posts SET views = views + 1 WHERE id = ?'
        );
        $statement->execute([$postId]);
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

    public function similar(int $postId, array $categoryIds, int $limit = 3): array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.title, p.slug, p.image, p.description, p.views, p.published_at,
                    GROUP_CONCAT(pc.category_id) AS category_ids
             FROM posts AS p
             LEFT JOIN post_category AS pc ON pc.post_id = p.id
             WHERE p.id <> ?
             GROUP BY p.id'
        );
        $statement->execute([$postId]);
        $posts = $statement->fetchAll();

        $current = array_map('intval', $categoryIds);
        $currentCount = count($current);

        foreach ($posts as &$post) {
            $candidate = $post['category_ids'] === null
                ? []
                : array_map('intval', explode(',', $post['category_ids']));

            $intersection = count(array_intersect($current, $candidate));
            $union = $currentCount + count(array_unique($candidate)) - $intersection;

            $post['score'] = $union > 0 ? $intersection / $union : 0;
            unset($post['category_ids']);
        }
        unset($post);

        usort($posts, static function (array $a, array $b): int {
            return $b['score'] <=> $a['score']
                ?: strcmp($b['published_at'], $a['published_at']);
        });

        return array_slice($posts, 0, $limit);
    }
}
