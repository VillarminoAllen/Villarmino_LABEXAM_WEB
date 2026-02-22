<?php
include 'db.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($pass !== $confirm_pass) {
        $message = "<p class='error-msg'>Passwords do not match!</p>";
    } else {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO admins (username, password) VALUES ('$user', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $message = "<p class='success-banner'>Admin registered! <a href='login.php'>Login here</a></p>";
        } else {
            if ($conn->errno == 1062) {
                $message = "<p class='error-msg'>Username already exists.</p>";
            } else {
                $message = "<p class='error-msg'>Error: " . $conn->error . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join the Bakery | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="entry-page">

    <div class="glass-card">
        <h2 class="brand-title">Join the Bakery</h2>
        <p class="brand-subtitle">Create an admin account to manage the shop.</p>
        
        <?php echo $message; ?>

        <form method="POST" class="bakery-form">
    <div class="input-group">
        <input type="text" name="username" id="username" required placeholder=" ">
        <label for="username">Username</label>
    </div>
    <div class="input-group">
        <input type="password" name="password" id="password" required placeholder=" ">
        <label for="password">Password</label>
    </div>
    <div class="input-group">
        <input type="password" name="confirm_password" id="confirm_password" required placeholder=" ">
        <label for="confirm_password">Confirm Password</label>
    </div>
    <button type="submit" class="btn-submit">Create Account</button>
</form>

        <div class="form-footer">
            <p>Already a member? <a href="login.php">Login here</a></p>
        </div>
    </div>

</body>
</html>