<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../models/Review.php';

class OrderController extends Controller
{
    public function history(): void
    {
        requireLogin();
        $orderModel = new Order();
        $orders = $orderModel->allByUser($_SESSION['user']['id']);

        $this->render('order/history.php', [
            'orders' => $orders,
            'currentUser' => getCurrentUser(),
        ]);
    }

    public function checkout(): void
    {
        requireLogin();
        $cartProducts = getCartProducts();

        if (empty($cartProducts)) {
            $this->redirect('index.php?route=cart');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deliveryAddress = sanitize($_POST['delivery_address'] ?? '');
            if ($deliveryAddress === '') {
                $error = 'Delivery address is required.';
            }

            if ($error === '') {
                $orderModel = new Order();
                $orderId = $orderModel->create($_SESSION['user']['id'], cartTotal(), $deliveryAddress);
                $orderItemModel = new OrderItem();
                foreach ($cartProducts as $product) {
                    $orderItemModel->create($orderId, $product['id'], $product['quantity'], $product['price'], $product['subtotal']);
                }
                clearCart();
                $this->redirect('index.php?route=order&action=success&id=' . $orderId);
            }
        }

        $this->render('order/checkout.php', [
            'cartProducts' => $cartProducts,
            'error' => $error,
            'currentUser' => getCurrentUser(),
            'deliveryAddress' => $_POST['delivery_address'] ?? '',
        ]);
    }

    public function success(): void
    {
        requireLogin();
        $orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $orderModel = new Order();
        $order = $orderModel->findById($orderId, $_SESSION['user']['id']);
        if (!$order) {
            $this->redirect('index.php?route=home');
        }

        $items = (new OrderItem())->allByOrderId($orderId);

        $reviewModel = new Review();
        $reviewSubmitted = false;
        $reviewMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
            $comment = sanitize($_POST['comment'] ?? '');
            if ($rating < 1 || $rating > 5 || $comment === '') {
                $reviewMessage = 'Please provide a valid rating and comment.';
            } elseif ($reviewModel->existsByOrderId($orderId)) {
                $reviewMessage = 'You have already submitted a review for this order.';
            } else {
                $reviewModel->create($orderId, $_SESSION['user']['id'], $rating, $comment);
                $reviewSubmitted = true;
                $reviewMessage = 'Thank you! Your review has been submitted.';
            }
        }

        $this->render('order/success.php', [
            'order' => $order,
            'items' => $items,
            'currentUser' => getCurrentUser(),
            'reviewSubmitted' => $reviewSubmitted,
            'reviewMessage' => $reviewMessage,
            'reviewExists' => $reviewModel->existsByOrderId($orderId),
        ]);
    }
}
