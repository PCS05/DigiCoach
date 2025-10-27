<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$trainer_id = $_SESSION['user_id'];

// 🧮 Total Students
$student_ids = [];
$rooms_query = mysqli_query($conn, "
    SELECT student1_id, student2_id, student3_id, student4_id, student5_id 
    FROM training_rooms 
    WHERE trainer_id='$trainer_id'
");
while ($room = mysqli_fetch_assoc($rooms_query)) {
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($room["student{$i}_id"])) {
            $student_ids[] = $room["student{$i}_id"];
        }
    }
}
$student_ids = array_unique($student_ids);
$total_students = count($student_ids);

// 📝 Total Tasks
$total_tasks = 0;
$task_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tasks_assigned WHERE trainer_id='$trainer_id'");
$task_data = mysqli_fetch_assoc($task_query);
$total_tasks = $task_data['total'] ?? 0;

// 📊 Average Attendance
$avg_attendance = 0;
if (!empty($student_ids)) {
    $ids_str = implode(',', $student_ids);
    $attendance_query = mysqli_query($conn, "
        SELECT 
            SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) AS present_count,
            COUNT(*) AS total_count
        FROM attendance
        WHERE student_id IN ($ids_str)
    ");
    $attendance_data = mysqli_fetch_assoc($attendance_query);
    if ($attendance_data['total_count'] > 0) {
        $avg_attendance = round(($attendance_data['present_count'] / $attendance_data['total_count']) * 100, 2);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Trainer Dashboard | DigiCoach</title>
<link rel="stylesheet" href="../assets/css/trainer.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f5f6fa;
}

.content {
  padding: 40px;
}

h3 {
  font-size: 28px;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 35px;
  text-align: center;
  background: linear-gradient(90deg, #6a1b9a, #8e24aa);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: 0.5px;
}

.row {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
  justify-content: center;
}

.card {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  padding: 25px;
  flex: 1;
  min-width: 280px;
  max-width: 320px;
  text-align: center;
  transition: all 0.3s ease;
  border-top: 5px solid #6a1b9a;
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

.card i {
  font-size: 45px;
  color: #6a1b9a;
  margin-bottom: 15px;
}

.card h5 {
  margin: 10px 0;
  color: #333;
  font-weight: 600;
}

.card p {
  font-size: 18px;
  font-weight: 500;
  color: #555;
}

@media (max-width: 768px) {
  .row {
    flex-direction: column;
    align-items: center;
  }
}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content">
  <h3>Welcome, Trainer 👋</h3>

  <div class="row">
    <div class="card text-center">
      <i class="fa-solid fa-user-graduate"></i>
      <h5>Total Students</h5>
      <p><?= $total_students ?> Students</p>
    </div>

    <div class="card text-center">
      <i class="fa-solid fa-clipboard-list"></i>
      <h5>Total Tasks</h5>
      <p><?= $total_tasks ?> Assigned</p>
    </div>

    <div class="card text-center">
      <i class="fa-solid fa-chart-line"></i>
      <h5>Average Attendance</h5>
      <p><?= $avg_attendance ?>%</p>
    </div>
  </div>
</div>
</body>
</html>
