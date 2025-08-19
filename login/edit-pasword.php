<?php
session_start();
include_once '../inc/inc_fungsi.php';
include("../inc/koneksi.php");

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Jika belum login, redirect ke halaman login
    exit;
}

// Ambil informasi pengguna dari session
$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$sql_user = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($koneksi, $sql_user);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Jika terjadi kesalahan dalam query
    echo "Terjadi kesalahan: " . mysqli_error($koneksi);
}

mysqli_free_result($result);

// ============================
// Proses perubahan password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data inputan
    $old_password = $_POST['old_password'] ?? '';//ika $_POST['...'] ada dan tidak null, maka nilai dari $post yang akan digunakan.jika null=''
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi inputan
    if ($new_password !== $confirm_password) {
        $_SESSION['gagal'] = "Password baru dan konfirmasi password tidak cocok.";
    } elseif (strlen($new_password) < 3) {
        $_SESSION['gagal'] = "Password baru harus lebih dari 3 karakter.";
    } elseif (!password_verify($old_password, $user['password'])) {
        $_SESSION['gagal'] = "Password lama salah.";
    } else {
        // Hash password baru
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password di database (gunakan query langsung)
        $sql = "UPDATE users SET password = '$hashed_new_password' WHERE id = '$user_id'";
        $result = mysqli_query($koneksi, $sql);

        if ($result) {
            $_SESSION['sukses'] = "Password berhasil diperbarui.";
        } else {
            $_SESSION['gagal'] = "Terjadi kesalahan saat memperbarui password.";
        }
    }

    // Redirect ke halaman profil setelah perubahan
    header("Location: profil.php");
    exit;
}




// ===================tombol adminnnnn=======
$_SESSION['role'] = $user['role'];  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="style_profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- <header>
</header> -->

<?php if (isset($_SESSION['sukses'])): ?>
    <div class="alert alert-success">
        <h1><?php echo $_SESSION['sukses']; ?></h1>
    </div>
    <?php unset($_SESSION['sukses']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['gagal'])): ?>
    <div class="alert alert-danger">
        <h1><?php echo $_SESSION['gagal']; ?></h1>
    </div>
    <?php unset($_SESSION['gagal']); ?>
<?php endif; ?>

<section id="profile" class="content-section">
<h2>Change Password</h2>
<a href="javascript:history.back()" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>


<section class="edit-profile">
    <h2>Ganti Password</h2>
    <form action="" method="POST">
        <label for="old_password">Password Lama:</label>
        <input type="password" name="old_password" required>

        <label for="new_password">Password Baru:</label>
        <input type="password" name="new_password" required>

        <label for="confirm_password">Konfirmasi Password Baru:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit"><i class="fas fa-save"></i> Perbarui Password</button>
    </form>

</section>

</section>

</body>
</html>