<?php

// Menampilkan pesan sukses jika ada
if (isset($_SESSION['sukses'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['sukses'] . '</div>';
    unset($_SESSION['sukses']);  // Menghapus pesan setelah ditampilkan
}

// Menampilkan pesan error jika ada
if (isset($_SESSION['eror'])) {
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['eror'] . '</div>';
    unset($_SESSION['eror']);  // Menghapus pesan setelah ditampilkan
}

// Form untuk memasukkan data
?>
