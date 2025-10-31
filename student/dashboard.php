<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$current_page = basename($_SERVER['PHP_SELF']); 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard | DigiCoach</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg d-flex justify-content-between">
    <a class="navbar-brand" href="#">🎓 DigiCoach Student Portal</a>
    <div class="user-info">
      <i class="fa-solid fa-user-circle"></i>
      <?php echo htmlspecialchars($_SESSION['name']); ?>
    </div>
  </nav>

  <div class="sidebar">
    <a href="dashboard.php" class="<?php if($current_page == 'dashboard.php'){ echo 'active'; } ?>"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="view_task.php" class="<?php if($current_page == 'view_task.php'){ echo 'active'; } ?>"><i class="fa-solid fa-clipboard-list"></i> View Tasks</a>
    <a href="submit_task.php" class="<?php if($current_page == 'submit_task.php'){ echo 'active'; } ?>"><i class="fa-solid fa-upload"></i> Submit Task</a>
    <a href="view_attendance.php" class="<?php if($current_page == 'view_attendance.php'){ echo 'active'; } ?>"><i class="fa-solid fa-calendar-check"></i> Attendance</a>
    <a href="view_fees.php" class="<?php if($current_page == 'view_fees.php'){ echo 'active'; } ?>"><i class="fa-solid fa-money-bill"></i> Fees</a>
    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>
 
  <div class="content">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h3>
    <p>Track your tasks, check attendance, and manage your fees easily.</p>

    <div class="row mt-4">
      <div class="col-md-4 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-clipboard-check"></i>
          <h5>Tasks Submitted</h5>
          <p>12 Completed</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-calendar-day"></i>
          <h5>Attendance</h5>
          <p>92% Present</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-money-check-alt"></i>
          <h5>Fees Status</h5>
          <p>Paid: ₹12,000 / ₹15,000</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
