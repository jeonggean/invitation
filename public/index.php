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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Landing Page -->
    <div id="landing" class="landing-page active">
        <div class="landing-overlay"></div>
        <div class="landing-content">
            <div class="foto-wrapper-landing">
                <div class="foto-frame">
                    <img src="images/foto_robby.jpg" alt="Foto Keluarga" class="foto-keluarga-landing">
                </div>
                <p class="caption-foto-landing">Keluarga Besar <?= $config['nama_ayah'] ?> & <?= $config['nama_ibu'] ?></p>
            </div>
            <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
            <h1 class="landing-title"><?= $config['judul'] ?></h1>
            <div class="kepada">
                <p>Kepada Yth:</p>
                <h2 class="nama-tamu"><?= $nama_tamu ?></h2>
            </div>
            <button onclick="bukaUndangan()" class="btn-buka">
                <span class="btn-text">Buka Undangan</span>
                <span class="btn-icon">📧</span>
            </button>
        </div>
        <div class="floating-particles">
            <span class="particle">✨</span>
            <span class="particle">🌟</span>
            <span class="particle">💫</span>
            <span class="particle">⭐</span>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="main-content">
        <!-- Section 1: Foto Keluarga -->
        <section class="section-foto">
            <div class="container">
                <div class="foto-wrapper reveal">
                    <div class="foto-border-wrapper">
                        <img src="images/foto_keluarga.jpg" alt="Foto Keluarga" class="foto-keluarga">
                    </div>
                    <p class="caption-foto">Keluarga Besar <?= $config['nama_ayah'] ?> & <?= $config['nama_ibu'] ?></p>
                </div>
            </div>
        </section>

        <!-- Section 2: Nama Anak -->
        <section class="section-nama reveal">
            <div class="container">
                <div class="card-minimalis">
                    <div class="foto-anak-wrapper">
                        <img src="images/foto_robby.jpg" alt="Foto Robby" class="foto-anak-side">
                        <div class="foto-overlay"></div>
                    </div>
                    <div class="info-anak-side">
                        <div class="ornamen-mini">❈</div>
                        <h2><?= $config['nama_anak'] ?></h2>
                        <p>
                            Putra ke-tiga dari:<br>
                            <?= $config['nama_ayah'] ?>
                            & <br> <?= $config['nama_ibu'] ?>
                        </p>
                        <div class="ornamen-mini bottom">❈</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Ayat/Doa -->
        <section class="section-ayat reveal">
            <div class="container">
                <div class="ayat-box">
                    <div class="ayat-ornamen top">☪</div>
                    <p class="ayat-arab"><?= $config['ayat']['teks_arab'] ?></p>
                    <p class="ayat-latin"><?= $config['ayat']['teks_latin'] ?></p>
                    <p class="ayat-keterangan"><?= $config['ayat']['keterangan'] ?></p>
                    <div class="ayat-ornamen bottom">☪</div>
                </div>
            </div>
        </section>

        <!-- Section 4: Detail Acara -->
        <section class="section-acara reveal">
            <div class="container">
                <div class="acara-container">
                    <div class="acara-card-new">
                        <div class="card-glow"></div>
                        <p>Save The Date</p>
                        <div class="divider"></div>
                        <h4><strong><?= $config['hari_tanggal'] ?></strong></h4>
                        <p class="waktu-detail"><?= $config['waktu'] ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 5: Lokasi Google Maps -->
        <section class="section-map reveal">
            <div class="container">
                <h3 class="section-title">
                    <span class="title-ornamen">❈</span>
                    Lokasi Acara
                    <span class="title-ornamen">❈</span>
                </h3>
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
                    <span>Buka di Google Maps</span>
                </a>
            </div>
        </section>

        <!-- Section 6: Wishes -->
        <section class="section-wishes reveal" id="wishes">
            <div class="container">
                <h3 class="section-title">
                    <span class="title-ornamen">❈</span>
                    Ucapan & Doa
                    <span class="title-ornamen">❈</span>
                </h3>
                
                <!-- Form -->
                <div class="wish-form-wrapper">
                    <form method="POST" class="wish-form">
                        <div class="form-group">
                            <label for="nama">
                                <span class="label-icon">👤</span> Nama Anda
                            </label>
                            <input type="text" name="nama" id="nama" placeholder="Masukkan nama Anda" required maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="pesan">
                                <span class="label-icon">💬</span> Ucapan & Doa
                            </label>
                            <textarea name="pesan" id="pesan" placeholder="Tuliskan ucapan dan doa Anda..." required rows="4"></textarea>
                        </div>
                        <button type="submit" name="submit_wish" class="btn-submit">
                            <span>Kirim Ucapan</span>
                            <span class="btn-submit-icon">✉️</span>
                        </button>
                    </form>
                </div>

                <!-- Daftar Ucapan -->
                <div class="wishes-list">
                    <?php if (empty($wishes)): ?>
                        <div class="no-wishes">
                            <span class="no-wishes-icon">💭</span>
                            <p>Belum ada ucapan. Jadilah yang pertama!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($wishes as $wish): ?>
                            <div class="wish-item">
                                <div class="wish-header">
                                    <span class="wish-avatar">👤</span>
                                    <strong class="wish-nama"><?= htmlspecialchars($wish['nama_pengirim']) ?></strong>
                                </div>
                                <p class="wish-pesan"><?= nl2br(htmlspecialchars($wish['pesan'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-ornamen">✦ ✧ ✦</div>
            <p class="footer-arab">بَارَكَ اللَّهُ</p>
            <p class="footer-text">Terima kasih atas doa dan ucapannya</p>
            <div class="footer-ornamen bottom">✦ ✧ ✦</div>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>