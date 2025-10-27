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

// 🔹 Fetch all student IDs under this trainer
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

$students = [];
if (!empty($student_ids)) {
    $ids_string = implode(',', $student_ids);
    $students_query = mysqli_query($conn, "SELECT id, name FROM student WHERE id IN ($ids_string)");
    while ($s = mysqli_fetch_assoc($students_query)) {
        $students[$s['id']] = $s['name'];
    }
}

// 🔹 Handle task assignment
if (isset($_POST['task_title'], $_POST['task_desc'], $_POST['due_date'], $_POST['assign_to'])) {
    $title = mysqli_real_escape_string($conn, $_POST['task_title']);
    $desc = mysqli_real_escape_string($conn, $_POST['task_desc']);
    $due = $_POST['due_date'];
    $assign_to = $_POST['assign_to'];

    if ($assign_to === 'all') {
        foreach ($students as $student_id => $student_name) {
            mysqli_query($conn, "
                INSERT INTO tasks_assigned (task_title, task_desc, due_date, trainer_id, student_id, status)
                VALUES ('$title', '$desc', '$due', '$trainer_id', '$student_id', 'Pending')
            ");
        }
    } else {
        mysqli_query($conn, "
            INSERT INTO tasks_assigned (task_title, task_desc, due_date, trainer_id, student_id, status)
            VALUES ('$title', '$desc', '$due', '$trainer_id', '$assign_to', 'Pending')
        ");
    }
    $success = "✅ Task assigned successfully!";
}

// 🔹 Fetch assigned tasks
$tasks_query = "
    SELECT t.*, s.name AS student_name
    FROM tasks_assigned t
    LEFT JOIN student s ON t.student_id = s.id
    WHERE t.trainer_id = '$trainer_id'
    ORDER BY t.id DESC
";
$tasks = mysqli_query($conn, $tasks_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assign Task | DigiCoach</title>
<link rel="stylesheet" href="../assets/css/trainer.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.content {
  padding: 30px;
}
form {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
  margin-bottom: 25px;
}
form input, form textarea, form select {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}
form button {
  background-color: #00796b;
  color: white;
  padding: 8px 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
form button:hover {
  background-color: #004d40;
}
.table {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.table th {
  background: #004d40;
  color: white;
  text-align: center;
}
.table td {
  text-align: center;
  vertical-align: middle;
}
.text-success {
  color: green;
  font-weight: bold;
}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content">
  <h3>Assign New Task</h3>
  <?php if (!empty($success)) echo "<p class='text-success'>$success</p>"; ?>

  <form method="POST">
    <input type="text" name="task_title" placeholder="Task Title" required>
    <textarea name="task_desc" placeholder="Description" required></textarea>
    <input type="date" name="due_date" required>
    <select name="assign_to" required>
      <option value="all">All Students</option>
      <?php foreach($students as $id => $name): ?>
        <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Assign Task</button>
  </form>

  <h4>Previously Assigned Tasks</h4>
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Due Date</th>
        <th>Assigned To</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($tasks) > 0): ?>
        <?php while($t = mysqli_fetch_assoc($tasks)): ?>
        <tr>
          <td><?= htmlspecialchars($t['task_title']); ?></td>
          <td><?= htmlspecialchars($t['task_desc']); ?></td>
          <td><?= htmlspecialchars($t['due_date']); ?></td>
          <td><?= htmlspecialchars($t['student_name']); ?></td>
          <td><?= htmlspecialchars($t['status']); ?></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5">No tasks assigned yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
