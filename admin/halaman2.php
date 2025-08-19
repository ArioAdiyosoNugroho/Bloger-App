<?php
session_start();
include("../inc/koneksi.php");
include("../inc/inc_fungsi.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Mengambil role pengguna dari database berdasarkan session
$user_id = $_SESSION['user_id']; // Ambil user_id dari session
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
$r1 = mysqli_fetch_assoc($query); // Ambil data user

// Periksa apakah data user ditemukan
if (!$r1) {
    echo "User tidak ditemukan.";
    exit();
}

// Simpan role ke dalam session
$_SESSION['role'] = $r1['role'];  // Menyimpan role ke session



// Cek apakah role admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: input.php");
    exit();
}

include("../inc/inc_header.php");
?>

<h1>BLOGER</h1>
<p>
    <a href="../index.php">
        <input type="button" class="btn btn-primary" value="Kembali">
    </a>

    <a href="halaman.php">
        <input type="button" class="btn btn-primary" value="data bloger">
    </a>
</p>
<?php
$sukses = "";
$gagal = "";

$katakunci = (isset($_GET['katakunci'])) ? $_GET['katakunci'] : "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM halaman WHERE id = '$id'";
    $query = mysqli_query($koneksi, $sql);
    if ($query) {
        $sukses = "Data berhasil dihapus";
    } else {
        $gagal = "Data gagal dihapus";
    }
}
?>

<?php
if ($sukses) {
    ?>
    <div class="alert alert-primary" role="alert">
        <?php echo $sukses ?>
    </div>
    <?php
}
?>

<?php
if ($gagal) {
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $gagal ?>
    </div>
    <?php
}
?>

<form action="" class="row g-3" method="get">
    <div class="col-auto">
        <input type="text" class="form-control" placeholder="masukkan kata kunci" name="katakunci" value="<?php echo $katakunci ?>">
    </div>
    <div class="col-auto">
        <input type="submit" name="cari" value="Cari Tulisan" class="btn btn-secondary">
    </div>
</form>

<!-- ====================values================== -->

<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-1">#</th>
            <th>id user</th>
            <th>Username</th>
            <th class="col-2">Email</th>
            <th class="col-2">role</th>

        </tr>
    </thead>

    <tbody>
    <?php
    $sqltambahan = "";
    $per_halaman = 5; // Tentukan jumlah data per halaman
    if ($katakunci != "") {
        $array_katakunci = explode(" ", $katakunci);
        for ($x = 0; $x < count($array_katakunci); $x++) {
            $sqlcari[] = "(judul LIKE '%" . $array_katakunci[$x] . "%' OR kutipan LIKE '%" . $array_katakunci[$x] . "%' OR isi LIKE '%" . $array_katakunci[$x] . "%')";
        }
        $sqltambahan = "WHERE " . implode(" OR ", $sqlcari);
    }
    
    $sql = "SELECT * FROM users $sqltambahan ";
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
    
    $query = mysqli_query($koneksi, $sql);
    $total = mysqli_num_rows($query);
    $total_page = ceil($total / $per_halaman);
    $nomor = $mulai + 1;
    $sql = $sql . " ORDER BY id DESC LIMIT $mulai, $per_halaman";

    $query = mysqli_query($koneksi, $sql);

    // Loop melalui setiap baris hasil query
    while ($r2 = mysqli_fetch_array($query)) {
        ?>
        <tr>
            <td><?php echo $nomor++ ?></td>
            <td><?php echo htmlspecialchars($r2['id']); ?></td>
            <td><?php echo htmlspecialchars($r2['username']); ?></td>
            <td><?php echo htmlspecialchars($r2['email']); ?></td>
            <td><?php echo htmlspecialchars($r2['role'] ?? 'user'); ?></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>

<!-- ==================pagination===================== -->
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php
        $cari = (isset($_GET['cari'])) ? $_GET['cari'] : "";
        for ($i = 1; $i <= $total_page; $i++) {
            ?>
            <li class="page-item">
                <a class="page-link" href="halaman.php?katakunci=<?php echo urlencode($katakunci) ?>&cari=<?php echo urlencode($cari) ?>&page=<?php echo $i ?>"><?php echo $i ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</nav>

<?php
include("../inc/inc_footer.php");
?>
