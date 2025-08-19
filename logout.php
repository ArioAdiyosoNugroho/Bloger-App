<?php
// Logout.php atau script logout
session_start();
session_unset();  // Menghapus semua variabel session
session_destroy(); // Menghancurkan session
header("Location: index.php"); // Mengarahkan ke halaman utama setelah logout
exit();

?>
