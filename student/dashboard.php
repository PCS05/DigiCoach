<?php
session_start();
include '../db/connect.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$current_page = basename($_SERVER['PHP_SELF']); 

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

// -------------------- Fetch Data --------------------

// ✅ 1. Count completed tasks
$task_query = mysqli_query($conn, "
    SELECT COUNT(*) AS total_completed 
    FROM task_submissions 
    WHERE student_id = '$student_id' AND status = 'Submitted'
");
$task_data = mysqli_fetch_assoc($task_query);
$total_tasks = $task_data['total_completed'] ?? 0;

// ✅ 2. Attendance percentage
$attendance_query = mysqli_query($conn, "
    SELECT 
        COUNT(*) AS total_days,
        SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) AS present_days
    FROM attendance
    WHERE student_id = '$student_id'
");
$attendance_data = mysqli_fetch_assoc($attendance_query);
$total_days = $attendance_data['total_days'] ?? 0;
$present_days = $attendance_data['present_days'] ?? 0;
$attendance_percentage = $total_days > 0 ? round(($present_days / $total_days) * 100, 1) : 0;
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

  <!-- Include header -->
  <?php include 'header.php'; ?>

  <!-- Include sidebar -->
  <?php include 'sidebar.php'; ?>
 
  <div class="content">
    <h3>Welcome, <?php echo htmlspecialchars($student_name); ?> 👋</h3>
    <p>Track your tasks and check your attendance easily.</p>

    <div class="row mt-4">
      <!-- Tasks Card -->
      <div class="col-md-6 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-clipboard-check"></i>
          <h5>Tasks Submitted</h5>
          <p><b><?php echo $total_tasks; ?></b> Completed</p>
          <a href="submit_task.php" class="btn btn-outline-primary btn-sm">View Tasks</a>
        </div>
      </div>

      <!-- Attendance Card -->
      <div class="col-md-6 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-calendar-day"></i>
          <h5>Attendance</h5>
          <p><b><?php echo $attendance_percentage; ?>%</b> Present</p>
          <a href="view_attendance.php" class="btn btn-outline-success btn-sm">View Attendance</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
