<?php
ob_start(); // Memulai buffering output
session_start();
// kode lainnya...

include("../inc/inc_header.php");

// ================== cek login ==========================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/"); 
    exit;
}

$user_id = $_SESSION['user_id'];

// =====================php variabel=============
$judul      = "";
$kutipan    = "";
$isi        = "";
$tema       = "";
$publish    = 0;  
$eror       = "";
$sukses     = "";
$sampul_gambar = '';  

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = "";
}

if ($id != "") {
    $sql = "SELECT * FROM halaman WHERE id = '$id' AND user_id = '$user_id'";
    $query = mysqli_query($koneksi, $sql);
    $r1 = mysqli_fetch_array($query);
    $judul = $r1['judul'];
    $kutipan = $r1['kutipan'];
    $isi = $r1['isi'];
    $tema = $r1['tema'];
    $publish = $r1['publish']; // Ambil status publish dari database
    $sampul_gambar = $r1['sampul_gambar']; // Ambil nama sampul gambar

    if ($isi == '') {
        $eror = "Data tidak ditemukan atau tidak dapat diakses.";
    }
}

if (isset($_POST['simpan'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);//cegah sql injeksi '"😊#tertawa tapi terluka
    $kutipan = mysqli_real_escape_string($koneksi, $_POST['kutipan']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $tema = mysqli_real_escape_string($koneksi, $_POST['tema']);
    $publish = isset($_POST['publish']) ? 1 : 0;  // Cek apakah checkbox publish dicentang


    // ===================Proses upload sampul gambar jika ada=========================
    if (isset($_FILES['sampul_gambar']) && $_FILES['sampul_gambar']['error'] == 0) {//Mengecek apakah tidak ada error dalam proses upload (error code 0 berarti tidak ada masalah).
        $file_name = $_FILES['sampul_gambar']['name'];
        $file_tmp = $_FILES['sampul_gambar']['tmp_name'];//ada di tem laragon/tempat sementara file
        $file_size = $_FILES['sampul_gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));//mendapatkan informasi dari file dan eksistensi file

        //============================== Tentukan ekstensi yang diperbolehkan==========================
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');//cek eksistensi file

        if (in_array($file_ext, $allowed_ext) && $file_size <= 5000000) {  // Maksimal 5MB=====  in_array memeriksa apakah $file_ext cocok dengan salah satu nilai di dalam $allowed_ext.

            // ==========================folder upload===================
            $upload_dir = '../sampul/';  // Folder utama 'uploads'
            $new_file_name = uniqid('sampul_', true) . '.' . $file_ext;//mengubah nama file,uniq=menhasilakn str unk berdasarkan waktu membuat tidak ada yang tertimpa/dupliakasi
            $upload_path = $upload_dir . $new_file_name;//menghubungkan folder upload dengan nama file baru

            // ============Hapus file sampul lama jika ada=======================
            if ($sampul_gambar != '') {
                $old_image_path = $upload_dir . $sampul_gambar;//menggabungkan tempat upload dengan nama file lama
                if (file_exists($old_image_path)) {//memeriksa file gamabar apakah ada
                    unlink($old_image_path);  // Menghapus file lama
                }
            }

            // ============Pindahkan file ke folder tujuan=================
            if (move_uploaded_file($file_tmp, $upload_path)) {// memindahkan file yang di-upload dari lokasi sementara (di server) ke lokasi yang permanen yang Anda tentukan.
                $sampul_gambar = $new_file_name;  // Simpan nama file gambar
            } else {
                $eror = "Gagal meng-upload gambar.";
            }
        } else {
            $eror = "File tidak valid. Pastikan gambar memiliki ekstensi jpg, jpeg, png, gif dan tidak lebih dari 5MB.";
        }
    }

    if ($judul == '' || $isi == '') {
        $_SESSION['eror'] = "Judul dan isi tidak boleh kosong";
        header("Location: input.php?id=$id");
        exit;
    } else {
        if ($id != "") {
            // Update data
            $sql = "UPDATE halaman SET judul = '$judul', kutipan = '$kutipan', isi = '$isi', tema = '$tema', publish = '$publish', sampul_gambar = '$sampul_gambar', tgl_isi = now() WHERE id = '$id' AND user_id = '$user_id'";
            $query = mysqli_query($koneksi, $sql);
        } else {
            // Insert data baru
            $sql = "INSERT INTO halaman (user_id, judul, kutipan, isi, tema, publish, sampul_gambar) VALUES ('$user_id', '$judul', '$kutipan', '$isi', '$tema', '$publish', '$sampul_gambar')";
            $query = mysqli_query($koneksi, $sql);
            $id = mysqli_insert_id($koneksi); // Ambil ID yang baru dibuat
        }
    
        if ($query) {
            $_SESSION['sukses'] = "Data Berhasil Disimpan";
            header("Location: input.php?id=$id");
            exit;
        } else {
            $_SESSION['eror'] = "Data Gagal Disimpan: " . mysqli_error($koneksi);
            header("Location: input.php?id=$id");
            exit;
        }
    }
}    
?>

<div class="container">
<h1 class="judul">Tambahkan Blog</h1>
<div class="mb-3 row">
    <a href="../cek.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>
    <!-- <a href="../cek.php">Kembali ke My Blog</a>
    <a href="../index.php">Kembali Ke Halaman Utama</a> -->
</div>

<?php
include 'notip.php';
?>

<?php
if ($sukses) {
?>
<div class="alert alert-success" role="alert">
    <?php echo $sukses ?>
</div>
<?php
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="isian">
        <label for="judul" class="label">Judul</label>
        <div class="kolom-a">
            <input type="text" class="kontrol" id="judul" value="<?php echo $judul ?>" name="judul">
        </div>
    </div>

    <div class="isian">
        <label for="kutipan" class="label">Kutipan</label>
        <div class="kolom-a">
            <input type="text" class="kontrol" id="kutipan" value="<?php echo $kutipan ?>" name="kutipan">
        </div>
    </div>

    <div class="isian">
        <label for="tema" class="label">Tema</label>
        <select name="tema" class="kontrol">
            <option value="">Pilih Tema</option>
            <option value="Anime" <?php echo ($tema == "Anime") ? "selected" : ""; ?>>Anime</option>
            <option value="Pengetahuan" <?php echo ($tema == "Pengetahuan") ? "selected" : ""; ?>>Pengetahuan</option>
            <option value="Video" <?php echo ($tema == "video") ? "selected" : ""; ?>>Video</option>
            <option value="Teknologi" <?php echo ($tema == "Teknologi") ? "selected" : ""; ?>>Teknologi</option>
            <option value="Meme" <?php echo ($tema == "Meme") ? "selected" : ""; ?>>Meme</option>
        </select>
    </div>

    <div class="isian">
        <label for="isi" class="label">Isi</label>
        <div class="kolom-a">
            <textarea name="isi" class="kontrol" id="summernote"><?php echo $isi ?></textarea>
        </div>
    </div>

    <div class="isian">
        <label for="sampul_gambar" class="label">Upload Sampul Gambar</label>
        <div class="kolom-a">
            <input type="file" name="sampul_gambar" class="btn-file"/>
            <div class="gambar">
                 <?php
                     if ($sampul_gambar != ''):
                     ?>
                         <img src="../sampul/<?php echo $sampul_gambar; ?>" alt="Sampul">
                     <?php endif; ?>
            </div>
        </div>
    </div>

    <p class="publish-p">Publish:</p>
    <label class="label">
        <div class="toggle">
            <input class="toggle-state" type="checkbox" name="publish" id="publish" value="1" <?php echo ($publish == 1) ? 'checked' : ''; ?>>
            <div class="indicator"></div>
        </div>
    </label>

    <div class="isian">
        <div class="luar"></div>
        <div class="dalam">
            <input type="submit" name="simpan" value="Simpan Data" class="btn-submit"/>
        </div>
    </div>
</form>
</div>

<?php
include("../inc/inc_footer.php");
?>
