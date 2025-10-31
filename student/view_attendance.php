<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']); 

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php"); 
    exit();
}
$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

$attendance_query = mysqli_query($conn, "SELECT * FROM attendance WHERE student_id='$student_id' ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Attendance | DigiCoach</title>
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
    <h4>Attendance Record</h4>
    <table class="table table-bordered bg-white text-dark mt-3">
<thead>
<tr><th>Date</th><th>Status</th></tr>
</thead>
<tbody>
<?php while($a = mysqli_fetch_assoc($attendance_query)): ?>
<tr>
<td><?= $a['date'] ?></td>
<td><?= $a['status'] == 'Present' ? '<span class="text-success">Present</span>' : '<span class="text-danger">Absent</span>' ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

  </div>
</body>
</html>
