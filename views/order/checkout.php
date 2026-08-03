<section class="section-title">
    <h2>Checkout</h2>
</section>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo sanitize($error); ?></div>
<?php endif; ?>

<div class="grid grid-2">
    <div class="form-card">
        <h3>Delivery details</h3>
        <form method="post" action="../public/index.php?route=order&action=checkout">
            <div class="input-group">
                <label class="form-label">Name</label>
                <input type="text" value="<?php echo sanitize($currentUser['name']); ?>" disabled>
            </div>
            <div class="input-group">
                <label class="form-label">Email</label>
                <input type="text" value="<?php echo sanitize($currentUser['email']); ?>" disabled>
            </div>
            <div class="textarea-group">
                <label class="form-label" for="delivery_address">Delivery Address</label>
                <textarea id="delivery_address" name="delivery_address" rows="4"><?php echo sanitize($deliveryAddress); ?></textarea>
            </div>
            <button type="submit" class="button button-primary">Confirm Order</button>
        </form>
    </div>

    <div class="form-card">
        <h3>Order summary</h3>
        <table class="table">
            <thead>
                <tr><th>Product</th><th>Qty</th><th>Total</th></tr>
            </thead>
            <tbody>
                <?php foreach ($cartProducts as $product): ?>
                    <tr>
                        <td><?php echo sanitize($product['name']); ?></td>
                        <td><?php echo sanitize($product['quantity']); ?></td>
                        <td><?php echo formatCurrency((float)$product['subtotal']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2" style="text-align:right;font-weight:700;">Grand Total</td>
                    <td><?php echo formatCurrency(cartTotal()); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
