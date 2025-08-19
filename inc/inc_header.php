<?php
include("koneksi.php")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY BLOG</title>
    <!-- properr -->
    <!-- Memuat jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <!-- Memuat Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Memuat Summernote -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    
    <!-- Memuat Plugin untuk Daftar Gambar -->
    <script src="js/summernote-image-list.min.js"></script>
    
    <link href="../css/summernote-image-list.min.css">
    <script src="../js/summernote-image-list.min.js"></script>
    
    <!-- Memuat Plugin Emojione -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/lib/js/emojione.min.js"></script>
    
    <!-- Memuat Plugin Highlight -->
    <script src="path/to/your/highlightPlugin.js"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="../css/style_input.css">

  <style>


.note-editable img {
    max-width: 100%; /* Menjaga gambar tetap responsif */
    margin: 0 auto; /* Margin otomatis untuk pemusatan */
}


.note-editor .note-editable img,
.note-editor .note-editable video {
    display: block;
    margin-left: auto;
    margin-right: auto;
}


.image-list-content {
    max-height: 300px; /* Tinggi maksimum area daftar gambar */
    overflow-y: auto; /* Aktifkan scrollbar vertikal */
    overflow-x: hidden; /* Sembunyikan scrollbar horizontal */
    padding-right: 10px; /* Tambahkan ruang untuk scrollbar */
}

.image-list-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.image-list-item img {
    width: 50px; /* Ukuran gambar thumbnail */
    height: auto;
    margin-right: 15px;
}

    .image-list-content .col-lg-3{
      width: 100%;
    }

    .image-list-content img{
      float: left;
      width: 10%;
    }

    .image-list-content p{
      float: left;
      padding-left: 20px;
      color: black;
    }

    .image-list-item{
      display: flex;
      padding: 7px 0px;
    }

    .note-modal-content .note-modal-body span{
        color: black;
    }

    .tombolr{
      background-color: #4CAF50;
      padding: 10px 20px;
    }
  


    
  </style>

</head>
<!-- =======================navigasi================== -->

<!-- ====================isi=================== -->
<body>

<main>