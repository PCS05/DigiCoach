<?php
session_start();
include 'db/connect.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // trainer/student

    // Check if email already exists in trainer or student tables
    $emailExists = false;

    $tables = ['trainer', 'student'];
    foreach ($tables as $table) {
        $check = mysqli_query($conn, "SELECT * FROM $table WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $emailExists = true;
            break;
        }
    }

    if ($emailExists) {
        $error = "Email already registered!";
    } else {
        // Insert user into respective table based on role
        if ($role == 'trainer') {
            $query = "INSERT INTO trainer (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        } elseif ($role == 'student') {
            $query = "INSERT INTO student (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        } else {
            $error = "Invalid role selected!";
        }

        if (isset($query) && mysqli_query($conn, $query)) {
            header("Location: dashboard.php");
            exit();
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DigiCoach Register</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="login-box">
    <h2>Create Your Account</h2>
    <p class="subtitle">Register to get started</p>

    <?php
      if (!empty($error)) echo "<p class='error'>$error</p>";
    ?>

    <form method="POST">
      <div class="input-group">
        <label>Name</label>
        <input type="text" name="name" placeholder="Enter your name" required>
      </div>

      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
      </div>

      <div class="input-group">
        <label>Role</label>
        <select name="role" required>
          <option value="">Select Role</option>
          <option value="trainer">Trainer</option>
          <option value="student">Student</option>
        </select>
      </div>

      <button type="submit" name="register">Register</button>
    </form>
</div>
</body>
</html>
