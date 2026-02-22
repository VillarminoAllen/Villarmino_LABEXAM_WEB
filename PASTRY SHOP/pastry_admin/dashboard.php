<?php
session_start();
include 'db.php';

$notification = "";

if (isset($_GET['archive'])) {
    $id = intval($_GET['archive']);
    mysqli_query($conn, "UPDATE products SET status='archived' WHERE product_id=$id");
    header("Location: dashboard.php"); exit();
}

if (isset($_GET['restore'])) {
    $id = intval($_GET['restore']);
    mysqli_query($conn, "UPDATE products SET status='active' WHERE product_id=$id");
    header("Location: dashboard.php"); exit();
}

if (isset($_GET['permdelete'])) {
    $id = intval($_GET['permdelete']);
    mysqli_query($conn, "DELETE FROM products WHERE product_id=$id");
    header("Location: dashboard.php"); exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $id          = intval($_POST['edit_id']);
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price       = floatval($_POST['price']);
    $qty         = intval($_POST['quantity']);

    if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../img/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename    = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $db_save_path = "img/" . $filename;
            $sql = "UPDATE products SET product_name='$name', product_description='$description', product_price=$price, product_quantity=$qty, image_path='$db_save_path' WHERE product_id=$id";
        } else {
            $notification = "<div class='error-msg'>❌ Image upload failed.</div>";
            $sql = "";
        }
    } else {
        $sql = "UPDATE products SET product_name='$name', product_description='$description', product_price=$price, product_quantity=$qty WHERE product_id=$id";
    }

    if ($sql && mysqli_query($conn, $sql)) {
        $notification = "<div class='success-banner'>✨ Product updated successfully!</div>";
    } elseif ($sql) {
        $notification = "<div class='error-msg'>❌ Database Error: " . mysqli_error($conn) . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['edit_id'])) {
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price       = floatval($_POST['price']);
    $qty         = intval($_POST['quantity']);
    $target_dir  = __DIR__ . "/../img/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $notification = "<div class='error-msg'>❌ No valid image file received.</div>";
    } else {
        $filename     = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file  = $target_dir . $filename;
        $db_save_path = "img/" . $filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO products (product_name, product_description, product_price, product_quantity, image_path) VALUES ('$name', '$description', '$price', '$qty', '$db_save_path')";
            if (mysqli_query($conn, $sql)) {
                $notification = "<div class='success-banner'>✨ $name has been added!</div>";
            } else {
                $notification = "<div class='error-msg'>❌ Database Error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            $notification = "<div class='error-msg'>❌ Failed to upload image.</div>";
        }
    }
}

$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM products WHERE product_id=$edit_id");
    $edit_product = mysqli_fetch_assoc($res);
}

