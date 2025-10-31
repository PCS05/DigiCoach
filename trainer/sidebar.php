<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <a href="dashboard.php" class="<?php if($current_page == 'dashboard.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-house"></i> Dashboard
  </a>
  <a href="assign_task.php" class="<?php if($current_page == 'assign_task.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-tasks"></i> Assign Task
  </a>
  <a href="mark_attendance.php" class="<?php if($current_page == 'mark_attendance.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-calendar-check"></i> Attendance
  </a>
  <a href="view_submissions.php" class="<?php if($current_page == 'view_submissions.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-folder-open"></i> Submissions
  </a>
  <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</aside>
