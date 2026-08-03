<?php
require_once __DIR__ . '/Model.php';

class Product extends Model
{
    public function featured(): array
    {
        return $this->fetchAll(
            'SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.status = ? ORDER BY p.created_at DESC LIMIT 6',
            's',
            ['active']
        );
    }

    public function all(int $categoryId = 0): array
    {
        if ($categoryId > 0) {
            return $this->fetchAll(
                'SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.category_id = ? AND p.status = ? ORDER BY p.name',
                'is',
                [$categoryId, 'active']
            );
        }

        return $this->fetchAll(
            'SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.status = ? ORDER BY p.name',
            's',
            ['active']
        );
    }

    public function allForAdmin(): array
    {
        return $this->fetchAll(
            'SELECT p.id, p.name, p.description, p.price, p.image, p.status, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.name'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM products WHERE id = ? AND status = ?', 'is', [$id, 'active']);
    }

    public function create(int $categoryId, string $name, string $description, float $price, string $image, string $status): int
    {
        $this->execute(
            'INSERT INTO products (category_id, name, description, price, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
            'issdsss',
            [$categoryId, $name, $description, $price, $image, $status, date('Y-m-d H:i:s')]
        );
        return $this->insertId();
    }

    public function delete(int $id): int
    {
        return $this->execute('DELETE FROM products WHERE id = ?', 'i', [$id]);
    }
}
