<section class="section-title">
    <h2>Restaurant Manager Dashboard</h2>
</section>

<div class="grid grid-2" style="margin-bottom:24px;">
    <div class="card"><div class="card-body"><h3>Menu Items</h3><p><?php echo sanitize($totalItems); ?> items</p></div></div>
    <div class="card"><div class="card-body"><h3>Categories</h3><p><?php echo sanitize($totalCategories); ?> categories</p></div></div>
    <div class="card"><div class="card-body"><h3>Orders</h3><p><?php echo sanitize($totalOrders); ?> total orders</p></div></div>
    <div class="card"><div class="card-body"><h3>Revenue</h3><p><?php echo formatCurrency($totalRevenue); ?></p></div></div>
    <div class="card"><div class="card-body"><h3>Avg Order Value</h3><p><?php echo formatCurrency($averageOrderValue); ?></p></div></div>
</div>

<section class="section-title">
    <h2>Recent Orders</h2>
</section>

<?php if (empty($recentOrders)): ?>
    <div class="card"><div class="card-body"><p>No recent orders yet.</p></div></div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Placed</th></tr>
        </thead>
        <tbody>
            <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td><?php echo sanitize($order['id']); ?></td>
                    <td><?php echo sanitize($order['customer']); ?></td>
                    <td><?php echo formatCurrency((float) $order['total_amount']); ?></td>
                    <td><?php echo sanitize($order['status']); ?></td>
                    <td><?php echo sanitize($order['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
