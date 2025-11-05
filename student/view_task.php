<?php
session_start();
include '../db/connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

// Fetch tasks
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

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

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

</body>
</html>
