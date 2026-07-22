<?php
session_start();

// Hubungkan koneksi database (Menggunakan PDO sesuai dengan koneksi di konfirmasi-pembayaran.php)
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $invoice_id = $_POST['invoice_id'];
    $nama_pengirim = $_POST['nama_pengirim'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $nominal = $_POST['nominal'];
    
    // Logika handling upload file gambar bukti transfer
    $nama_file = $_FILES['bukti_tf']['name'];
    $tmp_name = $_FILES['bukti_tf']['tmp_name'];
    
    // Tentukan folder penyimpanan gambar bukti transfer
    $target_dir = "uploads/";
    
    // Jika folder 'uploads' belum dibuat di direktori projek lu, PHP otomatis akan membuatnya
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    // Menambahkan timestamp pada nama file agar nama file tidak bentrok jika kembar
    $nama_file_baru = time() . "_" . basename($nama_file);
    $target_file = $target_dir . $nama_file_baru;
    
    if (move_uploaded_file($tmp_name, $target_file)) {
        
        try {
            // --- QUERY UPDATE STATUS DATABASE UTK KELOMPOK LU ---
            // Mengubah status_pesanan menjadi 'Menunggu Konfirmasi' di tabel_pesanan berdasarkan id_pesanan (invoice_id)
            $stmt = $koneksi->prepare("UPDATE tabel_pesanan SET status_pesanan = 'Menunggu Konfirmasi' WHERE id_pesanan = ?");
            $stmt->execute([$invoice_id]);
            
            // Alert sukses pop-up profesional & langsung lempar ke riwayat-pesanan.php biar bisa tes tombol Lacak Status
            echo "<script>
                alert('Sukses! Konfirmasi pembayaran Anda berhasil dikirim. Silakan tunggu verifikasi admin.');
                window.location.href = 'riwayat-pesanan.php';
            </script>";
            exit();

        } catch (PDOException $e) {
            // Jika query database crash atau error
            echo "<script>
                alert('Gagal update status database: " . addslashes($e->getMessage()) . "');
                window.location.href = 'konfirmasi-pembayaran.php';
            </script>";
            exit();
        }

    } else {
        echo "<script>
            alert('Maaf, gagal mengunggah file bukti pembayaran. Pastikan ukuran file tidak terlalu besar.');
            window.location.href = 'konfirmasi-pembayaran.php';
        </script>";
    }
} else {
    header("Location: konfirmasi-pembayaran.php");
    exit();
}
?>