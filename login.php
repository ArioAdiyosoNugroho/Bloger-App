<?php
session_start();
include("inc/koneksi.php"); // Pastikan koneksi database sudah benar

$eror = ""; // Variabel untuk menyimpan pesan error

if (isset($_POST['login'])) {
    //=============== Mengambil username dan password dari form==============
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // ====================Query untuk mencari pengguna berdasarkan username====================
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    // =====================Cek apakah pengguna ditemukan=================
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        //==== Verifikasi password============
        if (password_verify($password, $user['password'])) {
            // ============Set session======================
            $_SESSION['user_id'] = $user['id']; // Simpan ID pengguna di session
            $_SESSION['username'] = $user['username']; // Simpan username di session
            header("Location: cek.php"); // Redirect ke halaman cek.php setelah login
            exit;
        } else {
            $eror = "Username atau password salah!"; // Jika password salah
        }
    } else {
        $eror = "Username atau password salah!"; // Jika username tidak ditemukan
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($eror): ?>
            <div class="error"><?php echo $eror; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>

        <a href="register.php"><p>belum punya akun?</p></a>
    </div>
</body>
</html>