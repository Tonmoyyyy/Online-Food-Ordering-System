<?php
require_once __DIR__ . '/../inc/functions.php';
requireAdmin();

// Handle add/remove and reorder
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_id'])) {
        $uid = (int) $_POST['add_id'];
        // insert with highest priority + 1 (support existing schema)
        $posRow = db_fetch_one('SELECT MAX(COALESCE(priority, position, 0)) AS maxpos FROM featured_restaurants');
        $pos = (int) ($posRow['maxpos'] ?? 0) + 1;
        // try to insert into known columns (restaurant_id/priority)
        db_execute('INSERT IGNORE INTO featured_restaurants (restaurant_id, priority) VALUES (?, ?)', 'ii', [$uid, $pos]);
    }
    if (isset($_POST['remove_id'])) {
        $uid = (int) $_POST['remove_id'];
        db_execute('DELETE FROM featured_restaurants WHERE restaurant_id = ?', 'i', [$uid]);
    }
    if (isset($_POST['positions']) && is_array($_POST['positions'])) {
        foreach ($_POST['positions'] as $id => $position) {
            db_execute('UPDATE featured_restaurants SET priority = ? WHERE id = ?', 'ii', [(int)$position, (int)$id]);
        }
    }
    redirect('featured_restaurants.php');
}

$featured = db_fetch_all('SELECT fr.id, fr.restaurant_id AS user_id, fr.priority AS position, u.name, u.email FROM featured_restaurants fr JOIN users u ON u.id = fr.restaurant_id ORDER BY fr.priority ASC');
$restaurants = db_fetch_all("SELECT id, name, email, status FROM users WHERE role = 'restaurant_manager' ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Featured Restaurants</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style> .small-input{width:80px;} </style>
</head>
<body>
<header>
    <div class="container">
        <div class="site-title">Admin - Featured Restaurants</div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="featured_restaurants.php">Featured</a>
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>
<main class="container">
    <section class="section-title"><h2>Manage Featured Restaurants</h2></section>

    <div class="card"><div class="card-body">
        <h3>Current Featured</h3>
        <?php if (empty($featured)): ?>
            <p>No featured restaurants set.</p>
        <?php else: ?>
            <form method="post" action="featured_restaurants.php">
                <table class="table">
                    <thead><tr><th>Position</th><th>Name</th><th>Email</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach ($featured as $f): ?>
                            <tr>
                                <td><input class="small-input" type="number" name="positions[<?php echo sanitize($f['id']); ?>]" value="<?php echo sanitize($f['position']); ?>"></td>
                                <td><?php echo sanitize($f['name']); ?></td>
                                <td><?php echo sanitize($f['email']); ?></td>
                                <td>
                                    <form method="post" action="featured_restaurants.php" style="display:inline;">
                                        <input type="hidden" name="remove_id" value="<?php echo sanitize($f['user_id']); ?>">
                                        <button class="button button-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button class="button" type="submit">Save Positions</button>
            </form>
        <?php endif; ?>
    </div></div>

    <div class="card" style="margin-top:16px;"><div class="card-body">
        <h3>Add Restaurant to Featured</h3>
        <form method="post" action="featured_restaurants.php">
            <select name="add_id">
                <?php foreach ($restaurants as $r): ?>
                    <option value="<?php echo sanitize($r['id']); ?>"><?php echo sanitize($r['name']) . ' (' . sanitize($r['email']) . ')'; ?></option>
                <?php endforeach; ?>
            </select>
            <button class="button">Add</button>
        </form>
    </div></div>

</main>
<footer><div class="container"><p>&copy; <?php echo date('Y'); ?> Online Food Ordering System</p></div></footer>
</body>
</html>
