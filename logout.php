
<?php
session_start();
session_unset();
session_destroy();

// Clear browser localStorage via JS
echo "<script>
    localStorage.clear();
    window.location.href = '/digicoach/index.php';
</script>";
exit();
?>
