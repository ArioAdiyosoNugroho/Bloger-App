
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="style_profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- <header>
</header> -->

<?php if (isset($_SESSION['sukses'])): ?>
    <div class="alert alert-success">
        <h1><?php echo $_SESSION['sukses']; ?></h1>
    </div>
    <?php unset($_SESSION['sukses']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['gagal'])): ?>
    <div class="alert alert-danger">
        <h1><?php echo $_SESSION['gagal']; ?></h1>
    </div>
    <?php unset($_SESSION['gagal']); ?>
<?php endif; ?>

<section id="profile" class="content-section">
<h2>Delete Account</h2>
<a href="javascript:history.back()" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>

    <div class="profile-card">
        <a href="ganti-pp.php">
        <div class="profile-photo">
            <img src="<?php echo $user['profile_photo'] ? $user['profile_photo'] : 'https://via.placeholder.com/150'; ?>" alt="Profile Photo">
        </div>
        </a>
        </div>
    </div>

    <a href="hapus-akun.php" class="delete-account-button" onclick="return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')">
    <button class="danger-button"><i class="fas fa-trash-alt"></i> Hapus Akun</button>
</a>

</section>

</body>
</html>
