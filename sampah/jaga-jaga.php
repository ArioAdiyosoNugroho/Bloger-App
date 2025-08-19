<?php if (mysqli_num_rows($query) > 0) { 
        while ($r1 = mysqli_fetch_array($query)): ?>
            <div class="post-card">
                <!-- ===========judul========= -->
                <h2 class="post-title"><?php echo htmlspecialchars($r1['judul']); ?></h2>

               <!-- =====sampul==== -->
                <div class="gambars">
                <?php
                    $sampul_gambar = ''; 


                    $sampul_gambar = $r1['sampul_gambar'];

                    if ($sampul_gambar != ''):
                    ?>
                        <img src="uploads/<?php echo $sampul_gambar; ?>" alt="Sampul">
                    <?php endif; ?>
                    </div>

                 <!-- =======gambar============= -->

                <!-- <//?php 
                $gambar = ambil_gambar1($r1['id']);
                if (!empty($gambar)): ?>
                    <img src="<//?php echo $gambar; ?>" alt="Blog Image"/>
                <//?php endif; ?> -->

                <!-- =============kutipan============== -->
                <p class="post-quote"><?php echo htmlspecialchars($r1['kutipan']); ?></p>
                
                <!-- ===id===   -->
                <p class="content"><?php echo max_kata(ambil_isi($r1['id']), 30); ?></p>
                <!-- ======inpo====== -->
                <div class="post-info">
                    <p><?php echo (isset($r1['role']) && htmlspecialchars($r1['role']) == 'admin') ? '(admin😹)' : ''; ?></p>
                    <p>Penulis: <?php echo htmlspecialchars($r1['username']); ?> | Tanggal: <?php echo date("d M Y", strtotime($r1['created_at'])); ?></p>

                    <?php if (!empty($r1['tgl_isi'])): ?>
                        <p>Tanggal Update: <?php echo date("d M Y", strtotime($r1['tgl_isi'])); ?></p>
                    <?php endif; ?>
                                </div>
                                <a href="read.php?id=<?php echo $r1['id']; ?>" class="tbl-pink">Read More</a>
                            </div>
                        <?php endwhile;
                    } else {
                        echo "<p>Tidak ada data yang ditemukan.</p>";
                    } ?>
            </div>
