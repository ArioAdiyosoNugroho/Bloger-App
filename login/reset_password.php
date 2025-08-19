<?php
session_start();
include("../inc/koneksi.php");

$eror = ""; // Variabel untuk pesan error
$success = ""; // Pesan sukses

// Cek apakah token ada di URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek apakah token valid
    $sql = "SELECT * FROM users WHERE reset_token = '$token'";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Proses untuk mengubah password baru
        if (isset($_POST['reset_password'])) {
            $new_password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password == $confirm_password) {
                // Hash password baru
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password di database dan reset token
                $sql = "UPDATE users SET password = '$hashed_password', reset_token = NULL WHERE reset_token = '$token'";
                if (mysqli_query($koneksi, $sql)) {
                    $success = "Password berhasil diperbarui. Silakan login.";
                } else {
                    $eror = "Terjadi kesalahan saat memperbarui password.";
                }
            } else {
                $eror = "Password dan konfirmasi password tidak cocok!";
            }
        }
    } else {
        $eror = "Token reset password tidak valid!";
    }
} else {
    $eror = "Token reset password tidak ditemukan!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>

    <?php if ($eror): ?>
        <div style="color: red;"><?php echo $eror; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green;"><?php echo $success; ?></div>
    <?php else: ?>
        <form action="" method="post">
            <input type="password" name="password" placeholder="Password Baru" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    <?php endif; ?>
</body>
</html>
