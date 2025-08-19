<?php
include_once("inc/inc_fungsi.php");

if (session_status() === PHP_SESSION_NONE) {//Mengecek apakah sesi PHP belum dimulai (PHP_SESSION_NONE), jika belum, maka sesi akan dimulai dengan session_start()
    session_start();
}

include_once("inc/koneksi.php");

// $logged_in = false;

// if (isset($_POST['username']) && isset($_POST['password'])) {
//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     $sql = "SELECT id, username FROM users WHERE username = '$username' AND password = '$password'";
//     $result = mysqli_query($koneksi, $sql);

//     if (mysqli_num_rows($result) > 0) {
//         $user = mysqli_fetch_assoc($result);
//         $_SESSION['user_id'] = $user['id'];
//         $_SESSION['username'] = $user['username'];
//         header("Location: index.php");
//         exit();
//     } else {
//         echo "Username atau password salah!";
//     }
// }

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $logged_in = true;//menandakan status login adalah true
    $user_id = $_SESSION['user_id'];//jika id use ada dan disimpan dalam sesion
    $username = $_SESSION['username'];//dan username ada
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MY BLOG</title>
    <link rel="stylesheet" href="header.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                echo '<img src="profilFoto/' . $user_photo . '" alt="User   Photo">';
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
                <a href="index.php"><i class="bi bi-house-fill"></i> Home</a>
            </nav>';
        }
        ?>
    </div>
</div>

<script>
  const menuIcon = document.getElementById('menu-icon');
  const navbar = document.getElementById('navbar');

  menuIcon.addEventListener('click', () => {
      navbar.classList.toggle('active'); 
  });
</script>
