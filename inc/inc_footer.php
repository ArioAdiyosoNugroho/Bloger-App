</main>

<footer class="bg-light">
    <div class="text-center p-3" style="background: #cccccc;">
    <p>&copy; 2024 Ario's Blog. All Rights Reserved.</p>
    </div>
</footer>

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
        setTimeout(hideNotification, 3000); // Tunda selama 3 detik sebelum menghilangkan notifikasi
    };



// ========app pihak ke 3 buat menulis sesuatu WYSWYG Editor========
$(document).ready(function() {
        $('#summernote').summernote({
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        $.upload(files[i]);
                    }
                    
                }
            },
            height: 500,

            toolbar: [
                // Height text
                ["height", ["height"]],
                // Grup Style
                ['style', ['style', 'bold', 'italic', 'underline', 'strikethrough', 'clear']],
                // Grup Font
                ['font', ['fontname', 'fontsize', 'superscript', 'subscript']],
                // Grup Warna
                ['color', ['color', 'forecolor', 'backcolor']],
                // Grup Paragraf
                ['para', ['ul', 'ol', 'paragraph', 'lineheight', 'align']],
                // Grup Table
                ['table', ['table']],
                // Grup Insert
                ['insert', ['link', 'picture', 'video', 'hr', 'imageList', 'audio', 'file']],
                // Grup Tampilan
                ['view', ['fullscreen', 'codeview', 'help']],
                // Grup Misc
                ['misc', ['undo', 'redo', 'print']],
                // Grup Layout
                ['layout', ['floatLeft', 'floatRight', 'floatNone', 'align']],
                // Grup Custom
                ['custom', ['highlight']],
                // Grup Emoji
                ['emoji', ['emoji']],
                // Grup Special Characters
                ['specialChar', ['specialChar']],
                // Grup HTML Formatting
                ['html', ['html']]
                ],
                fontNames: [
                    'Arial', 'Times New Roman', 'Courier New', 'Verdana', 
                    'Comic Sans MS', 'Georgia', 'Impact', 'Roboto', 'Tahoma', 
                    'Calibri', 'Lucida Console', 'Trebuchet MS', 'Palatino Linotype'
                ], // Daftar font
                
                fontSizes: [
                    '8', '9', '10', '11', '12', '14', '16', '18', '20', 
                    '24', '28', '32', '36', '48', '64', '72', '96', 
                    '100', '150'
                ], // Ukuran font
                
                styleTags: [
                    'p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'address', 'span'
                ], // Tag gaya teks

                emoji: {
                imagePath: 'https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/assets/png/',  // Path emoji
    },
                imageList: {
                endpoint: '/path/to/your/api/images',  // API untuk daftar gambar
                fullUrlPrefix: 'https://example.com/images/',  // Prefix URL gambar
                thumbUrlPrefix: 'https://example.com/images/thumbs/',  // Prefix URL thumbnail gambar
    },
    highlight: true,  // Fitur highlight

                directionality: true,  // Enables text direction (LTR/RTL)
                colorPalette: true,  // Enables color palette for more color choices

            dialogsInBody: true,
            imageList: {
                endpoint: "daftar-gambar.php",
                fullUrlPrefix: "../uploads/",
                thumbUrlPrefix: "../uploads/"
            }
            
        });

        $.upload = function(file) {
            let out = new FormData();
            out.append('file', file, file.name);

            $.ajax({
                method: 'POST',
                url: 'simpan-gambar.php',
                contentType: false,
                cache: false,
                processData: false,
                data: out,
                success: function(img) {
                    $('#summernote').summernote('insertImage', img);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        };
    });

    
    
</script>

</body>
</html>