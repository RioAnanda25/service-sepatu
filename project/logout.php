<?php
// Mulai session yang lagi aktif
session_start();

// Bersihkan semua variabel session
$_SESSION = array();

// Hancurkan session dari sistem server
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Tendang balik dengan selamat ke halaman login utama
header("Location: signin.php");
exit();
?>