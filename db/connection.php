<?php
// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'robby');

// Koneksi database
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Fungsi untuk menyimpan ucapan
function saveWish($nama, $pesan) {
    $conn = getConnection();
    
    $nama = $conn->real_escape_string($nama);
    $pesan = $conn->real_escape_string($pesan);
    
    $sql = "INSERT INTO wishes (nama_pengirim, pesan, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nama, $pesan);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Fungsi untuk mengambil semua ucapan
function getWishes() {
    $conn = getConnection();
    
    $sql = "SELECT nama_pengirim, pesan, created_at FROM wishes ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $wishes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $wishes[] = $row;
        }
    }
    
    $conn->close();
    return $wishes;
}
?>