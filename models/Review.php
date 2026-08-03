<?php
require_once __DIR__ . '/Model.php';

class Review extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT r.id, r.order_id, r.rating, r.comment, r.created_at, u.name AS customer, o.total_amount, o.status AS order_status
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            JOIN orders o ON o.id = r.order_id
            ORDER BY r.created_at DESC'
        );
    }

    public function create(int $orderId, int $userId, int $rating, string $comment): int
    {
        $this->execute(
            'INSERT INTO reviews (order_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, ?)',
            'iiiss',
            [$orderId, $userId, $rating, $comment, date('Y-m-d H:i:s')]
        );
        return $this->insertId();
    }

    public function existsByOrderId(int $orderId): bool
    {
        $review = $this->fetchOne('SELECT id FROM reviews WHERE order_id = ?', 'i', [$orderId]);
        return !empty($review);
    }
}
