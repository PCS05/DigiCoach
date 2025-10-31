<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']); 

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

// Fetch fee records for this student
$fees_query = mysqli_query($conn, "SELECT * FROM fees WHERE student_id = '$student_id'");
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
    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="content">
    <h4>Your Fee Details</h4>
    <p class="text-light">Check your payment history below.</p>

    <table class="table table-bordered bg-white text-dark mt-3">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>Payment Date</th>
          <th>Amount</th>
          <th>Course Name</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if (mysqli_num_rows($fees_query) > 0) {
            $i = 1;
            while($f = mysqli_fetch_assoc($fees_query)) { ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= htmlspecialchars($f['payment_date']); ?></td>
                  <td><?= number_format($f['amount'], 2); ?></td>
                  <td><?= htmlspecialchars($f['course_name']); ?></td>
                </tr>
        <?php } 
        } else { ?>
            <tr>
              <td colspan="4" class="text-center text-danger">No fee records found.</td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
</div>
</body>
</html>
