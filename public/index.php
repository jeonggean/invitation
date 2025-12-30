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
        <section class="section-nama">
            <div class="container">
                <div class="ornamen-top">❈</div>
                <p class="pengantar"><?= $config['pengantar'] ?></p>
                <section class="section-foto">
                    <div class="container">
                        <div class="foto-wrapper">
                            <img src="images/foto_robby.jpg" alt="Foto Keluarga" class="foto-keluarga">
                        </div>
                    </div>
                </section>
                <h2 class="nama-anak"><?= $config['nama_anak'] ?></h2>
                <p class="nama-ortu">Putra ke-tiga dari<br><?= $config['nama_ayah'] ?> & <?= $config['nama_ibu'] ?></p>
                <div class="ornamen-bottom">❈</div>
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

        <!-- Section 4: Detail Acara -->
        <section class="section-acara">
            <div class="container">
                <h3 class="section-title">Detail Acara</h3>
                <div class="acara-card">
                    <div class="acara-item">
                        <div class="icon">📅</div>
                        <div class="acara-info">
                            <strong>Hari & Tanggal</strong>
                            <p><?= $config['hari_tanggal'] ?></p>
                        </div>
                    </div>
                    <div class="acara-item">
                        <div class="icon">🕐</div>
                        <div class="acara-info">
                            <strong>Waktu</strong>
                            <p><?= $config['waktu'] ?></p>
                        </div>
                    </div>
                    <div class="acara-item">
                        <div class="icon">📍</div>
                        <div class="acara-info">
                            <strong>Lokasi</strong>
                            <p><?= $config['lokasi'] ?></p>
                        </div>
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