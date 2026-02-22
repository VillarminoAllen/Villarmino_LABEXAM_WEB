<?php
session_start(); 
include 'config.php';

if (isset($_POST['signup'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $checkEmail = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($checkEmail) > 0) {
        $error = "Email already registered!";
    } else {
        $query = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')";
        
        if (mysqli_query($conn, $query)) {
            $new_user_id = mysqli_insert_id($conn);
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['fullname'] = $fullname;
            header("Location: dashboard.php");
            exit(); 
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <title>Join the Bakery</title>
</head>
<body class="auth-bg">
    <div class="auth-wrapper">
        <div class="glass-card">
            <h2>Join the Bakery</h2>
            <p>Create an account to start ordering.</p>
            
            <?php if(isset($error)): ?>
                <p style="color: red; font-size: 0.8rem;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
    <input type="text" name="fullname" id="fullname" required placeholder=" ">
    <label for="fullname">Full Name</label>
</div>
<div class="input-group">
    <input type="email" name="email" id="email" required placeholder=" ">
    <label for="email">Email Address</label>
</div>
<div class="input-group">
    <input type="password" name="password" id="password" required placeholder=" ">
    <label for="password">Password</label>
</div>
            </form>
            <p style="margin-top:15px;">Already a member? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>