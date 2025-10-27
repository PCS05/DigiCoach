<?php
session_start();
include 'db/connect.php';

$error = ''; // Initialize error

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $user = null;
    $role = '';

    // Check Admin table
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        $role = 'admin';
    }

    // Check Trainer table if not found
    if(!$user){
        $res = mysqli_query($conn, "SELECT * FROM trainer WHERE email='$email' AND password='$password'");
        if(mysqli_num_rows($res) > 0){
            $user = mysqli_fetch_assoc($res);
            $role = 'trainer';
        }
    }

    // Check Student table if not found
    if(!$user){
        $res = mysqli_query($conn, "SELECT * FROM student WHERE email='$email' AND password='$password'");
        if(mysqli_num_rows($res) > 0){
            $user = mysqli_fetch_assoc($res);
            $role = 'student';
        }
    }

    if($user){
        // Set PHP sessions
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $user['name'];

        // Set localStorage and redirect via JS
        $name = addslashes($user['name']);
        echo "<script>
            localStorage.setItem('user_id', '{$user['id']}');
            localStorage.setItem('role', '{$role}');
            localStorage.setItem('name', '{$name}');";

        if($role == 'admin'){
            echo "window.location.href='admin/dashboard.php';";
        } elseif($role == 'trainer'){
            echo "window.location.href='trainer/dashboard.php';";
        } else{
            echo "window.location.href='student/dashboard.php';";
        }

        echo "</script>";
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DigiCoach Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="navbar">
    <div class="logo">
      <img src="assets/images/digi_logo.png" alt="DigiCoach Logo">
    </div>
    <h3>A Smart Digital Coaching Platform</h3>
  </header>

  <div class="login-box">
    <h2>Welcome to DigiCoach</h2>
    <p class="subtitle">Login to continue</p>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
      </div>

      <button type="submit" name="login">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>

  <footer>
    <p>© 2025 DigiCoach | All Rights Reserved</p>
  </footer>
</body>
</html>
