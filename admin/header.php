<!-- <?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']); 
?> -->
<header>
    <div class="logo">
        <img src="../assets/images/digi_logo.png" alt="DigiCoach Logo">
    </div>
    <nav>
        <a href="dashboard.php" class="<?php if($current_page == 'dashboard.php'){ echo 'active'; } ?>">Dashboard</a>
        <a href="create_room.php" class="<?php if($current_page == 'create_room.php'){ echo 'active'; } ?>">Create Room</a>
        <a href="manage_fees.php" class="<?php if($current_page == 'manage_fees.php'){ echo 'active'; } ?>">Manage Fees</a>
        <a href="view_attendance.php" class="<?php if($current_page == 'view_attendance.php'){ echo 'active'; } ?>">Attendance</a>
        <a href="register.php" class="<?php if($current_page == 'register.php'){ echo 'active'; } ?>">Register</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>
