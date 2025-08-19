<?php
// Kode koneksi
include_once("inc/koneksi.php");
include_once("inc/inc_fungsi.php");
ob_start();

$id = isset($_GET['id']) ? $_GET['id'] : 0; // Mengambil id dari URL

if ($id == 0) {
    echo "<div><p>ID tidak ditemukan</p></div>";
    exit(); 
}

// ======login cek=======
session_start(); // Mulai sesi untuk cek login
$logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
// ========================= FUNGSI DELETE =========================
$sukses = $gagal = "";
$katakunci = isset($_GET['katakunci']) ? $_GET['katakunci'] : "";

if (isset($_GET['op']) && $_GET['op'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($koneksi, $id);

    // Debugging: Tampilkan ID yang akan dihapus
    echo "Menghapus ID: " . $id;

    $sql = "DELETE FROM halaman WHERE id = '$id'";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        $sukses = "Blog berhasil dihapus"; 
    } else {
        $gagal = "Blog gagal dihapus: " . mysqli_error($koneksi); // Tampilkan error jika ada
    }

    header('Location: cek.php?katakunci=' . urlencode($katakunci) . '&page=' . (isset($_GET['page']) ? $_GET['page'] : 1));
    exit;
}
// ========================= END FUNGSI DELETE =========================

// ===========Mengambil data halaman berdasarkan ID==============
$sql = "SELECT * FROM halaman WHERE id = '$id'";
$query = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($query) > 0) {
    $r1 = mysqli_fetch_array($query);//mengambil hasil query berupa array
    $judul_halaman = $r1['judul'];
} else {
    $judul_halaman = '';
}

// ====================fungsi view===============
if ($logged_in) {
    $query_check_id = "SELECT * FROM halaman WHERE id = '$id'";
    $result_check = mysqli_query($koneksi, $query_check_id);

    if (mysqli_num_rows($result_check) > 0) {
        $query_update_views = "UPDATE halaman SET views = views + 1 WHERE id = '$id'";
        mysqli_query($koneksi, $query_update_views);
    }
}
?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Isi Halaman</title>
        <link href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/materi.css">
        <link rel="stylesheet" href="css/komentarin.css">
    </head>
    
    <header>
    <?php include_once("inc_header.php") ?>
    </header>
    
    <body>
    
        
        <!-- Tampilkan pesan sukses atau gagal -->
        <?php if ($sukses): ?>
            <div class="alert alert-success">
                <h1><?php echo $sukses; ?></h1>
            </div>
            <?php endif; ?>
            
            <?php if ($gagal): ?>
                <div class="alert alert-danger">
                    <h1><?php echo $gagal; ?></h1>
                </div>
    <?php endif; ?>
    
    <?php
    
    if ($judul_halaman == '') {
        echo "<div><p>Judul halaman tidak ada</p></div>";
    } else {
        ?>
        <div class="keseluruhan">
        <div class="btn-fungsi">
                <a href="index.php"><button class="btn-use">kembali</button></a>
            </div>
            <button class="theme-switcher" onclick="toggleTheme()">
                 <i id="theme-icon" class="bx bx-sun"></i>
                 Switch Theme
            </button>
            <h4 class="desc">judul:</h4>
            <h1 class="judul"><?php echo $r1['judul']; ?></h1>
            <h4 class="desc">kutipan:</h4>
            <p class="deskripsi"><?php echo $r1['kutipan']; ?></p>
            <h3 class="desc">Kategori:</h3>
            <p class="content"><?php echo !empty($r1['tema']) ? htmlspecialchars($r1['tema']) : 'Tidak ada Kategori'; ?></p>
            <div class="isi">
                <p><?php echo set_isi($r1['isi']); ?></p>
            </div>
            <p>view: <?php echo($r1['views'])?></p>
            <div class="btn-fungsi">
                <a href="index.php"><button class="btn-use">kembali</button></a>
            </div>
            <?php
            include 'komen/inc_like.php';
            include 'komen/komen.php';
            ?>
        </div>
        <?php
    }
    ?>

    </body>
    
    <script>
    function toggleTheme() {
        const body = document.body;
        const currentTheme = body.classList.contains('dark') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
        body.classList.remove(currentTheme);
        body.classList.add(newTheme);
    
        const icon = document.getElementById('theme-icon');
        if (newTheme === 'dark') {
            icon.classList.remove('bx-sun');
            icon.classList.add('bx-moon');
        } else {
            icon.classList.remove('bx-moon');
            icon.classList.add('bx-sun');
        }
    
        localStorage.setItem('theme', newTheme);
    }
    </script>
    
    </html>