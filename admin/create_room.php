<?php
session_start();
include '../db/connect.php';

$trainer_query = mysqli_query($conn, "SELECT id, name FROM trainer");
$student_query = mysqli_query($conn, "SELECT id, name FROM student");

if (isset($_POST['create_room'])) {
    $course = $_POST['language'];
    $trainer_id = $_POST['trainer'];

    // Get student IDs from dropdowns
    $students = [
        $_POST['student1'] ?? NULL,
        $_POST['student2'] ?? NULL,
        $_POST['student3'] ?? NULL,
        $_POST['student4'] ?? NULL,
        $_POST['student5'] ?? NULL
    ];

    // Filter out empty values
    $selected_students = array_filter($students);

    // ✅ Check if at least 2 students selected
    if (count($selected_students) < 2) {
        $error = "Please select at least two students.";
    } else {
        // Assign student IDs to variables for query (keep them NULL if not selected)
        $student1 = $selected_students[0] ?? NULL;
        $student2 = $selected_students[1] ?? NULL;
        $student3 = $selected_students[2] ?? NULL;
        $student4 = $selected_students[3] ?? NULL;
        $student5 = $selected_students[4] ?? NULL;

        $insert_query = "
            INSERT INTO training_rooms 
            (course_name, trainer_id, student1_id, student2_id, student3_id, student4_id, student5_id)
            VALUES (
                '$course',
                '$trainer_id',
                " . ($student1 ? "'$student1'" : "NULL") . ",
                " . ($student2 ? "'$student2'" : "NULL") . ",
                " . ($student3 ? "'$student3'" : "NULL") . ",
                " . ($student4 ? "'$student4'" : "NULL") . ",
                " . ($student5 ? "'$student5'" : "NULL") . "
            )
        ";

        if (mysqli_query($conn, $insert_query)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Error creating room: " . mysqli_error($conn);
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Training Room | DigiCoach</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
form { max-width: 600px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: 600; }
select, input { width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
button { padding: 10px 20px; background: #0d3b47; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #09525e; }
.success { color: green; margin-bottom: 15px; }
.error { color: red; margin-bottom: 15px; }
</style>
</head>
<body>
<?php include 'header.php'; ?>

<main class="container">
  <h2>Create New Training Room</h2>

  <?php
    if (!empty($error)) echo "<p class='error'>$error</p>";
    if (!empty($success)) echo "<p class='success'>$success</p>";
  ?>

 <form method="POST">
    <div class="form-group">
        <label>Technology / Course:</label>
        <select name="language" required>
            <option value="">Select Course</option>
            <option value="HTML">HTML</option>
            <option value="CSS">CSS</option>
            <option value="JavaScript">JavaScript</option>
            <option value="React">React</option>
            <option value="Angular">Angular</option>
        </select>
    </div>

    <div class="form-group">
        <label>Trainer:</label>
        <select name="trainer" required>
            <option value="">Select Trainer</option>
            <?php while($trainer = mysqli_fetch_assoc($trainer_query)): ?>
            <option value="<?= $trainer['id'] ?>"><?= htmlspecialchars($trainer['name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

  <div class="form-group">
  <label>Student 1:</label>
  <select name="student1">
    <option value="">Select Student 1</option>
    <?php
      mysqli_data_seek($student_query, 0);
      while($student = mysqli_fetch_assoc($student_query)) {
          echo "<option value='{$student['id']}'>{$student['name']}</option>";
      }
    ?>
  </select>
</div>


<div class="form-group">
  <label>Student 2:</label>
  <select name="student2">
    <option value="">Select Student 2</option>
    <?php
      mysqli_data_seek($student_query, 0);
      while($student = mysqli_fetch_assoc($student_query)) {
          echo "<option value='{$student['id']}'>{$student['name']}</option>";
      }
    ?>
  </select>
</div>

<div class="form-group">
  <label>Student 3:</label>
  <select name="student3">
    <option value="">Select Student 3</option>
    <?php
      mysqli_data_seek($student_query, 0);
      while($student = mysqli_fetch_assoc($student_query)) {
          echo "<option value='{$student['id']}'>{$student['name']}</option>";
      }
    ?>
  </select>
</div>

<div class="form-group">
  <label>Student 4:</label>
  <select name="student4">
    <option value="">Select Student 4</option>
    <?php
      mysqli_data_seek($student_query, 0);
      while($student = mysqli_fetch_assoc($student_query)) {
          echo "<option value='{$student['id']}'>{$student['name']}</option>";
      }
    ?>
  </select>
</div>

<div class="form-group">
  <label>Student 5:</label>
  <select name="student5">
    <option value="">Select Student 5</option>
    <?php
      mysqli_data_seek($student_query, 0);
      while($student = mysqli_fetch_assoc($student_query)) {
          echo "<option value='{$student['id']}'>{$student['name']}</option>";
      }
    ?>
  </select>
</div>


    <button type="submit" name="create_room">Create Room</button>
</form>

</main>
</body>
</html>
