<section class="section-title">
    <h2>Sales Analytics</h2>
</section>

<div class="grid grid-2" style="margin-bottom:24px;">
    <div class="card"><div class="card-body"><h3>Total Orders</h3><p><?php echo sanitize($totalOrders); ?></p></div></div>
    <div class="card"><div class="card-body"><h3>Total Revenue</h3><p><?php echo formatCurrency($totalRevenue); ?></p></div></div>
    <div class="card"><div class="card-body"><h3>Avg Order Value</h3><p><?php echo formatCurrency($averageOrderValue); ?></p></div></div>
</div>

<section class="section-title">
    <h2>Orders by Day</h2>
</section>

<?php if (empty($ordersByDay)): ?>
    <div class="card"><div class="card-body"><p>No order data available.</p></div></div>
<?php else: ?>
    <table class="table">
        <thead><tr><th>Date</th><th># Orders</th><th>Revenue</th></tr></thead>
        <tbody>
            <?php foreach ($ordersByDay as $day): ?>
                <tr>
                    <td><?php echo sanitize($day['day']); ?></td>
                    <td><?php echo sanitize($day['orders']); ?></td>
                    <td><?php echo formatCurrency((float) $day['revenue']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<section class="section-title">
    <h2>Top Ordered Items</h2>
</section>

<?php if (empty($topItems)): ?>
    <div class="card"><div class="card-body"><p>No item data available.</p></div></div>
<?php else: ?>
    <table class="table">
        <thead><tr><th>Item</th><th>Quantity Ordered</th></tr></thead>
        <tbody>
            <?php foreach ($topItems as $item): ?>
                <tr>
                    <td><?php echo sanitize($item['name']); ?></td>
                    <td><?php echo sanitize($item['total_quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
