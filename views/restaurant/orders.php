<section class="section-title">
    <h2>Restaurant Orders</h2>
</section>

<?php if (!empty($message)): ?>
    <div class="alert alert-success"><?php echo sanitize($message); ?></div>
<?php endif; ?>

<?php if (empty($orders)): ?>
    <div class="card"><div class="card-body"><p>No orders found.</p></div></div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Update Status</th></tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo sanitize($order['id']); ?></td>
                    <td><?php echo sanitize($order['customer']); ?></td>
                    <td><?php echo formatCurrency((float) $order['total_amount']); ?></td>
                    <td><?php echo sanitize($order['status']); ?></td>
                    <td>
                        <form method="post" action="../public/index.php?route=restaurant&action=orders" style="display:flex; gap:8px; align-items:center;">
                            <input type="hidden" name="order_id" value="<?php echo sanitize($order['id']); ?>">
                            <select name="status">
                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="preparing" <?php echo $order['status'] === 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                                <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn-primary">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