$active_result   = mysqli_query($conn, "SELECT * FROM products WHERE status='active' ORDER BY product_id DESC");
$archived_result = mysqli_query($conn, "SELECT * FROM products WHERE status='archived' ORDER BY product_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | The Rise & Bake</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-dashboard">

    <nav class="admin-nav">
        <div class="admin-nav-brand">🥐 The Rise & Bake <span>Admin</span></div>
        <div class="admin-nav-actions">
            <button class="btn btn-add" onclick="openAddPanel()">+ Add Product</button>
            <a href="logout.php" class="btn btn-delete" onclick="return confirm('Are you sure you want to sign out?')">Sign Out</a>
        </div>
    </nav>

    <div class="panel-overlay" id="panel-overlay" onclick="togglePanel()"></div>
    <div class="add-panel" id="add-panel">
        <div class="panel-header">
            <h2><?php echo $edit_product ? '✏️ Edit Product' : '🧁 Add New Product'; ?></h2>
            <button class="panel-close" onclick="togglePanel()">✕</button>
        </div>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data">
                <?php if ($edit_product): ?>
                    <input type="hidden" name="edit_id" value="<?php echo $edit_product['product_id']; ?>">
                <?php endif; ?>

                <input type="text" name="name" placeholder="Pastry Name"
                    value="<?php echo $edit_product ? htmlspecialchars($edit_product['product_name']) : ''; ?>" required>

                <textarea name="description" rows="3" placeholder="Product Description"><?php echo $edit_product ? htmlspecialchars($edit_product['product_description'] ?? '') : ''; ?></textarea>

                <div class="row">
                    <input type="number" name="price" placeholder="Price (₱)" step="0.01"
                        value="<?php echo $edit_product ? $edit_product['product_price'] : ''; ?>" required>
                    <input type="number" name="quantity" placeholder="Stock" min="0"
                        value="<?php echo $edit_product ? $edit_product['product_quantity'] : ''; ?>" required>
                </div>

                <p class="upload-label"><?php echo $edit_product ? 'Upload new image (leave blank to keep current):' : 'Upload Pastry Photo:'; ?></p>
                <input type="file" name="image" accept="image/*" <?php echo $edit_product ? '' : 'required'; ?>>

                <div class="panel-actions">
                    <?php if ($edit_product): ?>
                        <button type="submit" class="btn btn-edit">💾 Save Changes</button>
                        <a href="dashboard.php" class="btn btn-cancel">✖ Cancel</a>
                    <?php else: ?>
                        <button type="submit" class="btn btn-add">Add to Oven</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-wrapper">
        <?php echo $notification; ?>

        <!-- ACTIVE PRODUCTS -->
        <h2>✅ Active Products</h2>
        <?php if (mysqli_num_rows($active_result) > 0): ?>
        <table>
            <tr><th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
            <?php while($row = mysqli_fetch_assoc($active_result)): ?>
            <tr>
                <td><img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt=""></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['product_description'] ?? '—'); ?></td>
                <td>₱<?php echo number_format($row['product_price'], 2); ?></td>
                <td><?php echo $row['product_quantity']; ?></td>
                <td><span class="badge badge-active">Active</span></td>
                <td>
                    <div class="actions">
                        <a href="?edit=<?php echo $row['product_id']; ?>" class="btn btn-edit">✏️ Edit</a>
                        <a href="?archive=<?php echo $row['product_id']; ?>" class="btn btn-archive" onclick="return confirm('Archive this product?')">📦 Archive</a>
                        <a href="?permdelete=<?php echo $row['product_id']; ?>" class="btn btn-delete" onclick="return confirm('Permanently delete? This cannot be undone!')">🗑 Delete</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p class="empty-msg">No active products.</p>
        <?php endif; ?>

        <!-- ARCHIVED PRODUCTS -->
        <h2>📦 Archived Products</h2>
        <?php if (mysqli_num_rows($archived_result) > 0): ?>
        <table>
            <tr><th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
            <?php while($row = mysqli_fetch_assoc($archived_result)): ?>
            <tr>
                <td><img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt=""></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['product_description'] ?? '—'); ?></td>
                <td>₱<?php echo number_format($row['product_price'], 2); ?></td>
                <td><?php echo $row['product_quantity']; ?></td>
                <td><span class="badge badge-archived">Archived</span></td>
                <td>
                    <div class="actions">
                        <a href="?edit=<?php echo $row['product_id']; ?>" class="btn btn-edit">✏️ Edit</a>
                        <a href="?restore=<?php echo $row['product_id']; ?>" class="btn btn-restore" onclick="return confirm('Restore this product?')">♻️ Restore</a>
                        <a href="?permdelete=<?php echo $row['product_id']; ?>" class="btn btn-delete" onclick="return confirm('Permanently delete? This cannot be undone!')">🗑 Delete</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p class="empty-msg">No archived products.</p>
        <?php endif; ?>

    </div>

<script>
    function togglePanel() {
    document.getElementById('add-panel').classList.toggle('open');
    document.getElementById('panel-overlay').classList.toggle('open');
}

function openAddPanel() {
    // If URL has edit parameter, redirect to clean URL first
    if (window.location.search.includes('edit')) {
        window.location.href = 'dashboard.php?addpanel=1';
    } else {
        togglePanel();
    }
}


    <?php if ($edit_product): ?>
        window.onload = function() { togglePanel(); }
    <?php endif; ?>


</script>

</body>
</html>