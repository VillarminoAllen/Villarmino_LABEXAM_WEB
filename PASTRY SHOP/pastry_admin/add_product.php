<?php
session_start();
include 'db.php'; // Ensure this connects to 'pastry_shop' database

$notification = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and clean text data
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $qty = mysqli_real_escape_string($conn, $_POST['product_quantity']);

    // INSERT query without any image references
    $sql = "INSERT INTO products (product_name, product_description, product_price, product_quantity) 
            VALUES ('$name', '$description', '$price', '$qty')";
    
    if (mysqli_query($conn, $sql)) {
        $notification = "<div style='color: #155724; background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>✨ Success! $name added to the oven.</div>";
    } else {
        $notification = "<div style='color: #721c24; background: #f8d7da; padding: 10px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-form-container" style="max-width: 500px; margin: 50px auto; font-family: sans-serif;">
        <h2>Add New Pastry</h2>
        <?php echo $notification; ?>
        
        <form action="" method="POST">
            <label>Name</label><br>
            <input type="text" name="product_name" required style="width:100%; margin-bottom:15px;"><br>
            
            <label>Description</label><br>
            <textarea name="product_description" required style="width:100%; height:80px; margin-bottom:15px;"></textarea><br>
            
            <div style="display:flex; gap: 10px; margin-bottom:15px;">
                <div style="flex:1;">
                    <label>Price (₱)</label>
                    <input type="number" step="0.01" name="product_price" required style="width:100%;">
                </div>
                <div style="flex:1;">
                    <label>Quantity</label>
                    <input type="number" name="product_quantity" required style="width:100%;">
                </div>
            </div>
            
            <button type="submit" style="background: #4b3621; color: white; padding: 10px 20px; border: none; cursor: pointer; width: 100%;">Save Product</button>
        </form>
        <p style="margin-top:20px;"><a href="../pastry_shop/dashboard.php">← View User Dashboard</a></p>
    </div>
</body>
</html>