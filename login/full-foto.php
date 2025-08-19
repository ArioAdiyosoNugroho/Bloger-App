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

// Ambil data pengguna dari database menggunakan query biasa
$sql_user = "SELECT * FROM users WHERE id = $user_id"; // Menyusun query dengan langsung menambahkan user_id ke dalam query

// Menjalankan query
$result = mysqli_query($koneksi, $sql_user);

// Mengecek apakah query berhasil dijalankan dan ada hasilnya
if ($result) {
    // Ambil data pengguna (hanya satu hasil karena id pasti unik)
    $user = mysqli_fetch_assoc($result);
    
    // Sekarang, data pengguna dapat diakses melalui array $user, seperti:
    // echo $user['username']; // Contoh mengakses nama pengguna
} else {
    // Jika query gagal, tampilkan pesan error
    echo "Terjadi kesalahan saat mengambil data pengguna";
}


// ===================tombol adminnnnn=======
$_SESSION['role'] = $user['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style_profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>

<!-- <header>
</header> -->

<section id="profile" class="content-section">
    <div class="menu">
        <a href="profil.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>
        <i class="fa-solid fa-ellipsis-vertical menu" id="menu-icon"></i>

    <nav id="navbar">
        <a href="edit-username.php" class="active"><i class="bi bi-plus-square"></i> Edit</a>
        <a href="edit-pasword.php"><i class="bi bi-journal-richtext"></i>Advance</a>
    </nav>

    </div>

    <div class="profile-card">
        <div class="profile-photo-full">
            <img src="<?php echo $user['profile_photo'] ? $user['profile_photo'] : 'https://via.placeholder.com/150'; ?>" alt="Profile Photo">
        </div>
    </div>
</section>
</body>

<script>
    const menuIcon = document.getElementById('menu-icon');
    const navbar = document.getElementById('navbar');

    menuIcon.addEventListener('click', () =>{
        navbar.classList.toggle('active');
    })


        // Fungsi untuk menghilangkan notifikasi
        function hideNotification() {
        var successAlert = document.querySelector('.alert-success');
        var errorAlert = document.querySelector('.alert-danger');
        
        // Jika ada notifikasi sukses
        if (successAlert) {
            successAlert.classList.add('hidden');
            setTimeout(function() {
                successAlert.style.display = 'none'; // Sembunyikan notifikasi setelah fade-out
            }, 1000); // Tunggu 1 detik untuk animasi selesai
        }

        // Jika ada notifikasi gagal
        if (errorAlert) {
            errorAlert.classList.add('hidden');
            setTimeout(function() {
                errorAlert.style.display = 'none'; // Sembunyikan notifikasi setelah fade-out
            }, 1000); // Tunggu 1 detik untuk animasi selesai
        }
    }

    // Menunggu halaman selesai dimuat
    window.onload = function() {
        setTimeout(hideNotification, 2000); // Tunda selama 2 detik sebelum menghilangkan notifikasi
    };
</script>
</html>