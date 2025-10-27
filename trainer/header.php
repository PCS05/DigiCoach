<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Trainer';
?>
<header class="main-header">
  <div class="logo">
    <i class="fa-solid fa-chalkboard-teacher"></i>
    DigiCoach Trainer Portal
  </div>
  <div class="user-info">
    <i class="fa-solid fa-user-circle"></i>
    <?php echo $name; ?>
  </div>
</header>
