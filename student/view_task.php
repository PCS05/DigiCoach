<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php"); // Redirect to login
    exit();
}

$student_id = $_SESSION['user_id']; // Logged-in student's ID
$student_name = $_SESSION['name'];

// Fetch all tasks assigned to this student
$query = "
    SELECT 
        t.task_title,
        t.task_desc,
        t.due_date,
        t.status,
        tr.name AS trainer_name
    FROM 
        tasks_assigned t
    LEFT JOIN trainer tr ON t.trainer_id = tr.id
    WHERE 
        t.student_id = '$student_id'
    ORDER BY t.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Tasks | DigiCoach</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg d-flex justify-content-between">
    <a class="navbar-brand" href="#">🎓 DigiCoach Student Portal</a>
    <div class="user-info">
      <i class="fa-solid fa-user-circle"></i>
      <?php echo htmlspecialchars($student_name); ?>
    </div>
  </nav>
  
  <!-- Sidebar -->
  <div class="sidebar">
    <a href="dashboard.php" class="<?php if($current_page == 'dashboard.php'){ echo 'active'; } ?>"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="view_task.php" class="<?php if($current_page == 'view_task.php'){ echo 'active'; } ?>"><i class="fa-solid fa-clipboard-list"></i> View Tasks</a>
    <a href="submit_task.php" class="<?php if($current_page == 'submit_task.php'){ echo 'active'; } ?>"><i class="fa-solid fa-upload"></i> Submit Task</a>
    <a href="view_attendance.php" class="<?php if($current_page == 'view_attendance.php'){ echo 'active'; } ?>"><i class="fa-solid fa-calendar-check"></i> Attendance</a>
    <a href="view_fees.php" class="<?php if($current_page == 'view_fees.php'){ echo 'active'; } ?>"><i class="fa-solid fa-money-bill"></i> Fees</a>
    <a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h4>Your Assigned Tasks</h4>

    <table class="table table-bordered bg-white text-dark mt-3">
      <thead>
        <tr>
          <th>Task Title</th>
          <th>Description</th>
          <th>Due Date</th>
          <th>Status</th>
          <th>Assigned By</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while($task = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($task['task_title']); ?></td>
              <td><?= htmlspecialchars($task['task_desc']); ?></td>
              <td><?= htmlspecialchars($task['due_date']); ?></td>
              <td><?= htmlspecialchars($task['status'] ?? 'Pending'); ?></td>
              <td><?= htmlspecialchars($task['trainer_name']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No tasks assigned yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script>
    // Optional check — verify localStorage values
    const role = localStorage.getItem('role');
    const userId = localStorage.getItem('user_id');

    if (role !== 'student') {
      window.location.href = '../index.php';
    }
  </script>
</body>
</html>
