<?php
session_start();
include '../db/connect.php';
$current_page = basename($_SERVER['PHP_SELF']);

// 🔹 Fetch attendance with student + course + trainer info
$attendance_query = "
    SELECT 
        s.name AS student_name, 
        COALESCE(r.course_name, 'N/A') AS course_name, 
        a.date, 
        a.status,
        t.name AS trainer_name
    FROM attendance a
    JOIN student s ON a.student_id = s.id
    LEFT JOIN rooms r ON a.room_id = r.id
    LEFT JOIN trainer t ON a.trainer_id = t.id
    ORDER BY a.date DESC, s.name ASC
";

$result = mysqli_query($conn, $attendance_query);
if (!$result) {
    die('Error fetching attendance: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DigiCoach | Admin Attendance</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <style>
    body { background: #f7f8fa; font-family: Arial, sans-serif; }
    .container {
      max-width: 1100px;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #0d3b47;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #0d3b47;
      color: #fff;
      text-transform: uppercase;
    }
    tr:nth-child(even) { background-color: #f2f2f2; }
    tr:hover { background-color: #e9f7f6; }
    .status-present { color: green; font-weight: bold; }
    .status-absent { color: red; font-weight: bold; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
  <h2>Attendance Overview</h2>
  <table>
    <thead>
      <tr>
        <th>Trainer Name</th>
        <th>Student Name</th>
        <th>Course</th>
        <th>Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['trainer_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['course_name']) ?></td>
            <td><?= date("d-m-Y h:i A", strtotime($row['date'])) ?></td>
            <td class="<?= strtolower($row['status']) === 'present' ? 'status-present' : 'status-absent' ?>">
              <?= htmlspecialchars($row['status']) ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5">No attendance records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
