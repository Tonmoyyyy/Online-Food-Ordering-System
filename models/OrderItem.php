<?php
require_once __DIR__ . '/Model.php';

class OrderItem extends Model
{
    public function create(int $orderId, int $productId, int $quantity, float $price, float $subtotal): int
    {
        $this->execute('INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)', 'iiidd', [$orderId, $productId, $quantity, $price, $subtotal]);
        return $this->insertId();
    }

    public function allByOrderId(int $orderId): array
    {
        return $this->fetchAll('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?', 'i', [$orderId]);
    }
}
