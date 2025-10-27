
<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']); 

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php"); // Redirect to login
    exit();
}
$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

$fees_query = mysqli_query($conn, "SELECT * FROM fees WHERE student_id='$student_id'");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Fees | DigiCoach</title>
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
     <a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <h4>Your Fee Details</h4>
    <p class="text-light">Check your payment history and remaining balance below.</p>

   <table class="table table-bordered bg-white text-dark mt-3">
<thead><tr>
<th>Receipt No.</th><th>Date</th><th>Amount Paid</th><th>Payment Mode</th><th>Status</th>
</tr></thead>
<tbody>
<?php while($f = mysqli_fetch_assoc($fees_query)): ?>
<tr>
<td><?= htmlspecialchars($f['receipt_no']) ?></td>
<td><?= $f['date'] ?></td>
<td><?= $f['amount'] ?></td>
<td><?= htmlspecialchars($f['payment_mode']) ?></td>
<td><?= $f['status'] == 'Paid' ? '<span class="text-success">Paid</span>' : '<span class="text-danger">Pending</span>' ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

  </div>
</body>
</html>
