<?php
session_start();
include_once '../inc/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Ambil ID pengguna
$user_id = $_SESSION['user_id'];

// Hapus data pengguna dari database
$sql_delete = "DELETE FROM users WHERE id = ?";
$stmt = $koneksi->prepare($sql_delete);
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    // Hapus sesi dan logout pengguna
    session_destroy();
    header("Location: goodbye.php"); 
    exit;
} else {
    $_SESSION['gagal'] = "Terjadi kesalahan saat menghapus akun.";
    header("Location: profil.php"); 
    exit;
}
?>