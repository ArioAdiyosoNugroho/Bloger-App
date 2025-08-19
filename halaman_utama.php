<?php
// index.php
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Selamat Datang di Blog Kami</title>

    <!-- Link untuk AOS (Animate On Scroll) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Link untuk Font yang dipilih -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Styling CSS -->
    <style>
        body {
            font-family: 'Lora', serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .content {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 40px;
            width: 70%;
            max-width: 800px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in-out;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .button {
            background-color: #1e3c72;
            color: white;
            padding: 15px 30px;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #2a5298;
        }
        footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 0.9rem;
            color: #ccc;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content" data-aos="fade-up">
            <h1>Selamat Datang di Blog Kami!</h1>
            <p>Blog ini menyediakan berbagai informasi dan artikel yang bermanfaat. Nikmati konten kami dan temukan sesuatu yang menarik!</p>
            <a href="login.php" class="button">Masuk untuk Menikmati Lebih Banyak</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Blog Kami. Semua hak cipta dilindungi.</p>
    </footer>

    <!-- Include AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();  // Inisialisasi AOS
    </script>
</body>
</html>
