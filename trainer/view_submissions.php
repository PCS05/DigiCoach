<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']);

// Handle status update
if (isset($_POST['update_status'])) {
    $submission_id = $_POST['submission_id'];
    $new_status = $_POST['status'];
    mysqli_query($conn, "UPDATE task_submissions SET status='$new_status' WHERE id='$submission_id'");
}

$trainer_id = $_SESSION['user_id'];

$submissions = mysqli_query($conn, "
    SELECT ts.*, s.name AS student_name
    FROM task_submissions ts
    JOIN student s ON ts.student_id = s.id
    WHERE ts.student_id IN (
        SELECT student1_id FROM training_rooms WHERE trainer_id = '$trainer_id'
        UNION
        SELECT student2_id FROM training_rooms WHERE trainer_id = '$trainer_id'
        UNION
        SELECT student3_id FROM training_rooms WHERE trainer_id = '$trainer_id'
        UNION
        SELECT student4_id FROM training_rooms WHERE trainer_id = '$trainer_id'
        UNION
        SELECT student5_id FROM training_rooms WHERE trainer_id = '$trainer_id'
    )
    ORDER BY ts.id DESC
");


// Debug if query fails
if (!$submissions) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Submissions | DigiCoach</title>
<link rel="stylesheet" href="../assets/css/trainer.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.status-pending {
    color: orange;
    font-weight: bold;
}
.status-completed {
    color: green;
    font-weight: bold;
}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content">
<h3>Recent Student Submissions</h3>

<table class="table table-bordered bg-white mt-3">
<thead class="thead-dark">
<tr>
  <th>Student</th>
  <th>Task Title</th>
  <th>File</th>
  <th>Current Status</th>
  <th>Action</th>
</tr>
</thead>
<tbody>
<?php if (mysqli_num_rows($submissions) > 0) { ?>
  <?php while($sub = mysqli_fetch_assoc($submissions)) { ?>
  <tr>
    <td><?php echo htmlspecialchars($sub['student_name']); ?></td>
    <td><?php echo htmlspecialchars($sub['task_title']); ?></td>
    <td>
      <?php if(!empty($sub['file_path'])): ?>
        <a href="<?php echo htmlspecialchars($sub['file_path']); ?>" target="_blank">View</a>
      <?php else: ?>
        No file
      <?php endif; ?>
    </td>
    <td>
      <span class="<?php echo $sub['status'] == 'Completed' ? 'status-completed' : 'status-pending'; ?>">
        <?php echo htmlspecialchars($sub['status']); ?>
      </span>
    </td>
    <td>
      <form method="POST" style="display:inline-flex;">
        <input type="hidden" name="submission_id" value="<?php echo $sub['id']; ?>">
        <select name="status" class="form-control form-control-sm mr-2">
          <option value="Pending" <?php if($sub['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
          <option value="Completed" <?php if($sub['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
        </select>
        <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
      </form>
    </td>
  </tr>
  <?php } ?>
<?php } else { ?>
  <tr><td colspan="5" class="text-center">No submissions found.</td></tr>
<?php } ?>
</tbody>
</table>
</div>
</body>
</html>
