<?php
include 'db.php';
$result = $conn->query("SELECT * FROM products");
?>

<h2>Current Pastry Inventory</h2>
<table border="1" cellpadding="10" cellspacing="0">
    <tr style="background: #eee;">
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['product_id']; ?></td>
        <td><img src="<?php echo $row['image_path']; ?>" width="50"></td>
        <td><?php echo $row['name']; ?></td>
        <td>$<?php echo $row['price']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td>
            <a href="edit.php?id=<?php echo $row['product_id']; ?>">Edit</a> | 
            <a href="delete.php?id=<?php echo $row['product_id']; ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>