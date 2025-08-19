<?php
session_start();
include("inc/koneksi.php");

$eror = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password

    //============== Cek apakah username sudah ada=================
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        $eror = "Username sudah terdaftar!";
    } else {
        // =============Simpan pengguna baru ke database====================
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if (mysqli_query($koneksi, $sql)) {
            header("Location: login.php"); // Redirect ke halaman login setelah pendaftaran berhasil
            exit;
        } else {
            $eror = "Terjadi kesalahan saat mendaftar!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran</title>
</head>
<body>
    <h1>Pendaftaran</h1>
    <?php if ($eror): ?>
        <div style="color: red;"><?php echo $eror; ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="email" name="email" placeholder="email" required>
        <input type="submit" name="register" value="Daftar">
    </form>
</body>
</html>