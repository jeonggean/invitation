<?php
// Load konfigurasi
$config = require_once '../config/invitation.php';
require_once '../db/connection.php';

// Ambil nama tamu dari parameter URL
$nama_tamu = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan';

// Handle form submission
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_wish'])) {
    $nama = trim($_POST['nama'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');
    
    if (!empty($nama) && !empty($pesan)) {
        $success = saveWish($nama, $pesan);
    }
    
    // Redirect untuk mencegah resubmit
    header('Location: ' . $_SERVER['PHP_SELF'] . '?to=' . urlencode($nama_tamu) . '#wishes');
    exit;
}

// Ambil semua ucapan
$wishes = getWishes();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $config['judul'] ?> - <?= $config['nama_anak'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Landing Page -->
    <div id="landing" class="landing-page active">
        <div class="landing-content">
            <div class="foto-wrapper">
                    <img src="images/foto_robby.jpg" alt="Foto Keluarga" class="foto-keluarga">
                    <p class="caption-foto">Keluarga Besar <?= $config['nama_ayah'] ?> & <?= $config['nama_ibu'] ?></p>
                </div>
            <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
            <h1 class="landing-title"><?= $config['judul'] ?></h1>
            <div class="kepada">
                <p>Kepada Yth:</p>
                <h2 class="nama-tamu"><?= $nama_tamu ?></h2>
            </div>
            <button onclick="bukaUndangan()" class="btn-buka">Buka Undangan</button>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="main-content">
        <!-- Section 1: Foto Keluarga -->
        <section class="section-foto">
            <div class="container">
                <div class="foto-wrapper">
                    <img src="images/foto_keluarga.jpg" alt="Foto Keluarga" class="foto-keluarga">
                    <p class="caption-foto">Keluarga Besar <?= $config['nama_ayah'] ?> & <?= $config['nama_ibu'] ?></p>
                </div>
            </div>
        </section>

        <!-- Section 2: Nama Anak -->
        <section class="reveal">
            <div class="card-minimalis">
                <img src="images/foto_robby.jpg" alt="Foto Robby" class="foto-anak-side">
                <div class="info-anak-side">
                    <h2><?= $config['nama_anak'] ?></h2>
                    <p>
                        Putra ke-tiga dari:<br>
                        <strong>Bapak <?= $config['nama_ayah'] ?></strong><br>
                        & <strong>Ibu <?= $config['nama_ibu'] ?></strong>
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 3: Ayat/Doa -->
        <section class="section-ayat">
            <div class="container">
                <div class="ayat-box">
                    <p class="ayat-arab"><?= $config['ayat']['teks_arab'] ?></p>
                    <p class="ayat-latin"><?= $config['ayat']['teks_latin'] ?></p>
                    <p class="ayat-keterangan"><?= $config['ayat']['keterangan'] ?></p>
                </div>
            </div>
        </section>
        

        <section class="section-acara reveal">
            <div class="container">
                <h3 class="section-title">Detail Acara</h3>
                
                <div class="acara-container">
                    <div class="acara-card-new">
                        <span class="acara-icon">📅</span>
                        <h4>Waktu Pelaksanaan</h4>
                        <p><strong><?= $config['hari_tanggal'] ?></strong></p>
                        <p><?= $config['waktu'] ?></p>
                    </div>
                </div>
            </div>
        </section>
        

        <!-- Section 5: Lokasi Google Maps -->
        <section class="section-map">
            <div class="container">
                <h3 class="section-title">Lokasi Acara</h3>
                <div class="map-wrapper">
                    <iframe 
                        src="<?= $config['google_maps_embed'] ?>" 
                        width="100%" 
                        height="300" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
                <a href="<?= $config['google_maps_link'] ?>" target="_blank" class="btn-map">
                    Buka di Google Maps
                </a>
            </div>
        </section>

        <!-- Section 6: Wishes -->
        <section class="section-wishes" id="wishes">
            <div class="container">
                <h3 class="section-title">Ucapan & Doa</h3>
                
                <!-- Form -->
                <div class="wish-form-wrapper">
                    <form method="POST" class="wish-form">
                        <div class="form-group">
                            <input type="text" name="nama" placeholder="Nama Anda" required maxlength="255">
                        </div>
                        <div class="form-group">
                            <textarea name="pesan" placeholder="Tuliskan ucapan dan doa Anda..." required rows="4"></textarea>
                        </div>
                        <button type="submit" name="submit_wish" class="btn-submit">Kirim Ucapan</button>
                    </form>
                </div>

                <!-- Daftar Ucapan -->
                <div class="wishes-list">
                    <?php if (empty($wishes)): ?>
                        <p class="no-wishes">Belum ada ucapan. Jadilah yang pertama!</p>
                    <?php else: ?>
                        <?php foreach ($wishes as $wish): ?>
                            <div class="wish-item">
                                <strong class="wish-nama"><?= htmlspecialchars($wish['nama_pengirim']) ?></strong>
                                <p class="wish-pesan"><?= nl2br(htmlspecialchars($wish['pesan'])) ?></p>
                                <span class="wish-time"><?= date('d M Y, H:i', strtotime($wish['created_at'])) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <p>بَارَكَ اللَّهُ</p>
            <p class="footer-text">Terima kasih atas doa dan ucapannya</p>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>