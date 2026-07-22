<?php
date_default_timezone_set('Asia/Jakarta');
$db_file = __DIR__ . '/database_sepatu.db';

try {
    $koneksi = new PDO("sqlite:" . $db_file);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. BIKIN TABEL USER
    $koneksi->exec("CREATE TABLE IF NOT EXISTS tabel_user (
        id_user INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_lengkap TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'pelanggan'
    )");

    // 2. SUNTIK DATA AKUN DUMMY (Aman anti error)
    $koneksi->exec("INSERT OR IGNORE INTO tabel_user (nama_lengkap, email, password, role) 
                    VALUES ('Pemilik', 'pemilik@gmail.com', 'pemilik123', 'pemilik')");
                    
    $koneksi->exec("INSERT OR IGNORE INTO tabel_user (nama_lengkap, email, password, role) 
                    VALUES ('Karyawan', 'karyawan@gmail.com', 'karyawan123', 'karyawan')");

    // 3. BIKIN TABEL PESANAN BARU (Tempat nyimpen orderan)
    $koneksi->exec("CREATE TABLE IF NOT EXISTS tabel_pesanan (
        id_pesanan INTEGER PRIMARY KEY AUTOINCREMENT,
        email_pelanggan TEXT NOT NULL,
        nama_pemilik TEXT NOT NULL,
        merek_sepatu TEXT NOT NULL,
        paket_treatment TEXT NOT NULL,
        opsi_logistik TEXT NOT NULL,
        alamat_penjemputan TEXT NOT NULL,
        status_pesanan TEXT NOT NULL DEFAULT 'Menunggu Konfirmasi',
        tanggal_order DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

} catch(PDOException $e) {
    die("<h3 style='color:red;'>KONEKSI GAGAL: " . $e->getMessage() . "</h3>
         <p><b>SOLUSI:</b> Folder lu dilarang nulis file sama Windows. Pindahin folder <b>the_clean</b> dari Downloads ke <b>C:\xampp\htdocs\</b> atau <b>D:\</b> sekarang!</p>");
}
?>