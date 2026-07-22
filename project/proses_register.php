<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = isset($_POST['nama']) ? $_POST['nama'] : 'Pelanggan Baru'; 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'pelanggan'; // Dikunci otomatis jadi pelanggan!

    try {
        $query = $koneksi->prepare("INSERT INTO tabel_user (nama_lengkap, email, password, role) VALUES (:nama, :email, :password, :role)");
        $query->bindParam(':nama', $nama);
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $password);
        $query->bindParam(':role', $role);
        $query->execute();

        echo "<script>alert('Registrasi Pelanggan Berhasil! Silahkan Login.'); window.location='signin.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Registrasi Gagal! Email sudah terdaftar.'); window.location='signup.php';</script>";
    }
}
?>