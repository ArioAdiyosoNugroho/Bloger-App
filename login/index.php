<?php
session_start();
include("../inc/koneksi.php"); // Pastikan koneksi database sudah benar

$eror = ""; // Variabel untuk menyimpan pesan error

// =================================== LOGIN =============================
if (isset($_POST['login'])) {//mengecek suatu variabel apakah sudah di devinisikan
    // Mengambil username dan password dari form
    $username = trim($_POST['username']);//hapus sepasi samping
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    // Cek apakah pengguna ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        //semisal username itu ada tapi pasword tidak ada maka flase dan seterusnya
        $user = mysqli_fetch_assoc($result);//ambil satu baris kolom dalam bentuk array....jadi kayak nama=doni
        // =================Verifikasi password==================
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Simpan ID pengguna di session
            $_SESSION['username'] = $user['username']; // Simpan username di session
            header("Location: ../index.php"); // Redirect ke halaman cek.php setelah login
            exit;
        } else {
            $eror = "Username atau password salah!"; // Jika password salah
        }
    } else {
        $eror = "Username atau password salah!"; // Jika username tidak ditemukan
    }
}

// =============================== REGISTER ============================
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = $_POST['email'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password

    // Cek apakah username sudah ada
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        $eror = "Username sudah terdaftar!";
    } else {
        // Simpan pengguna baru ke database
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
        if (mysqli_query($koneksi, $sql)) {
            header("Location: index.php"); // Redirect ke halaman login setelah pendaftaran berhasil
            exit;
        } else {
            $eror = "Terjadi kesalahan saat mendaftar!";
        }
    }
}
?>

<!-- ========================================= -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Modern Login Page | DevyMae</title>
</head>

<body>

<!-- ==================register====================== -->
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="" method="post">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>

                <!-- Tampilkan error jika ada -->
                <?php if ($eror): ?>
                    <div style="color: red;"><?php echo $eror; ?></div>
                <?php endif; ?>

                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit" name="register" value="Daftar">Sign Up</button>
            </form>
        </div>

        <!-- ===================login======================= -->
        <div class="form-container sign-in">
            <form action="" method="post">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>

                <!-- Tampilkan error jika ada -->
                <?php if ($eror): ?>
                    <div style="color: red;"><?php echo $eror; ?></div>
                <?php endif; ?>

                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <!-- <p><a href="forgot_password.php">forgot password?</a></p> -->
                <button type="submit" name="login" value="login">Sign In</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>
