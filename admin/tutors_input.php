<?php
    include("../inc/inc_header.php")
?>

<!-- =====================php variabel============= -->
<?php
    $judul      = "";
    $kutipan    = "";
    $isi        = "";
    $eror       = "";
    $sukses     = "";

    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }else{
        $id = "";
    }

    if($id != ""){
        $sql = "SELECT * FROM halaman WHERE id = '$id'";
        $query = mysqli_query($koneksi, $sql);
        $r1 = mysqli_fetch_array($query);
        $judul = $r1['judul'];
        $kutipan = $r1['kutipan'];
        $isi = $r1['isi'];

        if($isi == ''){
            $eror = "Data tidak ditemukan";
        }
    }

    if (isset($_POST['simpan'])) {
        $judul      = $_POST['judul'];
        $kutipan    = $_POST['kutipan'];
        $isi        = $_POST['isi'];

        if ($judul == '' or $isi == '') {
            $eror = "Judul dan isi tidak boleh kosong";
        }
    }

      // Cek jika ada gambar yang di-upload dan tambahkan tag <img> ke dalam kolom isi
    //   if (isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {
    //     $file = $_FILES['upload'];
    //     $filename = uniqid() . '_' . $file['name']; // Nama file unik
    //     $destination = "uploads/" . $filename; // Folder tujuan

    //     // Validasi file gambar
    //     $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    //     if (in_array($file['type'], $allowedTypes)) {
    //         if (move_uploaded_file($file['tmp_name'], $destination)) {
    //             $url = 'uploads/' . $filename; // Path gambar
    //             $isi .= "<img src='$url' alt='Uploaded Image' />"; // Menambahkan gambar ke isi
    //         } else {
    //             $eror = "Gagal upload file gambar.";
    //         }
    //     } else {
    //         $eror = "File bukan gambar!";
    //     }
    // }

    // Jika tidak ada error, simpan data ke database
// If no error
if($id != "") {
    // Update existing record in the 'halaman' table
    $sql = "UPDATE halaman SET judul = '$judul', kutipan = '$kutipan', isi = '$isi', tgl_isi = now() WHERE id = '$id'";
    $query = mysqli_query($koneksi, $sql);
} else {
    // Insert new record into the 'halaman' table
    $sql = "INSERT INTO halaman(judul, kutipan, isi) VALUES ('$judul', '$kutipan', '$isi')";
    $query = mysqli_query($koneksi, $sql);
}


    // Check if the query was successful
    if ($query) {
        $sukses = "Data Berhasil Disimpan";
    } else {
        $eror = "Data Gagal Disimpan";
    }


?>



<!-- =======================masukkan gambar=================== -->

<?php
// if (isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {
//     $file = $_FILES['upload'];
//     $filename = uniqid() . '_' . $file['name']; // Nama file unik
//     $destination = "uploads/" . $filename; // Folder tujuan

//     // Validasi file gambar
//     $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
//     if (in_array($file['type'], $allowedTypes)) {
//         if (move_uploaded_file($file['tmp_name'], $destination)) {
//             $url = 'uploads/' . $filename; // Path gambar
//             $funcNum = $_GET['CKEditorFuncNum'];  // Mendapatkan nomor fungsi dari CKEditor
//             echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', 'Upload berhasil!');</script>";
//         } else {
//             echo "<script>alert('Gagal upload file gambar.');</script>";
//         }
//     } else {
//         echo "<script>alert('File bukan gambar!');</script>";
//     }
// }
?>
<!-- =============================-->
    <h1>Tambahkan Blog</h1>
    <div class="mb-3 row">
        <a href="halaman.php">kembali ke halaman utama</a>
    </div>
<!-- ===================php============= -->
    <?php
    if($eror){
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $eror ?>
    </div>
    <?php
    }
    ?>

<?php
    if($sukses){
    ?>
    <div class="alert alert-success" role="alert">
        <?php echo $sukses ?>
    </div>
    <?php
    }
    ?>
<!-- ============================= -->
    <form action="" method="post">
    <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="judul" value="<?php echo $judul ?>" name="judul">
        </div>
    </div>

    <div class="mb-3">
        <label for="kutipan" class="form-label">Kutipan</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="kutipan" value="<?php echo $kutipan ?>" name="kutipan">
        </div>
    </div>

    <!-- <div class="mb-3">
    <label for="isi" class="form-label">Isi</label>
    <div class="col-sm-10">
        <textarea name="isi" class="form-control" id="editor"><//?php echo $isi ?></textarea>
    </div>
    </div> -->

    <div class="mb-3">
        <label for="isi" class="form-label">Isi</label>
        <div class="col-sm-10">
            <textarea name="isi" class="form-control" id="summernote" ><?php echo $isi ?></textarea>
        </div>
    </div>

<div class="mb-3">
        <label for="upload" class="form-label">Upload Gambar</label>
        <input type="file" class="form-control" name="upload" />
    </div>




    <div class="mb-3">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary"/>
        </div>
    </div>

    </form>

<!-- ========================== -->
<?php
    include("../inc/inc_footer.php");
?>