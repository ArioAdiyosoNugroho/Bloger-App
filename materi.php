<?php
// Kode koneksi
include_once("inc/koneksi.php");
include_once("inc/inc_fungsi.php");

$id = isset($_GET['id']) ? $_GET['id'] : 0; // Mengambil id dari URL

if ($id == 0) {
    echo "<div><p>ID tidak ditemukan</p></div>";
    exit(); 
}

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

    // Redirect kembali ke halaman yang sama setelah penghapusan
        // Redirect kembali ke halaman yang sama setelah penghapusan
        header('Location: cek.php?katakunci=' . urlencode($katakunci) . '&page=' . (isset($_GET['page']) ? $_GET['page'] : 1));
        exit;
    }
    // ========================= END FUNGSI DELETE =========================
    
    // Mengambil data halaman berdasarkan ID
    $sql = "SELECT * FROM halaman WHERE id = '$id'";
    $query = mysqli_query($koneksi, $sql);
    
    if (mysqli_num_rows($query) > 0) {
        $r1 = mysqli_fetch_array($query);
        $judul_halaman = $r1['judul'];
    } else {
        $judul_halaman = '';
    }

    // ====================fungsi view===============
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Isi Halaman</title>
        <link href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/materi.css">
        
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
            <p>View: <?php echo($r1['views']) ?></p>
            <div class="btn-fungsi">
                <a href="cek.php"><button class="btn-use">kembali</button></a>
                <a href="admin/input.php?id=<?php echo $r1['id'] ?>" class="btn-use edit">Edit</a>
                <a href="materi.php?op=delete&id=<?php echo $r1['id'] ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')" class="btn-use delete">Delete</a>
            </div>
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

    window.onload = function() {
        // Cari semua iframe di dalam elemen dengan kelas 'isi'
        const iframes = document.querySelectorAll('.isi iframe');
        
        // Menambahkan class CSS responsif pada iframe
        iframes.forEach(function(iframe) {
            iframe.classList.add('video-container');
        });
    }

    window.onload = function() {
    // Cari semua iframe di dalam elemen dengan kelas 'isi'
    const iframes = document.querySelectorAll('.isi iframe');
    
    // Menambahkan class CSS responsif pada iframe
    iframes.forEach(function(iframe) {
        iframe.classList.add('video-container');
    });
}


    </script>
    
    </html>