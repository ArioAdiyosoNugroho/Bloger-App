<?php
session_start();
include("inc/koneksi.php");
include("inc/inc_fungsi.php");

// Cek apakah pengguna sudah login
$logged_in = false;
$user_id = $username = '';
if (isset($_SESSION['user_id'])) {
    $logged_in = true;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}

$search_query = "";
$tema = "";

//======================= Mengecek apakah ada pencarian===========================
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($koneksi, $_GET['search']);
    $tema = mysqli_real_escape_string($koneksi, $_GET['tema']); // Ambil nilai tema yang dipilih dari tema dan mencegah sql injetion
    
    //aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    $sql = "SELECT halaman.*, users.username, users.role, halaman.created_at 
            FROM halaman 
            JOIN users ON halaman.user_id = users.id
            WHERE (halaman.judul LIKE '%$search_query%' 
                   OR halaman.kutipan LIKE '%$search_query%' 
                   OR users.username LIKE '%$search_query%'
                   OR halaman.isi LIKE '%$search_query%') 
            AND (halaman.tema LIKE '%$tema%' OR '$tema' = '')
            AND halaman.publish = 1
            ORDER BY halaman.id DESC";
} else {
    // Jika tidak ada pencarian
    $sql = "SELECT halaman.*, users.username, users.role, halaman.created_at 
            FROM halaman 
            JOIN users ON halaman.user_id = users.id 
            WHERE halaman.publish = 1
            ORDER BY halaman.id DESC";
}


// populer==Query untuk mengambil artikel berdasarkan views tertinggi
$sql_populer = "SELECT halaman.*, users.username, users.role, halaman.created_at 
                FROM halaman 
                JOIN users ON halaman.user_id = users.id 
                WHERE halaman.publish = 1 
                ORDER BY halaman.views DESC 
                LIMIT 5";
$query_populer = mysqli_query($koneksi, $sql_populer);//melakikan oprasi di database


// ====================Pagination setup=================
$per_halaman = 5;  
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;//mengecek apakah paramater page ada di url jika ada misal(page=2) maka akan di ambil jika tidak akan bernilai 1
// if (isset($_GET['page'])) {
//     $page = (int)$_GET['page'];
// } else {
//     $page = 1;
// }

// SELECT id,judul FROM halaman WHERE publish = 1 ORDER BY id DESC LIMIT 5, 5;

// if ($page>1){
//     $mulai=$page * $per_halaman-$per_halaman;
// }else{
//     $mulai=0;
// }
  
$mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;  
$sql .= " LIMIT $mulai, $per_halaman";//Menambahkan bagian LIMIT ke query SQL, yang menginstruksikan MySQL untuk hanya mengambil
//data sebanyak $per_halaman dimulai dari posisi $mulai.

$query = mysqli_query($koneksi, $sql);

$total_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM halaman WHERE publish = 1");
$total_data = mysqli_fetch_assoc($total_query)['total'];//assoc=mengambil satu baris hasil query
$total_page = ceil($total_data / $per_halaman);  // Hitung total halaman dengan di bulatkan ke ats 1.2=2
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BlogBox</title>
    <link rel="stylesheet" href="index.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
     <!-- Link CDN untuk Bootstrap Icons -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <header>
        <img class="logo" src="bg-foto/5.png" alt="Logo">
        <h1>Blog<div class="box">Box</div></h1>
    </header>
    <div class="nav-right">
        <a href="<?php echo $logged_in ? 'login/profil.php' : 'login/'; ?>">
            <?php 
            if ($logged_in) {
                $sql_user = "SELECT profile_photo FROM users WHERE id = '$user_id'";
                $query_user = mysqli_query($koneksi, $sql_user);
                $user_data = mysqli_fetch_assoc($query_user);
                $user_photo = $user_data['profile_photo'] ? $user_data['profile_photo'] : 'default-avatar.png';
                echo '<img src="profilFoto/' . $user_photo . '" alt="User  Photo">';
            } else {
                echo 'SignUp';
            }
            ?>
        </a>
        <?php
if ($logged_in) {
    echo '<i class="fa-solid fa-ellipsis-vertical menu" id="menu-icon"></i>

    <nav id="navbar">
        <a href="admin/input.php" class="active"><i class="bi bi-plus-square"></i> Add</a>
        <a href="cek.php"><i class="bi bi-journal-richtext"></i> My Blog</a>
    </nav>';
}




?>

    </div>
</div>



<div class="bg-atas">
    <h1 class="welcome">
        Welcome To BlogBox
    </h1>
</div>


