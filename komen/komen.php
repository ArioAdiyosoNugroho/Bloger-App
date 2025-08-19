<?php 
if (session_status() == PHP_SESSION_NONE) {     
    session_start(); 
}

$id_post = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';    
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Cek apakah user sudah login

// ================== Data user
if ($user_id) {
    // Jika user sudah login, ambil data user
    $sql_user = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($koneksi, $sql_user);
    if ($result && mysqli_num_rows($result) > 0) {     
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "Gagal mengambil data user.";
        exit;
    }
}

// ========================== Utama
$sql = "SELECT profile_photo FROM users WHERE id = '$user_id'";
$query = mysqli_query($koneksi, $sql);

// Ambil komentar
$sql = "SELECT users.username, komen.komen, users.role, users.profile_photo
        FROM komen
        JOIN users ON komen.id_user = users.id
        WHERE komen.id_post = '$id_post'
        ORDER BY komen.id DESC";
$query = mysqli_query($koneksi, $sql);

// ================== Tambah komentar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['komen']) && isset($_POST['id_post'])) {
    if (!$user_id) { 
        //ini halaman include jadi gak usah pakai ../
        header("Location: login/index.php"); 
        exit;
    }

    $id_post = mysqli_real_escape_string($koneksi, $_POST['id_post']);
    $komen = trim($_POST['komen']);
    $komen = htmlspecialchars($komen, ENT_QUOTES, 'UTF-8');
    $id_user = $_SESSION['user_id'];

    // Cek jika komentar tidak kosong
    if (!empty($komen)) {
        $sql_insert = "INSERT INTO komen (id_user, id_post, komen) VALUES ('$id_user', '$id_post', '$komen')";
        if (mysqli_query($koneksi, $sql_insert)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_post);
            exit;
        } else {
            echo "<p class='no-comment'>Gagal mengirim komentar.</p>";
        }
    } else {
        echo "<p class='no-comment'>Komentar tidak boleh kosong.</p>";
    }
}
?>

<style>
.comment-avatar {
    width: 50px; 
    height: 50px; 
    border-radius: 50%; 
    overflow: hidden; 
    display: flex;
    justify-content: center;
    align-items: center;
}

.comment-avatar img {
    width: 100%;
    height: 100%; 
    object-fit: cover; 
}


</style>
<div class="comment-container">
    <form method="POST" class="comment-form">
        <input type="hidden" name="id_post" value="<?= htmlspecialchars($id_post) ?>">
        <input type="text" placeholder="Masukkan komentar" class="komen" name="komen">
        <button class="tbl-kirim" type="submit">Kirim</button>
    </form>

    <div class="comment-section">
        <?php if (mysqli_num_rows($query) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <div class="comment-item">
                    <div class="comment-avatar">
                        <img src="<?= htmlspecialchars('http://localhost/projek3/BLOGER/profilFoto/' . $row['profile_photo']) ?>" alt="Profile Photo">
                    </div>
                    <div class="comment-content">
                        <span class="username">
                            <?= htmlspecialchars($row['username']) ?>
                            <?php if ($row['role'] === 'admin') { ?>
                                <span class="role-admin">(admin)</span>
                            <?php } ?>
                        </span>
                        <p class="comment-text"><?= htmlspecialchars($row['komen']) ?></p>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="no-comment">Belum ada komentar.</p>
        <?php } ?>
    </div>
</div>