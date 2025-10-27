<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure trainer is logged in
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'trainer') {
//     header("Location: ../login.php");
//     exit();
// }

$trainer_id = $_SESSION['user_id'];

// 🔹 Fetch all student IDs under this trainer (same logic as assign_task)
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

// 🔹 Fetch students only under this trainer
$students = [];
if (!empty($student_ids)) {
    $ids_string = implode(',', $student_ids);
    $students_query = mysqli_query($conn, "SELECT id, name FROM student WHERE id IN ($ids_string)");
    while ($s = mysqli_fetch_assoc($students_query)) {
        $students[] = $s;
    }
}

// 🔹 Handle attendance submission
if (isset($_POST['attendance'])) {
    foreach ($_POST['status'] as $student_id => $status) {
        mysqli_query($conn, "
            INSERT INTO attendance (student_id, trainer_id, status, date)
            VALUES ('$student_id', '$trainer_id', '$status', NOW())
        ");
    }
    $msg = "✅ Attendance saved successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mark Attendance | DigiCoach</title>
<link rel="stylesheet" href="../assets/css/trainer.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.content { padding: 30px; }
.table { background: white; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.table th { background: #004d40; color: white; text-align: center; }
.table td { text-align: center; vertical-align: middle; }
button { background-color: #00796b; color: white; padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; }
button:hover { background-color: #004d40; }
.text-success { color: green; font-weight: bold; }
</style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content">
<h3>Mark Attendance</h3>
<?php if(isset($msg)) echo "<p class='text-success'>$msg</p>"; ?>

<?php if (!empty($students)) { ?>
<form method="POST">
<table class="table">
<thead><tr><th>Student Name</th><th>Status</th></tr></thead>
<tbody>
<?php foreach ($students as $s): ?>
<tr>
  <td><?= htmlspecialchars($s['name']); ?></td>
  <td>
    <select name="status[<?= $s['id']; ?>]">
      <option value="Present">Present</option>
      <option value="Absent">Absent</option>
    </select>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<button type="submit" name="attendance">Save Attendance</button>
</form>
<?php } else { ?>
<p>No students found under this trainer.</p>
<?php } ?>
</div>
</body>
</html>