<!-- Search bar -->
<div class="search-bar-container">
    <form id="searchForm" method="GET" action="" class="search-bar">
    <i class="fa-solid fa-magnifying-glass"></i><input type="text" name="search" placeholder="Cari kata kunci..." value="<?php echo htmlspecialchars($search_query); ?>">
        
        <!-- Dropdown Pilih Tema -->
        <select name="tema" class="kontrol">
                        <option value="">Pilih Kategori</option>
            <option value="Anime" <?php echo ($tema == "Anime") ? "selected" : ""; ?>>Anime</option>
            <option value="Pengetahuan" <?php echo ($tema == "Pengetahuan") ? "selected" : ""; ?>>Pengetahuan</option>
            <option value="Video" <?php echo ($tema == "Video") ? "selected" : ""; ?>>Video</option>
            <option value="Teknologi" <?php echo ($tema == "Teknologi") ? "selected" : ""; ?>>Teknologi</option>
            <option value="Meme" <?php echo ($tema == "Meme") ? "selected" : ""; ?>>Meme</option>
        </select>
        
        <input type="submit" value="Cari">
    </form>
</div>

<div class="container">
    <h2>Semua Halaman</h2>

      <!-- Artikel Populer -->

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
                <img class="gambar-populer" src="sampul/<?php echo $sampul_gambar; ?>" alt="Sampul">
            <?php endif;
            
            echo '<p class="populer-kutip">' . htmlspecialchars($row['kutipan']) . '</p>';
            if ($sampul_gambar == '') { 
                echo '<p class="content">' . max_kata(ambil_isi($row['id']), 20) . '</p>';
            }
            echo '<a href="read.php?id=' . $row['id'] . '" class="tbl-populer">Read More</a>';
            echo '</div>';
        }
    } else {
        echo "<p>Artikel populer tidak tersedia.</p>";
    }
    ?>
    </div>
</div>



    <?php if (mysqli_num_rows($query) > 0) { 
        while ($r1 = mysqli_fetch_array($query)): ?> <!-- memanggil satu baris hasil query dengn bentuk aray -->
            <div class="post-card">
                
                <!-- =====sampul==== -->
                 <div class="gambar">
                 <?php
                     $sampul_gambar = ''; 

 
                     $sampul_gambar = $r1['sampul_gambar'];
 
                     if ($sampul_gambar != ''):
                     ?>
                         <img src="sampul/<?php echo $sampul_gambar; ?>" alt="Sampul">
                     <?php endif; ?>
                     </div>
                <!-- ===========judul========= -->
                <h2 class="post-title"><?php echo htmlspecialchars($r1['judul']); ?></h2>


                 <!-- =======gambar============= -->

                <!-- <//?php 
                $gambar = ambil_gambar1($r1['id']);
                if (!empty($gambar)): ?>
                    <img src="<//?php echo $gambar; ?>" alt="Blog Image"/>
                <//?php endif; ?> -->

                <!-- =============kutipan============== -->
                <p class="post-quote"><?php echo htmlspecialchars($r1['kutipan']); ?></p>
                
                <!-- ===id===-->
                <p class="content"><?php echo max_kata(ambil_isi($r1['id']), 30); ?></p>
                <!-- ======inpo====== -->
                <div class="post-info">
                    <p><?php echo (isset($r1['role']) && htmlspecialchars($r1['role']) == 'admin') ? '(admin😹)' : ''; ?></p>
                    <p>Penulis: <?php echo htmlspecialchars($r1['username']); ?> | Tanggal: <?php echo date("d M Y", strtotime($r1['created_at'])); ?></p>

                    <?php if (!empty($r1['tgl_isi'])): ?>
                        <p>Tanggal Update: <?php echo date("d M Y", strtotime($r1['tgl_isi'])); ?></p>
                    <?php endif; ?>
                                </div>
                                <a href="read.php?id=<?php echo $r1['id']; ?>" class="tbl-pink">Read More</a>
                            </div>
                        <?php endwhile;
                    } else {
                        echo "<p>Tidak ada data yang ditemukan.</p>";
                    } ?>
            </div>

<!-- Pagination -->
<div class="pagination">
    <?php if ($total_page > 1): ?>

        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_query); ?>&tema=<?php echo urlencode($tema); ?>" class="prev-next-btn">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_page; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>&tema=<?php echo urlencode($tema); ?>" class="page-btn <?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_page): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_query); ?>&tema=<?php echo urlencode($tema); ?>" class="prev-next-btn">Next</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>

<script>

    const menuIcon = document.getElementById('menu-icon');
    const navbar = document.getElementById('navbar');

    menuIcon.addEventListener('click', () => {
        navbar.classList.toggle('active'); 
    });
    
    
    let scrollPos = sessionStorage.getItem('scrollPos');
if (scrollPos) {
    window.scrollTo(0, scrollPos);
}


document.getElementById('searchForm').addEventListener('submit', function(event) {
    scrollPos = window.scrollY;
    sessionStorage.setItem('scrollPos', scrollPos);
});


const paginationLinks = document.querySelectorAll('.pagination a');
paginationLinks.forEach(link => {
    link.addEventListener('click', function() {
        scrollPos = window.scrollY;
        sessionStorage.setItem('scrollPos', scrollPos);
    });
});
</script>

<!-- <script src="js/index.js"></script> -->


</html>