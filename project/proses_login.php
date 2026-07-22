<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        header("Location: signin.php?pesan=kosong");
        exit();
    }

    try {
        $query = $koneksi->prepare("SELECT * FROM tabel_user WHERE email = :email AND password = :password");
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $password);
        $query->execute();
        
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['login'] = true;
            $_SESSION['email'] = $user['email']; // <-- INI KUNCI SAKTI YANG TADI HILANG!
            $_SESSION['nama_user'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];

            // PENGALIHAN FILE YANG BENER (SEMUA DI ROOT)
            if ($user['role'] === 'pemilik') {
                header("Location: dashboard-pemilik.php"); 
                exit();
            } elseif ($user['role'] === 'karyawan') {
                header("Location: dashboard-karyawan.php");
                exit();
            } else {
                // KHUSUS PELANGGAN: Buka index.php dulu atau langsung ke pesan-layanan.php boleh
                header("Location: index.php");
                exit();
            }
        } else {
            header("Location: signin.php?pesan=gagal");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: signin.php?pesan=error");
        exit();
    }
} else {
    header("Location: signin.php");
    exit();
}
?>