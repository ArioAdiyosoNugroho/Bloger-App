<?php
if ($_FILES['file']['name']) {
    $fileName = $_FILES['file']['name'];
    $fileTmp = $_FILES['file']['tmp_name'];
    $filePath = '../uploads/' . $fileName;

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($fileTmp, $filePath)) {
        // Return URL gambar relatif terhadap server
        echo $filePath; // Path relatif gambar
    } else {
        http_response_code(500);
        echo "Gagal menyimpan gambar.";
    }
}
?>


<script>
    $.ajax({
   url: "upload_image.php",  // Pastikan ini sesuai dengan lokasi file PHP
   method: "POST",
   data: data,
   contentType: false,
   processData: false,
   success: function(response) {
     $('#summernote').summernote('insertImage', response);
   },
   error: function() {
     alert("Gagal mengunggah gambar. Coba lagi.");
   }
});

</script>