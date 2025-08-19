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
//berfungsi untuk membebaskan atau menghapus data hasil query dari memory setelah selesai digunakan. 
// mysqli_free_result($result);


// Proses upload foto profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {
    $target_dir = "../profilFoto/";  // Direktori upload
    $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);  // Path file yang diupload
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));  // Cek ekstensi file

    // ==============================Cek apakah file adalah gambar=======================
    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['gagal'] = "File yang diupload bukan gambar.";
        $uploadOk = 0;
    }

    // =================Cek ukuran file==============
    if ($_FILES["profile_photo"]["size"] > 5000000) {  //=================== Maksimal ukuran file 5MB======================
        $_SESSION['gagal'] = "Maaf, file Anda terlalu besar.";
        $uploadOk = 0;
    }

    // ===================Cek format file======================
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $_SESSION['gagal'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // ==================Jika semua cek lolos, upload file=========================
    if ($uploadOk == 1) {
        // ========Hapus foto lama jika ada===============
        if ($user['profile_photo'] && file_exists($user['profile_photo'])) {
            unlink($user['profile_photo']);
        }

        // =============================Upload foto baru===================================
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            //=========================== Update path foto profil di database menggunakan query langsung=========================
            $sql_update = "UPDATE users SET profile_photo = '$target_file' WHERE id = '$user_id'";
            if ($koneksi->query($sql_update)) {
                $_SESSION['sukses'] = "Foto profil berhasil diupload.";
            } else {
                $_SESSION['gagal'] = "Maaf, terjadi kesalahan saat mengupdate foto profil di database.";
            }
        } else {
            $_SESSION['gagal'] = "Maaf, terjadi kesalahan saat mengupload file.";
        }
        header("Location: profil.php"); // Redirect ke halaman profil setelah upload
        exit;
    }
}


// ==================Proses perubahan username dan email======================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['email'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    
    // Validasi username
    if (empty($new_username)) {
        $_SESSION['gagal'] = "Username tidak boleh kosong.";
    } elseif (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['gagal'] = "Email tidak valid.";
    } else {
        // Menyiapkan query tanpa prepared statements
        $sql_update_user = "UPDATE users SET username = '$new_username', email = '$new_email' WHERE id = '$user_id'";
        
        // Menjalankan query langsung
        if ($koneksi->query($sql_update_user)) {
            $_SESSION['sukses'] = "Data profil berhasil diperbarui.";
        } else {
            $_SESSION['gagal'] = "Terjadi kesalahan saat memperbarui profil.";
        }

        header("Location: profil.php"); // Redirect ke halaman profil setelah perubahan
        exit;
    }
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
<a href="javascript:history.back()" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>

    <section class="edit-profile">
    <h2>Ganti Username dan Email</h2>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <button type="submit"><i class="fas fa-save"></i> Perbarui Profil</button>
    </form>




</section>


    <section class="upload-photo">
        <h2>Upload Foto Profil</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_photo" accept="image/*" required>
            <button type="submit"><i class="fas fa-upload"></i> Upload Foto</button>
        </form>
    </section>
</section>

</body>
</html>