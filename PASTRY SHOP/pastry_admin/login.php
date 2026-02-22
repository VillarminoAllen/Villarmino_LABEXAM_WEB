<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM admins WHERE username='$user'");
    if ($row = $res->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        } else { $error = "Invalid password."; }
    } else { $error = "Admin not found."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | The Rise and Bake</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="entry-page">

    <div class="glass-card">
        <h2 class="form-title">Admin Login</h2>
        <p class="form-subtitle">Enter your credentials to access the dashboard.</p>
        
        <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

        <form method="POST" class="bakery-form">
    <div class="input-group">
        <input type="text" name="username" id="username" required placeholder=" ">
        <label for="username">Username</label>
    </div>
    <div class="input-group">
        <input type="password" name="password" id="password" required placeholder=" ">
        <label for="password">Password</label>
    </div>
    <button type="submit" class="btn-submit">Login to Shop</button>
</form>

        <div class="form-footer">
            <p>Not an admin yet? <a href="register.php">Create account</a></p>
            <p style="margin-top: 10px;"><a href="index.php" class="back-link">← Back to Portal</a></p>
        </div>
    </div>

</body>
</html>