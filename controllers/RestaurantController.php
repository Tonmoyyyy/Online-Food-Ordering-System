<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Review.php';

class RestaurantController extends Controller
{
    public function dashboard(): void
    {
        requireRestaurantManager();

        $productModel = new Product();
        $categoryModel = new Category();
        $orderModel = new Order();

        $totalItems = count($productModel->all());
        $totalCategories = count($categoryModel->all());
        $totalOrders = count($orderModel->all());
        $recentOrders = array_slice($orderModel->all(), 0, 6);

        $totalRevenueRow = db_fetch_one('SELECT SUM(total_amount) AS total_revenue FROM orders');
        $totalRevenue = (float) ($totalRevenueRow['total_revenue'] ?? 0);
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $this->render('restaurant/dashboard.php', [
            'totalItems' => $totalItems,
            'totalCategories' => $totalCategories,
            'totalOrders' => $totalOrders,
            'recentOrders' => $recentOrders,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $averageOrderValue,
            'currentUser' => getCurrentUser(),
        ]);
    }

    public function menu(): void
    {
        requireRestaurantManager();
        $categoryModel = new Category();
        $productModel = new Product();

        $message = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'add_food') {
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $price = (float) ($_POST['price'] ?? 0);
                $categoryId = (int) ($_POST['category_id'] ?? 0);
                $status = $_POST['status'] ?? 'active';
                $image = trim($_POST['image'] ?? 'placeholder.png');

                if ($name === '' || $price <= 0 || $categoryId <= 0) {
                    $error = 'Name, price and category are required for new food items.';
                } else {
                    $productModel->create($categoryId, $name, $description, $price, $image, $status);
                    $message = 'Food item added to the menu.';
                }
            } elseif ($action === 'add_category') {
                $name = trim($_POST['category_name'] ?? '');
                $description = trim($_POST['category_description'] ?? '');
                if ($name === '') {
                    $error = 'Category name is required.';
                } else {
                    $categoryModel->create($name, $description);
                    $message = 'Category created successfully.';
                }
            }
        }

        if (isset($_GET['delete_product'])) {
            $deleteId = (int) $_GET['delete_product'];
            if ($deleteId > 0) {
                $productModel->delete($deleteId);
                $this->redirect('index.php?route=restaurant&action=menu');
            }
        }

        $categories = $categoryModel->all();
        $products = $productModel->all();

        $this->render('restaurant/menu.php', [
            'categories' => $categories,
            'products' => $products,
            'message' => $message,
            'error' => $error,
            'currentUser' => getCurrentUser(),
        ]);
    }

    public function orders(): void
    {
        requireRestaurantManager();

        $orderModel = new Order();
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
            $status = $_POST['status'] ?? 'pending';
            if ($orderId > 0) {
                $orderModel->updateStatus($orderId, $status);
                $message = 'Order status updated.';
            }
        }

        $orders = $orderModel->all();

        $this->render('restaurant/orders.php', [
            'orders' => $orders,
            'message' => $message,
            'currentUser' => getCurrentUser(),
        ]);
    }

    public function reviews(): void
    {
        requireRestaurantManager();

        $reviewModel = new Review();
        $reviews = $reviewModel->all();

        $this->render('restaurant/reviews.php', [
            'reviews' => $reviews,
            'currentUser' => getCurrentUser(),
        ]);
    }

    public function analytics(): void
    {
        requireRestaurantManager();

        $ordersByDay = db_fetch_all(
            'SELECT DATE(created_at) AS day, COUNT(*) AS orders, SUM(total_amount) AS revenue FROM orders GROUP BY DATE(created_at) ORDER BY DATE(created_at) DESC LIMIT 14'
        );

        $topItems = db_fetch_all(
            'SELECT p.name, SUM(oi.quantity) AS total_quantity FROM order_items oi JOIN products p ON p.id = oi.product_id GROUP BY oi.product_id ORDER BY total_quantity DESC LIMIT 5'
        );

        $orderRows = db_fetch_one('SELECT COUNT(*) AS total_orders, SUM(total_amount) AS total_revenue FROM orders');
        $totalOrders = (int) ($orderRows['total_orders'] ?? 0);
        $totalRevenue = (float) ($orderRows['total_revenue'] ?? 0);
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $this->render('restaurant/analytics.php', [
            'ordersByDay' => $ordersByDay,
            'topItems' => $topItems,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $averageOrderValue,
            'currentUser' => getCurrentUser(),
        ]);
    }
}
