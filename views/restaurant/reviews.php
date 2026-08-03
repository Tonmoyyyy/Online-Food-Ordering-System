<section class="section-title">
    <h2>Customer Reviews</h2>
</section>

<?php if (empty($reviews)): ?>
    <div class="card"><div class="card-body"><p>No reviews have been submitted yet.</p></div></div>
<?php else: ?>
    <div class="grid grid-2" style="gap:16px; margin-bottom:24px;">
        <?php foreach ($reviews as $review): ?>
            <div class="card">
                <div class="card-body">
                    <h3><?php echo sanitize($review['customer']); ?> <small>(<?php echo sanitize($review['rating']); ?>/5)</small></h3>
                    <p><?php echo sanitize($review['comment']); ?></p>
                    <p><strong>Order:</strong> #<?php echo sanitize($review['order_id']); ?> | <strong>Status:</strong> <?php echo sanitize($review['order_status']); ?></p>
                    <p><em>Posted: <?php echo sanitize($review['created_at']); ?></em></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
