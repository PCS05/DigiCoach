<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// $name = $_SESSION['name'] ?? 'Student';
$name = isset($_SESSION['name']) && $_SESSION['name'] !== '' ? htmlspecialchars($_SESSION['name']) : 'Student';

?>
<nav class="navbar navbar-expand-lg d-flex justify-content-between">
  <a class="navbar-brand" href="#">🎓 DigiCoach Student Portal</a>
  <div class="user-info">
    <i class="fa-solid fa-user-circle"></i>
      <?php echo $name; ?>
  </div>
</nav>
