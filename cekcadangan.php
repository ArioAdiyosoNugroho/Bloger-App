

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY BLOG</title>
    

    <!-- Load jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <!-- Load Summernote -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    
    <!-- Load Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">

    <style>

    #isi{
    max-width: 100%;  /* Maksimalkan lebar sesuai ukuran kontainer */
    overflow: hidden; /* Sembunyikan bagian konten yang keluar */
    word-wrap: break-word; /* Pisahkan kata jika terlalu panjang */
    margin: 0;
    }

    </style>

</head>
<body>

<header>
    <h1>MY BLOG</h1>
</header>

<?php if (isset($_SESSION['sukses'])): ?>
    <div class="alert alert-success">
        <h1><?php echo $_SESSION['sukses']; ?></h1>
    </div>
    <?php unset($_SESSION['sukses']); ?> <!-- Hapus pesan setelah ditampilkan -->
<?php endif; ?>

<?php if (isset($_SESSION['gagal'])): ?>
    <div class="alert alert-danger">
        <h1><?php echo $_SESSION['gagal']; ?></h1>
    </div>
    <?php unset($_SESSION['gagal']); ?> <!-- Hapus pesan setelah ditampilkan -->
<?php endif; ?>


<section id="courses" class="content-section">
    <?php
    $sukses = $gagal = "";
    $katakunci = isset($_GET['katakunci']) ? $_GET['katakunci'] : "";


    $sukses = "";
    $gagal = "";
    
    if (isset($_GET['op']) && $_GET['op'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = mysqli_real_escape_string($koneksi, $id);
    
        $sql = "DELETE FROM halaman WHERE id = '$id'";
        $query = mysqli_query($koneksi, $sql);
    
        if ($query) {
            $_SESSION['sukses'] = "Blog berhasil dihapus";
        } else {
            $_SESSION['gagal'] = "Blog gagal dihapus";
        }
        
        // Menunggu beberapa detik sebelum redirect
        sleep(2); // Menunggu 2 detik sebelum mengalihkan
        header('Location: cek.php?katakunci=' . urlencode($katakunci) . '&page=' . (isset($_GET['page']) ? $_GET['page'] : 1));
        exit;
        
    }
    
    

    ?>

<h1>Blogger</h1>


<div class="container">
        <form action="" method="get" class="input-group">
            <input type="text" placeholder="Masukkan kata kunci" name="katakunci" value="<?php echo $katakunci ?>">
            <input type="submit" name="cari" value="Cari Tulisan">
        </form>
        
        <a href="admin/input.php" class="tbl-biru">Buat Halaman Baru</a>

        <!-- Loop through posts and display them -->
        <?php
        $sqltambahan = "";
        $per_halaman = 5;
        if ($katakunci != "") {
            $array_katakunci = explode(" ", $katakunci);
            foreach ($array_katakunci as $keyword) {
                $sqlcari[] = "(judul LIKE '%$keyword%' OR kutipan LIKE '%$keyword%' OR isi LIKE '%$keyword%')";
            }
            $sqltambahan = "WHERE " . implode(" OR ", $sqlcari);
        }

        $sql = "SELECT * FROM halaman $sqltambahan";
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
        $query = mysqli_query($koneksi, $sql);
        $total = mysqli_num_rows($query);
        $total_page = ceil($total / $per_halaman);
        $nomor = $mulai + 1;
        $sql .= " ORDER BY id DESC LIMIT $mulai, $per_halaman";
        $query = mysqli_query($koneksi, $sql);


        while ($r1 = mysqli_fetch_array($query)): ?>
            <div class="post-card">
            <h2 class="post-title"><?php echo $r1['judul']; ?></h2>
            <p class="post-quote"><?php echo $r1['kutipan']; ?></p>
            <div class="image-container">
            <!-- <img src="<//?php echo ambil_gambar($r1['id']) ?>" alt="Blog Image"/> -->
            </div>
            <!-- <p><//?php echo set_isi($r1['isi']) ?></p> -->
            <!-- <p class="post-content"><//?php echo max_kata(ambil_isi($r1['isi']),20) ?></p> -->
            <p id="isi" class="content"><?php echo max_kata(ambil_isi($r1['id']),20); ?></p>
            <p class="content">Tanggal isi: <?php echo ambil_tanggal($r1['id']); ?></p>  <!-- Menampilkan tanggal -->
            <a href="admin/input.php?id=<?php echo $r1['id'] ?>" class="tbl-pink">Edit</a>
            <a href="materi.php?id=<?php echo $r1['id'] ?>"  class="tbl-pink">read more</a>
            <a href="cek.php?op=delete&id=<?php echo $r1['id'] ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')" class="tbl-pink">Delete</a>
            
            </div>

        <?php endwhile; ?>

        

        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                    <li>
                        <a href="cek.php?katakunci=<?php echo $katakunci ?>&page=<?php echo $i ?>" class="page-link"><?php echo $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</section>

</body>
<script>
setTimeout(function() {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.style.opacity = 0;
        setTimeout(function() {
            alert.style.display = 'none';
        }, 500); // Waktu untuk menghapus elemen dari DOM
    }
}, 3000); // 3 detik setelah halaman dimuat
</script>
</html>



<div class="popular-articles">
    <h2>Artikel Populer</h2>
    <div class="isi-artikel">
    <?php 
    if (mysqli_num_rows($query_populer) > 0) {
        while ($row = mysqli_fetch_assoc($query_populer)) {
            echo '<div class="populer">';
            echo '<h3 class="populer-title">' . htmlspecialchars($row['judul']) . '</h3>';
            
            
            $sampul_gambar = $row['sampul_gambar'];

            if ($sampul_gambar != ''):
            ?>
                <img src="uploads/<?php echo $sampul_gambar; ?>" alt="Sampul">
            <?php endif;
            
            echo '<p class="populer-kutip">' . htmlspecialchars($row['kutipan']) . '</p>';
            echo '<a href="read.php?id=' . $row['id'] . '" class="tbl-populer">Read More</a>';
            echo '</div>';
        }
    } else {
        echo "<p>Artikel populer tidak tersedia.</p>";
    }
    ?>
    </div>
</div>