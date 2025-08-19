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
$sql_user = "SELECT * FROM users WHERE id = '$user_id'";
// Menjalankan query ke database
$result = mysqli_query($koneksi, $sql_user);
// Memeriksa apakah query berhasil dan mengambil data
if ($result) {
    $user = mysqli_fetch_assoc($result);//ambil satu baris query
} else {
    echo "Terjadi kesalahan dalam mengambil data.";
}
// basename memanggilnama lengjkap file

// Proses upload foto profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {//memeriksa apakah form menggunakan post dan file tidak null
    $target_dir = "../profilFoto/";  // Direktori upload
    $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);  // Path file yang diupload akan mengirim file ke target dan dengan prflft sebagai file dan name dsebagai fungsi array
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));  // ambil ekstensi file(pathinfo) saja dari target

    // Cek apakah file adalah gambar
    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if ($check === false) {//membandingkan type data secara keseluruhan
        $_SESSION['gagal'] = "File yang diupload bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["profile_photo"]["size"] > 5000000) {  // Maksimal ukuran file 5MB
        $_SESSION['gagal'] = "Maaf, file Anda terlalu besar.";
        $uploadOk = 0;
    }

    // Cek format file
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $_SESSION['gagal'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Jika semua cek lolos, upload file
    if ($uploadOk == 1) {
        // Hapus foto lama jika ada fileexis=menegecek file berada di tempatnya
        if ($user['profile_photo'] && file_exists($user['profile_photo'])) {//megecek file ada dan berada id tempatnya
            unlink($user['profile_photo']);//hapuskan dulu le😭
        }

        // Upload foto baru
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {//memindahkan file di penyimpanan sementara ke terget
            // Update path foto profil di database menggunakan query langsung
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
    <div class="alert alert-success" id="sukses-alert">
        <h1><?php echo $_SESSION['sukses']; ?></h1>
    </div>
    <?php unset($_SESSION['sukses']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['gagal'])): ?>
    <div class="alert alert-danger" id="gagal-alert">
        <h1><?php echo $_SESSION['gagal']; ?></h1>
    </div>
    <?php unset($_SESSION['gagal']); ?>
<?php endif; ?>




<section id="profile" class="content-section">
<h2>Profile Image</h2>
<a href="javascript:history.back()" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>

    <div class="profile-card">
        <a href="full-foto.php">
        <div class="profile-photo">
            <img src="<?php echo $user['profile_photo'] ? $user['profile_photo'] : 'https://via.placeholder.com/150'; ?>" alt="Profile Photo">
        </div>
        </a>
        </div>
    </div>

    <section class="upload-photo">
        <h2>Upload Foto Profil</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_photo" accept="image/*" required>
            <button type="submit"><i class="fas fa-upload"></i> Upload Foto</button>
        </form>
    </section>
</section>

</body>
<script>
    setTimeout(function() {
        var suksesAlert = document.getElementById("sukses-alert");
        if (suksesAlert) {
            suksesAlert.style.display = "none"; 
        }

        var gagalAlert = document.getElementById("gagal-alert");
        if (gagalAlert) {
            gagalAlert.style.display = "none"; 
        }
    }, 5000);
</script>
</html>