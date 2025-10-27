<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']); 



$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

$success = $error = "";

// ✅ Handle form submission
if(isset($_POST['task_title'])){
    $title = mysqli_real_escape_string($conn, $_POST['task_title']);
    $file_name = $_FILES['file_upload']['name'];
    $tmp_name = $_FILES['file_upload']['tmp_name'];

    $upload_dir = "../uploads/";
    if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $dest = $upload_dir . time() . "_" . basename($file_name);

    if(move_uploaded_file($tmp_name, $dest)){
        $insert = mysqli_query($conn, "
            INSERT INTO task_submissions (student_id, task_title, file_path, submitted_at, status)
            VALUES ('$student_id', '$title', '$dest', NOW(), 'Submitted')
        ");
        if($insert){
            $success = "✅ Task submitted successfully!";
        } else {
            $error = "❌ Database error: " . mysqli_error($conn);
        }
    } else {
        $error = "❌ File upload failed!";
    }
}

// ✅ Fetch this student's submitted tasks
$submitted_tasks = mysqli_query($conn, "
    SELECT ts.*, s.name AS student_name
    FROM task_submissions ts
    LEFT JOIN student s ON ts.student_id = s.id
    WHERE ts.student_id = '$student_id'
    ORDER BY ts.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Task | DigiCoach</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
 <nav class="navbar navbar-expand-lg d-flex justify-content-between">
    <a class="navbar-brand" href="#">🎓 DigiCoach Student Portal</a>
    <div class="user-info">
      <i class="fa-solid fa-user-circle"></i>
      <?php echo htmlspecialchars($student_name); ?>
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

  <div class="content">
    <h4>Upload Your Task</h4>

    <?php if(!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST" enctype="multipart/form-data" class="mt-4">
      <div class="form-group">
        <label>Task Title</label>
        <input type="text" class="form-control" name="task_title" required>
      </div>
      <div class="form-group mt-3">
        <label>Upload File</label>
        <input type="file" class="form-control" name="file_upload" required>
      </div>
      <button class="btn btn-warning mt-3 text-dark fw-bold">Submit Task</button>
    </form>

    <hr class="my-4">

    <h4>Your Submitted Tasks</h4>
    <table class="table table-bordered bg-white text-dark mt-3">
      <thead>
        <tr>
          <th>Student Name</th>
          <th>Task Title</th>
          <th>File</th>
          <th>Submitted At</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if(mysqli_num_rows($submitted_tasks) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($submitted_tasks)): ?>
            <tr>
              <td><?= htmlspecialchars($row['student_name']); ?></td>
              <td><?= htmlspecialchars($row['task_title']); ?></td>
              <td><a href="<?= htmlspecialchars($row['file_path']); ?>" target="_blank">View File</a></td>
              <td><?= htmlspecialchars($row['submitted_at']); ?></td>
              <td><?= htmlspecialchars($row['status']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No tasks submitted yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
