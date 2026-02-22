<?php
session_start();
include 'config.php'; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM products WHERE status IN ('active', 'archived') ORDER BY product_id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | The Rise & Bake</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
</head>
<body class="dashboard-page">

    <nav class="nav-bar">
        <div class="brand-logo">🥐 The Rise and Bake</div>
        <div class="nav-right">
            <button class="cart-btn" onclick="toggleCart()">
                🛒 Cart <span class="cart-count" id="cart-count">0</span>
            </button>
            <a href="#" class="logout-link" onclick="confirmLogout()">Sign Out</a>
        </div>
    </nav>

    <!-- CART SIDEBAR -->
    <div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
    <div class="cart-sidebar" id="cart-sidebar">
        <div class="cart-header">
            <h2>Your Cart 🛒</h2>
            <button class="cart-close" onclick="toggleCart()">✕</button>
        </div>
        <div class="cart-items" id="cart-items">
            <p class="cart-empty">Your cart is empty.</p>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                Total: <span id="cart-total">₱0.00</span>
            </div>
            <button class="btn-checkout">Checkout</button>
        </div>
    </div>

    <div class="dashboard-wrapper">
        <div class="welcome-section">
            <h1>Our Menu</h1>
            <p>Fresh from the oven, just for you 🍞</p>
        </div>

        <div class="pastry-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card <?php echo $row['status'] === 'archived' ? 'card-unavailable' : ''; ?>">
                        <?php if (!empty($row['image_path'])): ?>
                            <img src="../<?php echo $row['image_path']; ?>" 
                                 alt="<?php echo htmlspecialchars($row['product_name']); ?>" 
                                 class="card-img">
                        <?php else: ?>
                            <div class="card-img-placeholder">🥐</div>
                        <?php endif; ?>

                        <?php if ($row['status'] === 'archived'): ?>
                            <div class="unavailable-badge">⚠️ Unavailable</div>
                        <?php endif; ?>

                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                            <p class="card-desc"><?php echo htmlspecialchars($row['product_description'] ?? ''); ?></p>
                            <span class="price">₱<?php echo number_format($row['product_price'], 2); ?></span>

                            <?php if ($row['status'] === 'archived'): ?>
                                <p class="out-of-stock">Currently out of stock</p>
                                <button class="btn-order btn-unavailable" disabled>Out of Stock</button>
                            <?php else: ?>
                                <p class="card-stock">Stock: <?php echo $row['product_quantity']; ?></p>
                                <button class="btn-order" onclick="addToCart(
                                    <?php echo $row['product_id']; ?>,
                                    '<?php echo addslashes($row['product_name']); ?>',
                                    <?php echo $row['product_price']; ?>,
                                    '<?php echo !empty($row['image_path']) ? '../' . addslashes($row['image_path']) : ''; ?>'
                                )">Add to Cart</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>The display is empty. Add items in the Admin Panel!</p>
            <?php endif; ?>
        </div>
    </div>

<script>
    let cart = {};

    function toggleCart() {
        const sidebar = document.getElementById('cart-sidebar');
        const overlay = document.getElementById('cart-overlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }

    function addToCart(id, name, price, image) {
        if (cart[id]) {
            cart[id].qty += 1;
        } else {
            cart[id] = { name, price, image, qty: 1 };
        }
        renderCart();
        const sidebar = document.getElementById('cart-sidebar');
        const overlay = document.getElementById('cart-overlay');
        sidebar.classList.add('open');
        overlay.classList.add('open');
    }

    function removeFromCart(id) {
        delete cart[id];
        renderCart();
    }

    function changeQty(id, delta) {
        cart[id].qty += delta;
        if (cart[id].qty <= 0) delete cart[id];
        renderCart();
    }

    function renderCart() {
        const cartItems = document.getElementById('cart-items');
        const cartCount = document.getElementById('cart-count');
        const cartTotal = document.getElementById('cart-total');

        const keys = Object.keys(cart);

        if (keys.length === 0) {
            cartItems.innerHTML = '<p class="cart-empty">Your cart is empty.</p>';
            cartCount.textContent = '0';
            cartTotal.textContent = '₱0.00';
            return;
        }

        let total = 0;
        let totalQty = 0;
        let html = '';

        keys.forEach(id => {
            const item = cart[id];
            const subtotal = item.price * item.qty;
            total += subtotal;
            totalQty += item.qty;

            html += `
                <div class="cart-item">
                    ${item.image ? `<img src="${item.image}" class="cart-item-img">` : '<div class="cart-item-img-placeholder">🥐</div>'}
                    <div class="cart-item-info">
                        <p class="cart-item-name">${item.name}</p>
                        <p class="cart-item-price">₱${(item.price).toFixed(2)}</p>
                        <div class="cart-qty-controls">
                            <button onclick="changeQty(${id}, -1)">−</button>
                            <span>${item.qty}</span>
                            <button onclick="changeQty(${id}, 1)">+</button>
                        </div>
                    </div>
                    <button class="cart-item-remove" onclick="removeFromCart(${id})">✕</button>
                </div>
            `;
        });

        cartItems.innerHTML = html;
        cartCount.textContent = totalQty;
        cartTotal.textContent = '₱' + total.toFixed(2);
    }

    function confirmLogout() {
        if (confirm("Are you sure you want to sign out?")) {
            window.location.href = "logout.php";
        }
    }
</script>

</body>
</html>