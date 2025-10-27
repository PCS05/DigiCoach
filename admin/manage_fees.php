<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']); 

// Fetch all students for dropdown
$students = mysqli_query($conn, "SELECT id, name FROM student");

// Handle form submission
if (isset($_POST['add_fee'])) {
    $student_id = $_POST['student_id'];
    $course_name = $_POST['course_name'];
    $payment_date = $_POST['payment_date'];
    $amount = $_POST['amount'];
 

    $query = "INSERT INTO fees (student_id, payment_date, amount,  course_name)
              VALUES ('$student_id', '$payment_date', '$amount', '$course_name')";

    if (mysqli_query($conn, $query)) {
        $success = "Fees added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Student Fees | DigiCoach</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { background: #f9f9f9; font-family: "Inter", sans-serif; }
    .container { max-width: 700px; margin: 40px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    h2 { color: #0d3b47; margin-bottom: 20px; text-align: center; }
    .form-group { margin-bottom: 15px; }
    label { font-weight: 600; display: block; margin-bottom: 6px; }
    input, select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
    button { background: #0d3b47; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
    button:hover { background: #09525e; }
    .success { color: green; text-align: center; margin-bottom: 10px; }
    .error { color: red; text-align: center; margin-bottom: 10px; }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
  <h2>Add Student Fees</h2>

  <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
  <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

  <form method="POST">
    <div class="form-group">
      <label>Select Student:</label>
      <select name="student_id" id="studentSelect" required>
        <option value="">Select Student</option>
        <?php while ($s = mysqli_fetch_assoc($students)): ?>
          <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Display student details dynamically -->
    <div id="studentDetails" style="display:none;">
      <div class="form-group">
        <label>Student Name:</label>
        <input type="text" id="studentEmail" readonly>
      </div>

      <div class="form-group">
        <label>Student Course:</label>
        <input type="text" id="studentCourse" readonly>
      </div>
    </div>

    <div class="form-group">
      <label>Course Name:</label>
      <input type="text" name="course_name" placeholder="Enter Course (e.g. React, HTML)" required>
    </div>

    <div class="form-group">
      <label>Date:</label>
      <input type="date" name="payment_date" required>
    </div>

    <div class="form-group">
      <label>Amount Paid (₹):</label>
      <input type="number" name="amount" placeholder="Enter Amount" required>
    </div>

    <button type="submit" name="add_fee">Save Fee Record</button>
  </form>
</div>

<script>
document.getElementById('studentSelect').addEventListener('change', function() {
  var studentId = this.value;
  if (studentId) {
    fetch('get_student_details.php?id=' + studentId)
      .then(response => response.json())
      .then(data => {
        document.getElementById('studentDetails').style.display = 'block';
        document.getElementById('studentEmail').value = data.email;
        document.getElementById('studentCourse').value = data.course;
      });
  } else {
    document.getElementById('studentDetails').style.display = 'none';
  }
});
</script>

</body>
</html>
