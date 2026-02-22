<?php
session_start();
if (isset($_SESSION['admin'])) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Entry | The Rise and Bake</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="entry-page">

    <div class="hero-wrapper">
        <div class="glass-card">
            <h1 class="brand-title">The Rise and Bake</h1>
            <p class="brand-subtitle">Handcrafted, golden-brown perfection delivered from our oven to your doorstep.</p>
            
            <div class="button-group">
                <a href="login.php" class="btn btn-solid">Admin Login</a>
                <a href="register.php" class="btn btn-outline">Create Account</a>
            </div>
        </div>
    </div>

</body>
</html>