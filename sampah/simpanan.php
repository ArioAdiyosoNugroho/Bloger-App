<!-- ini untuk halaman -->

<?php
    include("../inc/inc_header.php")
?>
    <?php
    $sukses =   "";
    $gagal  =   "";

    $katakunci = (isset($_GET['katakunci']))?$_GET['katakunci']:"";

    if(isset($_GET['op'])){
        $op = $_GET['op'];
    }else{
        $op = "";
    }
    if($op == 'delete'){
        $id     = $_GET['id'];
        $sql    ="delete from halaman where id = '$id'";
        $query  = mysqli_query($koneksi, $sql);
        if($query){
            $sukses = "Data berhasil dihapus";
            }else
            {
                $gagal = "Data gagal dihapus";
                }
    }
    ?>

    <h1>BLOGER</h1>
    <p>
        <a href="input.php">
            <input type="button" class="btn btn-primary" value="Buat halaman baru">
        </a>
    </p>
    <?php
    if($sukses){
        ?>
        <div class="alert alert-primary" role="alert">
             <?php echo $sukses ?>
        </div>

        <?php
    }
    
    ?>
    <?php
    if($gagal){
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $gagal ?>
        </div>

        <?php
    }
    
    ?>
        <form action="" class="row g-3" method="get">
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="masukkan kata kunci" name="katakunci" value="<?php echo $katakunci ?>">
            </div>
            <div class="col-auto">
                <input type="submit" name="cari" value="Cari Tulisan" class="btn btn-secondary">
            </div>
        </form>

        <!-- ====================values================== -->
        
        <table class="table table-stripet">
            <thead>

                <tr>
                    <th class="col-1">#</th>
                    
                    <th>judul</th>
                    
                    <th>kutipan</th>
                    
                    <th class="col-2">Aksi</th>
                </tr>
            </thead>
            
            <tbody>
            <?php
            $sqltambahan    =   "";
            $per_halaman = 5; // Tentukan jumlah data per halaman
            if($katakunci != ""){
                $array_katakunci= explode(" ", $katakunci);
                for($x=0; $x < count($array_katakunci); $x++){
                    $sqlcari[] = "(judul like '%".$array_katakunci[$x]."%' or kutipan like '%".$array_katakunci[$x]."%' or isi like '%".$array_katakunci[$x]."%' )";

                }
                $sqltambahan    ="where".implode(" or ",$sqlcari);

            }
            $sql    = "SELECT * FROM halaman $sqltambahan ";
            $page   =isset($_GET['page'])?(int)$_GET['page']:1;
            $mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
            $query = mysqli_query($koneksi, $sql);
            $total = mysqli_num_rows($query);            
            $total_page = ceil($total/$per_halaman);
            $nomor  =$mulai +1;
            $sql = $sql . " ORDER BY id DESC LIMIT $mulai, $per_halaman";




            $query  = mysqli_query($koneksi, $sql);
            while($r1 = mysqli_fetch_array($query)){
            ?>
                <tr>
                <td><?php echo $nomor++?></td>
                <td><?php echo $r1['judul']; ?></td>
                <td><?php echo $r1['kutipan']; ?></td>

                <td>
                    <!-- ==tombol== -->
                    <a href="input.php?id=<?php echo $r1['id']?>">
                    <span class="badge text-bg-warning">Edit</span>
                    </a>

                    <a href="halaman.php?op=delete&id=<?php echo $r1['id']?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')">
                    <span class="badge text-bg-danger">delete</span>
                    </a>
                </td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
<!-- ==================pagination===================== -->
        <nav aria-label="Page navigation exemple">
            <ul class="pagination">
                <?php
                $cari   =(isset($_GET['cari']))?$_GET['cari'] : "";
                for($i=1; $i <= $total_page; $i++){
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="halaman.php?katakunci=<?php echo $katakunci ?>&cari=<?php echo $cari ?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                    </li>
                    <?php

                } 
                ?>

        </nav>

<?php
    include("../inc/inc_footer.php")
?>
 
