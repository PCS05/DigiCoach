<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
  <a href="dashboard.php" class="<?php if($current_page == 'dashboard.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-house"></i> Dashboard
  </a>
  <a href="view_task.php" class="<?php if($current_page == 'view_task.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-clipboard-list"></i> View Tasks
  </a>
  <a href="submit_task.php" class="<?php if($current_page == 'submit_task.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-upload"></i> Submit Task
  </a>
  <a href="view_attendance.php" class="<?php if($current_page == 'view_attendance.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-calendar-check"></i> Attendance
  </a>
  <a href="view_fees.php" class="<?php if($current_page == 'view_fees.php'){ echo 'active'; } ?>">
    <i class="fa-solid fa-money-bill"></i> Fees
  </a>
  <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>
