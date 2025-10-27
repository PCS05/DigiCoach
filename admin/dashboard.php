<?php
session_start();
include '../db/connect.php';

$current_page = basename($_SERVER['PHP_SELF']); 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch totals
$student_query = mysqli_query($conn, "SELECT COUNT(*) as total_students FROM student");
$student_count = mysqli_fetch_assoc($student_query)['total_students'];

$trainer_query = mysqli_query($conn, "SELECT COUNT(*) as total_trainers FROM trainer");
$trainer_count = mysqli_fetch_assoc($trainer_query)['total_trainers'];

$fees_query = mysqli_query($conn, "SELECT SUM(amount) as total_fees FROM fees");
$fees = mysqli_fetch_assoc($fees_query);
$total_fees = $fees['total_fees'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DigiCoach | Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f4f7fa; margin: 0; }
    .content { padding: 30px; }
    .row { display: flex; flex-wrap: wrap; margin: -10px; }
    .col-md-4 { flex: 1; min-width: 250px; padding: 10px; }
    .card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
    .card i { font-size: 40px; color: #0d3b47; margin-bottom: 10px; }
    .card h5 { margin: 10px 0 5px; font-weight: 600; color: #0d3b47; }
    .card p { margin: 0; font-size: 18px; color: #555; }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="content">
    <h3>Welcome, Admin 👋</h3>
    <p>Track your trainers, students, and fees easily.</p>

    <div class="row mt-4">
      <div class="col-md-4 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-user-graduate"></i>
          <h5>Total Students</h5>
          <p><?php echo $student_count; ?> Students</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-chalkboard-teacher"></i>
          <h5>Total Trainers</h5>
          <p><?php echo $trainer_count; ?> Trainers</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center">
          <i class="fa-solid fa-money-check-alt"></i>
          <h5>Total Fees</h5>
          <p>₹<?php echo number_format($total_fees, 2); ?></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
