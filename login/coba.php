<?php
session_start();
include_once '../inc/inc_fungsi.php';
include("../inc/koneksi.php");

// Ambil data pengguna dari database untuk semua pengguna
$sql_user = "SELECT * FROM users"; // Menyusun query untuk mengambil semua pengguna

// Menjalankan query
$result = mysqli_query($koneksi, $sql_user);

// Mengecek apakah query berhasil dijalankan dan ada hasilnya
if ($result) {
    // Jika ada hasil, maka kita bisa loop melalui data pengguna
} else {
    // Jika query gagal, tampilkan pesan error
    echo "Terjadi kesalahan saat mengambil data pengguna";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style_profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

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
    <div class="menu">
        <a href="javascript:history.back()" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>
        <i class="fa-solid fa-ellipsis-vertical menu" id="menu-icon"></i>

        <nav id="navbar">
            <a href="edit-username.php" class="active"><i class="bi bi-plus-square"></i> Edit</a>
            <a href="edit-pasword.php"><i class="bi bi-journal-richtext"></i> Advance</a>
        </nav>

    </div>

    <!-- Menampilkan data seluruh pengguna -->
    <?php while ($user = mysqli_fetch_assoc($result)) { ?>
        <div class="profile-card">
            <a href="ganti-pp.php">
                <div class="profile-photo">
                    <img src="<?php echo $user['profile_photo'] ? $user['profile_photo'] : 'https://via.placeholder.com/150'; ?>" alt="Profile Photo">
                </div>
            </a>
            <div class="profile-info">
                <p class="username">Selamat Datang, <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <a href="../cek.php"><p>My Blog</p></a>
                <?php
                if ($user['role'] == 'admin') {
                    echo  '<a href="../admin/halaman.php">admin😹</a>';
                } else {
                    echo '';
                }
                ?>
            </div>
        </div>
    <?php } ?>

    <div class="logout">
        <a  href="../logout.php" onclick="return confirm('apakah anda yakin ingin logout dari akun ini?')"><i class="fas fa-sign-out-alt"></i> LogOut</a>
    </div>

    <?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_post = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda belum login.";
    exit;
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Query ke database untuk mengambil data user
$sql_user = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($koneksi, $sql_user);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Gagal mengambil data user.";
    exit;
}

// Query untuk mengambil komentar
$sql = "SELECT users.username, komen.komen, users.role, users.profile_photo
        FROM komen
        JOIN users ON komen.id_user = users.id
        WHERE komen.id_post = '$id_post'
        ORDER BY komen.id DESC";
$query = mysqli_query($koneksi, $sql);

// Proses tambah komentar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['komen']) && isset($_POST['id_post'])) {
    $id_post = mysqli_real_escape_string($koneksi, $_POST['id_post']);
    $komen = trim($_POST['komen']); // Hapus spasi di awal dan akhir
    $komen = htmlspecialchars($komen, ENT_QUOTES, 'UTF-8'); // Hindari XSS
    $id_user = $_SESSION['user_id'];

    // Cek jika komentar tidak kosong
    if (!empty($komen)) {
        $sql_insert = "INSERT INTO komen (id_user, id_post, komen) VALUES ('$id_user', '$id_post', '$komen')";
        if (mysqli_query($koneksi, $sql_insert)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_post);
            exit;
        } else {
            echo "<p class='no-comment'>Gagal mengirim komentar.</p>";
        }
    } else {
        echo "<p class='no-comment'>Komentar tidak boleh kosong.</p>";
    }
}

?>

<div class="comment-container">
    <form method="POST" class="comment-form">
        <input type="hidden" name="id_post" value="<?= htmlspecialchars($id_post) ?>">
        <input type="text" placeholder="Masukkan komentar" class="komen" name="komen">
        <button class="tbl-kirim" type="submit">Kirim</button>
    </form>

    <div class="comment-section">
        <?php if (mysqli_num_rows($query) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <div class="comment-item">
                    <div class="comment-avatar">
                        <!-- Foto Profil -->
                        <img src="<?= htmlspecialchars($row['profile_photo'] ? $row['profile_photo'] : 'https://via.placeholder.com/150') ?>" alt="Profile Photo">
                    </div>
                    <div class="comment-content">
                        <span class="username">
                            <?= htmlspecialchars($row['username']) ?>
                            <?php if ($row['role'] === 'admin') { ?>
                                <span class="role-admin">(admin)</span>
                            <?php } ?>
                        </span>
                        <p class="comment-text"><?= htmlspecialchars($row['komen']) ?></p>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="no-comment">Belum ada komentar.</p>
        <?php } ?>
    </div>
</div>

</section>

<script>
    const menuIcon = document.getElementById('menu-icon');
    const navbar = document.getElementById('navbar');

    menuIcon.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });

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
</body>
</html>
