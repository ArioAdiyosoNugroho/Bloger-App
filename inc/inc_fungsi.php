<?php
function url_dasar(){
    //$_SERVER['SERVER_NAME'] memberi alamat web
    //$_SERVER['SCRIPT_NAME']  directory web ,web/..
    $url_dasar = "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']);
    return $url_dasar;
}
    function ambil_gambar($id_tulisan){
    global $koneksi;
    $sql    = "SELECT * FROM halaman WHERE id = '$id_tulisan'";
    $query  = mysqli_query($koneksi, $sql);
    $r1     = mysqli_fetch_array($query);
    $text   = $r1['isi'];

    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $img);
    $gambar =$img[1];
    $gambar = str_replace("../uploads/",url_dasar()."/uploads/",$gambar);

    return $gambar;

}
function ambil_gambar1($id_tulisan){
    global $koneksi;
    $sql    = "SELECT * FROM halaman WHERE id = '$id_tulisan'";
    $query  = mysqli_query($koneksi, $sql);
    $r1     = mysqli_fetch_array($query);
    $text   = $r1['isi'];

    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $img);

    if (isset($img[1])) {
        $gambar = $img[1];
        $gambar = str_replace("../uploads/", url_dasar() . "/uploads/", $gambar);
        return $gambar;
    } else {
        return ''; 
    }
}


function ambil_judul($id_tulisan){
    global $koneksi;
    $sql    = "SELECT * FROM halaman WHERE id = '$id_tulisan'";
    $query  = mysqli_query($koneksi,$sql);
    $r1     =mysqli_fetch_array($query);
    $text   =$r1['judul'];
    return $text;

}

function ambil_kutipan($id_tulisan){
    global $koneksi;
    $sql    = "SELECT * FROM halaman WHERE id = '$id_tulisan'";
    $query  = mysqli_query($koneksi,$sql);
    $r1     =mysqli_fetch_array($query);
    $text   =$r1['kutipan'];
    return $text;

}

function ambil_tanggal($id_tulisan) {
    global $koneksi;
    // Pastikan menggunakan created_at, bukan tgl_isi
    $sql = "SELECT DATE_FORMAT(created_at, '%d-%m-%Y') as tgl_isi_formatted FROM halaman WHERE id = '$id_tulisan'";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        $r1 = mysqli_fetch_array($query);
        if ($r1 && $r1['tgl_isi_formatted']) {
            return $r1['tgl_isi_formatted']; // Menampilkan tanggal yang sudah diformat
        } else {
            return "Tanggal tidak ditemukan"; // Menangani jika tanggal kosong atau NULL
        }
    } else {
        return "Query gagal"; // Menangani kesalahan query
    }
}



function ambil_tabel($id_tulisan) {
    global $koneksi;

    // Query untuk mendapatkan data dari tabel berdasarkan ID
    $sql = "SELECT isi FROM halaman WHERE id = '$id_tulisan'";
    $query = mysqli_query($koneksi, $sql);

    // Memeriksa apakah query berhasil
    if ($query) {
        $r1 = mysqli_fetch_array($query);

        // Jika data ditemukan
        if ($r1) {
            $text = $r1['isi'];

            // Tambahkan div pembungkus
            $styledTable = '<div class="table-wrapper">' . $text . '</div>';

            return $styledTable;
        }
    }

    // Kembalikan pesan error jika data tidak ditemukan
    return '<div class="table-wrapper">Tidak ada data tabel</div>';
}




function ambil_isi($id_tulisan){
    global $koneksi;//akses variabel dalam fingsi
    $sql = "SELECT * FROM halaman WHERE id = '$id_tulisan'";
    $query = mysqli_query($koneksi, $sql);
    
    if ($query) {
        $r1 = mysqli_fetch_array($query);//ambil 1 baris hasil query
        if ($r1) {
            $text = strip_tags($r1['isi']);//hapus tag html
            return $text;
        }
    }
    return '';  // Kembalikan string kosong jika tidak ada data
}

function ambil_semua_isi() {
    global $koneksi;
    $sql = "SELECT isi FROM halaman ORDER BY id ASC"; // Ambil semua data
    $query = mysqli_query($koneksi, $sql);
    
    if ($query) {
        $result = [];
        while ($row = mysqli_fetch_array($query)) {
            $result[] = strip_tags($row['isi']);
        }
        return $result;
    }
    return [];  // Jika tidak ada data, kembalikan array kosong
}

function ambil_isi_baru($id_tulisan) {
    global $koneksi;
    $sql = "SELECT isi FROM halaman WHERE id = '$id_tulisan'";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        $r1 = mysqli_fetch_array($query);
        if ($r1) {
            return strip_tags($r1['isi']); // Strip tags untuk keamanan
        }
    }
    return ''; // Kembalikan string kosong jika tidak ada data
}






function bersihkan_judul($judul){
    // Mengubah semua huruf menjadi kecil
    $judul_baru = strtolower($judul);

    // Hanya memperbolehkan karakter alfanumerik, spasi, dan tanda hubung
    $judul_baru = preg_replace("/[^a-z0-9\s-]/", "", $judul_baru);

    // Mengganti spasi menjadi tanda hubung
    $judul_baru = preg_replace("/\s+/", "-", $judul_baru);

    // Menghapus tanda hubung berlebihan (misalnya: "---")
    $judul_baru = preg_replace("/-+/", "-", $judul_baru);

    // Menghapus tanda hubung di awal dan akhir string
    $judul_baru = trim($judul_baru, "-");

    return $judul_baru;
}


function buat_link_halaman($id){
    global $koneksi;
    $sql    = "SELECT * FROM halaman WHERE id = '$id'";
    $query  = mysqli_query($koneksi, $sql);
    $r1     = mysqli_fetch_array($query);
    $judul  = bersihkan_judul($r1['judul']); // Bersihkan judul langsung di sini
    return url_dasar()."/materi.php/$id/$judul";
}

function dapatkan_id(){
    $id = "";
    if(isset($_SERVER['PATH_INFO'])){
        $id = dirname($_SERVER['PATH_INFO']);
        $id = preg_replace("/[^0-9]/","",$id);
    }
    return $id;
}

function set_isi($isi){
    $isi    = str_replace("../uploads/",url_dasar()."/uploads/",$isi);
    return $isi;
}

function set_isi1($isi){
    if ($isi !== null) {
        $isi = str_replace("../uploads/", url_dasar() . "/uploads/", $isi);
    }
    return $isi;
}


function max_kata($isi,$max_kata){
    $array_isi  = explode(" ",$isi);
     //Fungsi array_slice digunakan untuk mengambil bagian dari array. Dalam hal ini, kita mengambil dari indeks ke-0 (awal array) hingga batas $max_kata jumlah elemen. Misalnya, jika $max_kata adalah 2, maka array setelah
    $array_isi  = array_slice($array_isi,0,$max_kata);//array yang ingin di potong,strt=0 dan jumlahnya=manut
    $isi        =implode(" ",$array_isi);
    if (count($array_isi) >= $max_kata) {
        $isi .= "...";
    }
    return $isi;
}

?>