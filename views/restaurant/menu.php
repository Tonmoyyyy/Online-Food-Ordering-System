<section class="section-title">
    <h2>Restaurant Menu Management</h2>
</section>

<?php if (!empty($message)): ?>
    <div class="alert alert-success"><?php echo sanitize($message); ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo sanitize($error); ?></div>
<?php endif; ?>

<div class="grid grid-2" style="margin-bottom:24px;">
    <div class="card">
        <div class="card-body">
            <h3>Add New Food Item</h3>
            <form method="post" action="../public/index.php?route=restaurant&action=menu">
                <input type="hidden" name="action" value="add_food">
                <div class="input-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="textarea-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="input-group">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" step="0.01" min="0" required>
                </div>
                <div class="input-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" required>
                        <option value="">Select category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo sanitize($category['id']); ?>"><?php echo sanitize($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label class="form-label">Image filename</label>
                    <input type="text" name="image" placeholder="example.jpg">
                </div>
                <div class="input-group">
                    <label class="form-label">Status</label>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="button button-primary">Add Food Item</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3>Add Category</h3>
            <form method="post" action="../public/index.php?route=restaurant&action=menu">
                <input type="hidden" name="action" value="add_category">
                <div class="input-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" required>
                </div>
                <div class="textarea-group">
                    <label class="form-label">Description</label>
                    <textarea name="category_description" rows="3"></textarea>
                </div>
                <button type="submit" class="button button-primary">Add Category</button>
            </form>
        </div>
    </div>
</div>

<section class="section-title">
    <h2>Current Menu Items</h2>
</section>

<?php if (empty($products)): ?>
    <div class="card"><div class="card-body"><p>No menu items available.</p></div></div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr><th>Name</th><th>Category</th><th>Price</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo sanitize($product['name']); ?></td>
                    <td><?php echo sanitize($product['category_name']); ?></td>
                    <td><?php echo formatCurrency((float) $product['price']); ?></td>
                    <td><?php echo sanitize($product['status']); ?></td>
                    <td><a class="button" href="../public/index.php?route=restaurant&action=menu&delete_product=<?php echo sanitize($product['id']); ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
