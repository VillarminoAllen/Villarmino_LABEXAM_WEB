<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password. Try again!";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <title>Login | The Golden Whisk</title>
</head>
<body class="auth-bg">
    <div class="auth-wrapper">
        <div class="glass-card">
            <h2>Welcome Back</h2>
            <p>The oven is warm! Please sign in.</p>

            <?php if(isset($error)): ?>
                <p style="color: #e74c3c; font-size: 0.9rem; margin-bottom: 10px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'account_created'): ?>
                <p style="color: #2ecc71; font-size: 0.9rem; margin-bottom: 10px;">Account created! Please login.</p>
            <?php endif; ?>

            <form method="POST" action="">
    <div class="input-group">
        <input type="email" name="email" id="email" required placeholder=" ">
        <label for="email">Email Address</label>
    </div>
    <div class="input-group">
        <input type="password" name="password" id="password" required placeholder=" ">
        <label for="password">Password</label>
    </div>
    <button type="submit" name="login">Enter the Bakery</button>
</form>
            
            <p style="margin-top:15px;">New here? <a href="signup.php" style="color: #d4a373; font-weight: bold;">Join the club</a></p>
        </div>
    </div>
</body>
</html>