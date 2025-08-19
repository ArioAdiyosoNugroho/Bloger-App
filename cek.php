<?php
session_start();  // Pastikan session_start() ada di sini
include_once("inc_header.php");
include_once 'inc/inc_fungsi.php';
include("inc/koneksi.php");

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login/"); // Jika belum login, redirect ke halaman login ygy
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = (int)$_GET['id'];
    $user_id = (int)$user_id;

    $sql_delete_comments = "DELETE FROM komen WHERE id_post = $id AND id_user = $user_id";
    $query_comments = $koneksi->query($sql_delete_comments);

    $sql_delete_halaman = "DELETE FROM halaman WHERE id = $id AND user_id = $user_id";
    $query_halaman = $koneksi->query($sql_delete_halaman);
    if ($query_comments && $query_halaman) {
        $_SESSION['sukses'] = "Halaman berhasil dihapus.";
    } else {
        $_SESSION['gagal'] = "Gagal menghapus halaman awokawokawok KASIAN😹.";
    }

    header("Location: cek.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Cek</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<section id="courses" class="content-section">

    <div class="container">
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


        <div class=".search-bar-container ">
        <form action="" method="get" class="search-bar ">
            <input type="text" placeholder="Masukkan kata kunci" name="katakunci" value="<?php echo isset($_GET['katakunci']) ? htmlspecialchars($_GET['katakunci']) : ''; ?>">
            <input type="submit" name="cari" value="Cari Tulisan">
        </form>
        </div>
        <a href="admin/input.php" class="btn-plus">Buat Halaman Baru</a>

        <!-- Loop through posts and display them -->
        <?php
        $katakunci = isset($_GET['katakunci']) ? $_GET['katakunci'] : "";
        // if (isset($_GET['kata_kunci'])){
        //     $katakunci = $_GET['kata_kunci'];
        // }else{
        //     $katakunci = "";
        // }
        $sqltambahan = "";
        $per_halaman = 5;

        if ($katakunci != "") {
            $array_katakunci = explode(" ", $katakunci);//memisahkan kata kunci menjadi array berdasarkan spasi
            foreach ($array_katakunci as $keyword) {//dari setiap array di katakunci akan di ubah namanya menjadi $keywors
                $sqlcari[] = "(judul LIKE '%$keyword%' OR kutipan LIKE '%$keyword%' OR isi LIKE '%$keyword%')";//[]menampung looping
                // $sqlcari=[
                //     "(judul LIKE '%$keyword%' OR kutipan LIKE '%$keyword%' OR isi LIKE '%$keyword%')",
                //     "(judul LIKE '%$keyword%' OR kutipan LIKE '%$keyword%' OR isi LIKE '%$keyword%')"
                // ];

            }
            //"(judul LIKE '%PHP%') OR (kutipan LIKE '%PHP%') OR (isi LIKE '%PHP%')"
            $sqltambahan = "AND (" . implode(" OR ", $sqlcari) . ")";//menggabungkan sql cari tadimenjadi......AND (judul LIKE '%PHP%' OR kutipan LIKE '%PHP%' OR isi LIKE '%PHP%' OR judul LIKE '%MySQL%' OR kutipan LIKE '%MySQL%' OR isi LIKE '%MySQL%')
        }else 

        if (isset($_GET['action']) && $_GET['action'] == 'publish' && isset($_GET['id'])) {
            // Cek apakah ID valid
            $id = (int)$_GET['id'];  // Mengambil ID dan memastikan tipe data integer
            $user_id = $_SESSION['user_id'];  // ID pengguna yang login
            //mengubah publish jika bernilai 1 maka akan di ubah menjadi 0 dan jika selain 1 maka akan di ubah menjadi 0
            $sql_publish = "UPDATE halaman SET publish = IF(publish = 1, 0, 1) WHERE id = $id AND user_id = $user_id";

            if (mysqli_query($koneksi, $sql_publish)) {
                $_SESSION['sukses'] = "Status publikasi berhasil diubah.";
            } else {
                $_SESSION['gagal'] = "Gagal mengubah status publikasi.";
            }
            
            // Redirect ke halaman cek.php
            header("Location: cek.php");
            exit;
        }
        
        
        // Query untuk mengambil data blog berdasarkan user_id
        $sql = "SELECT * FROM halaman WHERE user_id = '$user_id' $sqltambahan";
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
        $query = mysqli_query($koneksi, $sql);
        $total = mysqli_num_rows($query);
        $total_page = ceil($total / $per_halaman);
        $nomor = $mulai + 1;
        $sql .= " ORDER BY id DESC LIMIT $mulai, $per_halaman";
        $query = mysqli_query($koneksi, $sql);


        if (mysqli_num_rows($query) > 0):
            while ($r1 = mysqli_fetch_array($query)): ?>
                <div class="post-card">
                    <h2 class="post-title"><?php echo htmlspecialchars($r1['judul']); ?></h2>
                    <p class="post-quote"><?php echo htmlspecialchars($r1['kutipan']); ?></p>
                    <p id="isi" class="content"><?php echo max_kata(ambil_isi($r1['id']), 20); ?></p>
                    <p class="content">Tanggal isi: <?php echo date("d M Y", strtotime($r1['created_at'])); ?></p>  <!-- Menampilkan tanggal -->
                    <p class="content"><?php echo !empty($r1['tema']) ? htmlspecialchars($r1['tema']) : 'Tidak ada Kategori'; ?></p>
                    <a href="admin/input.php?id=<?php echo $r1['id'] ?>" class="tbl-pink">Edit</a>
                    <a href="materi.php?id=<?php echo $r1['id'] ?>" class="tbl-pink">Read More</a>
                    <a href="?id=<?php echo $r1['id']; ?>&action=delete" class="tbl-pink" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                    <div class="publish-btn-container">
                    <a href="cek.php?id=<?php echo $r1['id']; ?>&action=publish" class="publish-btn">
                        <?php echo ($r1['publish'] == 1) ? 'Unpublish' : 'Publish'; ?>
                    </a>

                    </div>
                </div>
            <?php endwhile; 
        else: ?>
            <p>Tidak ada data yang ditemukan.</p>
        <?php endif; ?>
        
       <!-- Pagination -->
<div class="pagination">
    <?php if ($total_page > 1): ?>
        <!-- Tombol Previous -->
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&katakunci=<?php echo urlencode($katakunci); ?>" class="prev-next-btn">Previous</a>
        <?php endif; ?>

        <!-- Loop untuk nomor halaman -->
        <?php for ($i = 1; $i <= $total_page; $i++): ?>
            <a href="?page=<?php echo $i; ?>&katakunci=<?php echo urlencode($katakunci); ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Tombol Next -->
        <?php if ($page < $total_page): ?>
            <a href="?page=<?php echo $page + 1; ?>&katakunci=<?php echo urlencode($katakunci); ?>" class="prev-next-btn">Next</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

    </div>
</section>


<script>
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
