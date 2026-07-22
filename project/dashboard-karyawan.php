<?php
session_start();
include 'koneksi.php';

// Validasi otentikasi sesi pengguna sebelum memberikan izin akses ke halaman dashboard
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

// ------------------------------------------------------------------------
// LOGIKA PEMBARUAN DATA: MEMPROSES PERUBAHAN STATUS PESANAN VIA FORM POST
// ------------------------------------------------------------------------
if (isset($_POST['update_status']) && isset($_POST['id_pesanan']) && isset($_POST['status_baru'])) {
    $id_pesanan_update = $_POST['id_pesanan'];
    $status_baru = $_POST['status_baru'];
    
    try {
        // Menggunakan Prepared Statements untuk mencegah kerentanan SQL Injection
        $stmt_update = $koneksi->prepare("UPDATE tabel_pesanan SET status_pesanan = ? WHERE id_pesanan = ?");
        $stmt_update->execute([$status_baru, $id_pesanan_update]);
        
        // Memicu penyegaran halaman secara otomatis guna memperbarui antarmuka tabel data
        echo "<script>alert('Status pesanan berhasil diperbarui ke: $status_baru!'); window.location.href='dashboard-karyawan.php';</script>";
        exit;
    } catch(PDOException $e) {
        echo "<script>alert('Gagal memperbarui database: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// ------------------------------------------------------------------------
// LOGIKA ARSIP DATA: MEMPROSES PENSEMBUNYIAN DATA TRANSAKSI SELESAI
// ------------------------------------------------------------------------
if (isset($_POST['arsip_transaksi']) && isset($_POST['id_pesanan'])) {
    $id_pesanan_arsip = $_POST['id_pesanan'];
    
    try {
        // Cek/pastikan kolom is_archived ada di database Anda atau menambahkannya secara dinamis jika belum ada
        $koneksi->exec("ALTER TABLE tabel_pesanan ADD COLUMN is_archived INTEGER DEFAULT 0");
    } catch (PDOException $e) {
        // Abaikan error jika kolom sudah pernah dibuat sebelumnya
    }

    try {
        $stmt_archive = $koneksi->prepare("UPDATE tabel_pesanan SET is_archived = 1 WHERE id_pesanan = ?");
        $stmt_archive->execute([$id_pesanan_arsip]);
        
        echo "<script>alert('Transaksi berhasil diarsipkan!'); window.location.href='dashboard-karyawan.php';</script>";
        exit;
    } catch(PDOException $e) {
        echo "<script>alert('Gagal mengarsipkan data: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// ------------------------------------------------------------------------
// LOGIKA RESTORE DATA: MENGEMBALIKAN DATA DARI ARSIP KE DASHBOARD UTAMA
// ------------------------------------------------------------------------
if (isset($_POST['restore_transaksi']) && isset($_POST['id_pesanan'])) {
    $id_pesanan_restore = $_POST['id_pesanan'];
    
    try {
        $stmt_restore = $koneksi->prepare("UPDATE tabel_pesanan SET is_archived = 0 WHERE id_pesanan = ?");
        $stmt_restore->execute([$id_pesanan_restore]);
        
        echo "<script>alert('Transaksi berhasil dikembalikan ke data aktif!'); window.location.href='dashboard-karyawan.php';</script>";
        exit;
    } catch(PDOException $e) {
        echo "<script>alert('Gagal mengembalikan data: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// Cek apakah user sedang membuka halaman filter arsip atau halaman data aktif
$view_archive = isset($_GET['view']) && $_GET['view'] === 'archive';

// ------------------------------------------------------------------------
// RETRIEVAL DATA: MENARIK DATA TRANSAKSI SESUAI VIEW FILTER
// ------------------------------------------------------------------------
try {
    if ($view_archive) {
        // Jika mode arsip aktif, ambil yang bernilai 1
        $stmt = $koneksi->query("SELECT * FROM tabel_pesanan WHERE is_archived = 1 ORDER BY id_pesanan DESC");
    } else {
        // Mode normal, ambil data aktif (0 atau NULL)
        $stmt = $koneksi->query("SELECT * FROM tabel_pesanan WHERE is_archived = 0 OR is_archived IS NULL ORDER BY id_pesanan DESC");
    }
    $semua_pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Fallback jika query filter gagal karena kolom belum siap di database lama Anda
    try {
        $stmt = $koneksi->query("SELECT * FROM tabel_pesanan ORDER BY id_pesanan DESC");
        $semua_pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        $semua_pesanan = [];
    }
}

// Inisialisasi variabel akumulator untuk kompilasi data statistik pada Dashboard
$antrean_baru = 0;
$sedang_cuci = 0;
$siap_delivery = 0;
$kas_keuangan = 0;

// Iterasi data statis keuangan & antrean harus selalu dihitung dari total seluruh data (termasuk yang diarsipkan agar balance)
try {
    $stmt_stats = $koneksi->query("SELECT * FROM tabel_pesanan");
    $semua_data_stats = $stmt_stats->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $semua_data_stats = $semua_pesanan;
}

foreach ($semua_data_stats as $p) {
    $stat = strtolower(trim($p['status_pesanan']));
    
    if ($stat == 'menunggu konfirmasi') {
        $antrean_baru++;
    } elseif ($stat == 'sedang dicuci' || $stat == 'diproses') {
        $sedang_cuci++;
    } elseif ($stat == 'selesai' || $stat == 'selesai cuci') {
        $siap_delivery++;
    }

    if ($stat != 'belum bayar' && $stat != '') {
        $paket = trim($p['paket_treatment']);
        if ($paket == 'Fast Clean') {
            $kas_keuangan += 20000;
        } elseif ($paket == 'Deep Clean') {
            $kas_keuangan += 25000;
        } elseif ($paket == 'Express Clean') {
            $kas_keuangan += 45000;
        }
    }
}
?>
<!doctype html>
<html lang="id" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Clean | Dashboard Kerja Karyawan</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="./src/css/tailwind.css" />
    
    <script src="assets/js/wow.min.js"></script>
    <script>
      new WOW().init();
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: "class" }</script>
  </head>

  <body class="bg-white text-gray-800 dark:bg-slate-900 dark:text-gray-100">
    
    <header class="absolute top-0 left-0 z-40 flex items-center w-full bg-transparent ud-header">
      <div class="container px-4 mx-auto">
        <div class="relative flex items-center justify-between -mx-4">
          <div class="max-w-full px-4 w-60">
            <a href="dashboard-karyawan.php" class="py-5 text-2xl font-bold text-white navbar-logo flex items-center gap-2">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
              <span>The Clean.</span>
            </a>
          </div>
          <div class="flex items-center justify-between w-full px-4">
            <div>
              <button
                id="navbarToggler"
                class="absolute right-4 top-1/2 block -translate-y-1/2 rounded-lg px-3 py-[6px] ring-primary focus:ring-2 lg:hidden"
              >
                <span class="relative my-[6px] block h-[2px] w-[30px] bg-white ud-menu-toggle"></span>
                <span class="relative my-[6px] block h-[2px] w-[30px] bg-white ud-menu-toggle"></span>
                <span class="relative my-[6px] block h-[2px] w-[30px] bg-white ud-menu-toggle"></span>
              </button>
              <nav
                id="navbarCollapse"
                class="absolute right-4 top-full hidden w-full max-w-[250px] rounded-lg bg-white py-5 shadow-lg lg:static lg:block lg:w-full lg:max-w-full lg:bg-transparent lg:shadow-none xl:px-6 dark:bg-slate-800 lg:dark:bg-transparent"
              >
                <ul class="block lg:flex 2xl:ml-20">
                  <li class="relative group">
                    <a href="dashboard-karyawan.php#overview" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Beranda</a>
                  </li>
                  <li class="relative group">
                    <a href="dashboard-karyawan.php#orders" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Data Pesanan</a>
                  </li>
                  <li class="relative group">
                    <a href="dashboard-karyawan.php?view=archive#orders" class="flex py-2 mx-8 text-base font-medium <?= $view_archive ? 'text-amber-500 lg:text-amber-300 font-bold' : 'text-dark dark:text-white lg:text-white' ?> lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Arsip Pesanan</a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="hidden sm:flex items-center justify-end pr-16 lg:pr-0 gap-4">
              <span id="roleBadge" class="text-sm font-semibold text-white px-3 py-1 bg-white/20 rounded-md transition-colors duration-200">Karyawan Toko</span>
              <a id="logoutBtn" href="logout.php" class="px-5 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white hover:text-red-600 rounded-md transition-all duration-300 theme-signin">Keluar</a>
              
              <button id="themeToggler" class="flex h-9 w-9 items-center justify-center rounded-full text-white hover:bg-white/10 transition-colors" aria-label="theme toggler">
                <svg id="sunIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 11-2 0V3a1 1 0 011-1zm4.243 3.05a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-3.05 4.243a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414zM10 14a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.243-3.05a1 1 0 010 1.414l.707-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0zM3 10a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zm3.05-4.243a1 1 0 010 1.414l-.707.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0zM10 5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"></path>
                </svg>
                <svg id="moonIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section id="overview" class="relative overflow-hidden bg-blue-600 pt-[120px] pb-[60px] md:pt-[130px] lg:pt-[160px] z-10">
      <div class="absolute left-0 top-0 -z-10 h-80 w-80 rounded-full bg-gradient-to-br from-blue-400/20 to-indigo-500/30 blur-3xl pointer-events-none"></div>
      <div class="absolute right-0 bottom-0 -z-10 h-[350px] w-[350px] rounded-full bg-gradient-to-tr from-cyan-400/20 to-blue-400/20 blur-3xl pointer-events-none"></div>
      
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap items-center -mx-4">
          <div class="w-full px-4">
            <div class="hero-content mx-auto max-w-[780px] text-center wow fadeInUp" data-wow-delay=".2s">
              <h1 class="mb-4 text-3xl font-bold leading-snug text-white sm:text-4xl lg:text-5xl">
                Panel Kerja Karyawan
              </h1>
              <p class="mx-auto mb-6 max-w-[600px] text-base font-medium text-blue-100">
                Sistem Terkomputerisasi Terintegrasi. Pemrosesan Antrean Perawatan Sepatu, Alur Pelacakan Logistik Mandiri, dan Pencatatan Kasir.
              </p>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8 max-w-5xl mx-auto wow fadeInUp" data-wow-delay=".3s">
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Antrean Baru</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?= $antrean_baru ?> Pesanan</h3>
          </div>
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-500 dark:text-blue-400 uppercase">Sedang Cuci</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?= $sedang_cuci ?> Pasang</h3>
          </div>
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-500 dark:text-blue-300 uppercase">Selesai / Delivery</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?= $siap_delivery ?> Layanan</h3>
          </div>
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-500 dark:text-blue-400 uppercase">Kas Keuangan</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">Rp <?= number_format($kas_keuangan, 0, ',', '.') ?></h3>
          </div>
        </div>
      </div>
    </section>

    <section id="orders" class="pt-16 pb-20 dark:bg-slate-900 bg-gray-50">
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap -mx-4">
          <div class="w-full px-4">
            <div class="mx-auto mb-10 max-w-[600px] text-center wow fadeInUp" data-wow-delay=".1s">
              <span class="block mb-2 text-lg font-semibold text-blue-600">Alur Kerja Aktivitas</span>
              <h2 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl">
                  <?= $view_archive ? 'Arsip Manajemen Transaksi' : 'Manajemen Kerja & Transaksi' ?>
              </h2>
              <p class="text-base text-gray-600 dark:text-gray-300">
                  <?= $view_archive ? 'Menampilkan list seluruh pesanan lampau yang telah selesai dan diarsipkan dari sistem utama.' : 'Kelola pembaruan status pembersihan, pelacakan penjemputan/pengantaran, dan konfirmasi kasir keuangan.' ?>
              </p>
              <?php if($view_archive): ?>
                 <a href="dashboard-karyawan.php#orders" class="mt-3 inline-block px-4 py-1.5 bg-blue-600 text-white rounded-md font-semibold text-xs hover:bg-blue-700 shadow transition-all">← Kembali ke Data Aktif</a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="w-full max-w-6xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-lg border dark:border-slate-700 overflow-hidden wow fadeInUp" data-wow-delay=".2s">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 text-sm font-semibold border-b dark:border-slate-600">
                  <th class="p-4">No. Transaksi</th>
                  <th class="p-4">Pelanggan & Item</th>
                  <th class="p-4">Paket Perawatan</th>
                  <th class="p-4 text-center">Status Proses</th>
                  <th class="p-4 text-center">Metode Logistik</th>
                  <th class="p-4 text-right">Total Biaya</th>
                  <th class="p-4 text-center">Tindakan Kerja</th>
                </tr>
              </thead>
              <tbody class="text-sm divide-y divide-gray-100 dark:divide-slate-700 text-gray-600 dark:text-gray-300">
                
                <?php if(empty($semua_pesanan)): ?>
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-500">
                        <?= $view_archive ? 'Belum ada data transaksi yang diarsipkan.' : 'Belum ada data pesanan transaksi aktif di dalam sistem.' ?>
                    </td>
                </tr>
                <?php else: ?>
                    
                    <?php foreach ($semua_pesanan as $row): 
                        $id = $row['id_pesanan'];
                        $invoice_table = "TC-2026-" . str_pad($id, 3, '0', STR_PAD_LEFT);
                        $nama = htmlspecialchars($row['nama_pemilik'] ?? 'Customer');
                        $sepatu = htmlspecialchars($row['merek_sepatu'] ?? '-');
                        $paket = htmlspecialchars($row['paket_treatment']);
                        $logistik = htmlspecialchars($row['opsi_logistik'] ?? 'Ambil Sendiri');
                        
                        $status_db = $row['status_pesanan'] ? trim($row['status_pesanan']) : 'Belum Bayar';
                        $status_cek = strtolower($status_db);

                        $bukti_tf = isset($row['bukti_pembayaran']) ? htmlspecialchars($row['bukti_pembayaran']) : '';

                        // Perhitungan Biaya Transaksi Berdasarkan Jenis Layanan Paket Perawatan
                        $biaya = 0;
                        if ($paket == 'Fast Clean') $biaya = 20000;
                        elseif ($paket == 'Deep Clean') $biaya = 25000;
                        elseif ($paket == 'Express Clean') $biaya = 45000;

                        // Manajemen Visual Badge Pewarnaan Status Kerja Sistem Operasional
                        $badge_class = "bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200";
                        if ($status_cek == 'menunggu konfirmasi') {
                            $badge_class = "bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300";
                        } elseif ($status_cek == 'sedang dijemput' || $status_cek == 'sedang diantar') {
                            $badge_class = "bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300";
                        } elseif ($status_cek == 'sedang dicuci' || $status_cek == 'diproses') {
                            $badge_class = "bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300";
                        } elseif ($status_cek == 'selesai' || $status_cek == 'selesai cuci') {
                            $badge_class = "bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300";
                        } elseif ($status_cek == 'belum bayar') {
                            $badge_class = "bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300";
                        }

                        // Algoritma Pemisahan String Alamat Fisik Manual dan Tautan Koordinat Geolocation
                        $raw_alamat = $row['alamat_penjemputan'] ?? '';
                        $alamat_tampil = $raw_alamat;
                        $link_gmaps = '';

                        if (!empty($raw_alamat) && strpos($raw_alamat, ' [MAPS_URL] ') !== false) {
                            $pecah_alamat = explode(' [MAPS_URL] ', $raw_alamat);
                            $alamat_tampil = $pecah_alamat[0]; // Subsring 1: Data Alamat Patokan Manual
                            $link_gmaps = $pecah_alamat[1];    // Substring 2: Tautan Rute Integrasi Google Maps API
                        }
                    ?>
                    <tr class="align-middle">
                      <td class="p-4 font-mono font-bold text-blue-600 dark:text-blue-400"><?= $invoice_table ?></td>
                      <td class="p-4 max-w-xs">
                        <p class="font-bold text-gray-800 dark:text-white text-base"><?= $nama ?></p>
                        <p class="text-xs text-gray-500 font-medium mb-1">👟 <?= $sepatu ?></p>
                        
                        <?php if(strtolower($logistik) == 'kurir pick-up' || strtolower($logistik) == 'antar jemput'): ?>
                            <div class="text-xs text-slate-700 dark:text-gray-300 font-normal bg-slate-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 p-3 rounded-lg mt-2 space-y-1.5 shadow-inner">
                              <span class="font-semibold text-purple-600 dark:text-purple-400 block flex items-center gap-1">📍 Detail Alamat Penjemputan:</span>
                              <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                 <?= htmlspecialchars($alamat_tampil ?: 'Alamat deskripsi fisik kosong.') ?>
                              </p>
                              
                              <?php if (!empty($link_gmaps)): ?>
                                  <a href="<?= htmlspecialchars($link_gmaps) ?>" target="_blank" class="mt-2 inline-flex items-center gap-1 px-2 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-bold rounded shadow transition-all duration-200 whitespace-nowrap">
                                      🗺️ Buka Rute Live Navigasi
                                  </a>
                              <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($bukti_tf)): ?>
                            <a href="uploads/<?= $bukti_tf ?>" target="_blank" class="inline-block mt-2 text-xs text-blue-500 hover:text-blue-700 underline font-medium">Cek Bukti Pembayaran Digital</a>
                        <?php endif; ?>
                      </td>
                      <td class="p-4 font-medium"><?= $paket ?></td>
                      
                      <td class="p-4 text-center whitespace-nowrap">
                        <span class="px-3 py-1 <?= $badge_class ?> text-xs font-bold rounded-full tracking-wide uppercase"><?= strtoupper($status_db) ?></span>
                      </td>
                      <td class="p-4 text-center whitespace-nowrap">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 text-xs font-medium rounded-md"><?= $logistik ?></span>
                      </td>
                      <td class="p-4 text-right whitespace-nowrap">
                        <p class="font-semibold text-gray-800 dark:text-white">Rp <?= number_format($biaya, 0, ',', '.') ?></p>
                        <?php if($status_cek == 'belum bayar'): ?>
                            <span class="text-xs text-red-500 font-medium dark:text-red-400">Belum Lunas</span>
                        <?php else: ?>
                            <span class="text-xs text-emerald-500 font-medium dark:text-emerald-400">Terbayar</span>
                        <?php endif; ?>
                      </td>
                      
                      <td class="p-4 text-center whitespace-nowrap">
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-1.5">
                          <form method="POST" action="" class="inline-block w-full sm:w-auto">
                              <input type="hidden" name="id_pesanan" value="<?= $id ?>">
                              
                              <?php if ($view_archive): ?>
                                  <button type="submit" name="restore_transaksi" class="w-full px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded transition shadow-sm">
                                      Buka Arsip ↩
                                  </button>
                                  
                              <?php elseif ($status_cek == 'menunggu konfirmasi'): ?>
                                  <?php if(strtolower($logistik) == 'kurir pick-up' || strtolower($logistik) == 'antar jemput'): ?>
                                      <input type="hidden" name="status_baru" value="Sedang Dijemput">
                                      <button type="submit" name="update_status" class="w-full px-3 py-1.5 bg-purple-500 hover:bg-purple-600 text-white text-xs font-semibold rounded transition shadow-sm">Pergi Jemput Sepatu</button>
                                  <?php else: ?>
                                      <input type="hidden" name="status_baru" value="Sedang Dicuci">
                                      <button type="submit" name="update_status" class="w-full px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded transition shadow-sm">Verifikasi & Cuci</button>
                                  <?php endif; ?>
                              
                              <?php elseif ($status_cek == 'sedang dijemput'): ?>
                                  <input type="hidden" name="status_baru" value="Sedang Dicuci">
                                  <button type="submit" name="update_status" class="w-full px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition shadow-sm">Sudah Sampai Toko (Cuci)</button>

                              <?php elseif ($status_cek == 'sedang dicuci' || $status_cek == 'diproses'): ?>
                                  <input type="hidden" name="status_baru" value="Selesai Cuci">
                                  <button type="submit" name="update_status" class="w-full px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded transition shadow-sm">Selesai Cuci</button>
                              
                              <?php elseif ($status_cek == 'selesai cuci'): ?>
                                  <?php if(strtolower($logistik) == 'kurir pick-up' || strtolower($logistik) == 'antar jemput'): ?>
                                      <input type="hidden" name="status_baru" value="Sedang Diantar">
                                      <button type="submit" name="update_status" class="w-full px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-semibold rounded transition shadow-sm">Pergi Antar Sepatu</button>
                                  <?php else: ?>
                                      <input type="hidden" name="status_baru" value="Selesai">
                                      <button type="submit" name="update_status" class="w-full px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition shadow-sm">Sudah Diambil Pelanggan</button>
                                  <?php endif; ?>

                              <?php elseif ($status_cek == 'sedang diantar'): ?>
                                  <input type="hidden" name="status_baru" value="Selesai">
                                  <button type="submit" name="update_status" class="w-full px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition shadow-sm">Selesai Diserahkan</button>

                              <?php elseif ($status_cek == 'selesai'): ?>
                                  <button type="submit" name="arsip_transaksi" onclick="return confirm('Arsipkan pesanan ini? Data tidak akan tampil lagi di dashboard utama.')" class="w-full px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded transition shadow-sm">
                                      Arsipkan Transaksi ✓
                                  </button>
                              
                              <?php else: ?>
                                  <span class="text-xs text-gray-400 dark:text-gray-500 italic block">Menunggu User</span>
                              <?php endif; ?>
                          </form>
                          
                          <a href="cetak-label.php?id=<?= $id ?>" target="_blank" class="w-full sm:w-auto px-3 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-medium rounded transition shadow-sm border dark:border-slate-600 flex items-center justify-center gap-1">🖨️ Cetak Label</a>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <footer class="relative z-10 bg-[#090E34] pt-12 text-gray-300 overflow-hidden">
      <div class="absolute bottom-0 left-0 -z-10 h-72 w-72 rounded-full bg-gradient-to-tr from-blue-500/10 to-cyan-400/20 blur-3xl pointer-events-none"></div>
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap -mx-4 justify-between items-center pb-8">
          <div class="w-full px-4 lg:w-4/12 md:w-1/2 wow fadeInUp" data-wow-delay=".1s">
            <div class="mb-4 text-2xl font-bold text-white flex items-center gap-2">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
              <span>The Clean.</span>
            </div>
            <p class="text-sm text-[#959CB1]">
              Sistem Otomasi Operasional Kerja & Manajemen Toko Sepatu.
            </p>
          </div>
          <div class="w-full px-4 lg:w-4/12 text-right md:w-1/2 mt-4 md:mt-0">
            <span class="text-xs text-[#959CB1]">The Clean Cleaning Shoes and Care</span>
          </div>
        </div>
      </div>

      <div class="border-t border-white border-opacity-10 py-6 bg-[#0b113c]">
        <div class="container px-4 mx-auto text-center">
          <p class="text-sm text-[#959CB1]">
            &copy; 2026 The Clean Cleaning Shoes and Care - Kelompok 2. All rights reserved.
          </p>
        </div>
      </div>
    </footer>

    <script>
      window.onscroll = function () {
        const ud_header = document.querySelector(".ud-header");
        const logo = document.querySelector(".navbar-logo");
        const navLinks = ud_header.querySelectorAll("nav ul li a");
        const themeToggler = document.getElementById('themeToggler');
        const menuToggle = ud_header.querySelectorAll(".ud-menu-toggle");
        
        const roleBadge = document.getElementById("roleBadge");
        const logoutBtn = document.getElementById("logoutBtn");

        if (window.pageYOffset > 50) {
          ud_header.classList.remove("absolute", "bg-transparent");
          ud_header.classList.add("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50", "transition-all", "duration-200");
          
          logo.classList.remove("text-white");
          logo.classList.add("text-blue-600", "dark:text-white");

          themeToggler.classList.remove("text-white");
          themeToggler.classList.add("text-gray-800", "dark:text-white");

          roleBadge.classList.remove("text-white", "bg-white/20");
          roleBadge.classList.add("text-gray-800", "bg-gray-100", "dark:text-white", "dark:bg-slate-800");

          logoutBtn.className = "px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-all duration-300 theme-signin";

          menuToggle.forEach(toggle => {
            toggle.classList.remove("bg-white");
            toggle.classList.add("bg-dark", "dark:bg-white");
          });

          navLinks.forEach(link => {
            if(!link.classList.contains('text-amber-500') && !link.classList.contains('lg:text-amber-300')) {
               link.classList.remove("lg:text-white");
               link.classList.add("text-gray-800", "dark:text-gray-200");
            }
          });
        } else {
          ud_header.classList.remove("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50");
          ud_header.classList.add("absolute", "bg-transparent");
          
          logo.classList.remove("text-blue-600", "dark:text-white");
          logo.classList.add("text-white");

          themeToggler.classList.remove("text-gray-800", "dark:text-white");
          themeToggler.classList.add("text-white");

          roleBadge.classList.remove("text-gray-800", "bg-gray-100", "dark:text-white", "dark:bg-slate-800");
          roleBadge.classList.add("text-white", "bg-white/20");

          logoutBtn.className = "px-5 py-2 text-sm font-medium text-white bg-white/20 hover:bg-white hover:text-red-600 rounded-md transition-all duration-300 theme-signin";

          menuToggle.forEach(toggle => {
            toggle.classList.remove("bg-dark", "dark:bg-white");
            toggle.classList.add("bg-white");
          });

          navLinks.forEach(link => {
            if(!link.classList.contains('text-amber-500') && !link.classList.contains('lg:text-amber-300')) {
               link.classList.remove("text-gray-800", "dark:text-gray-200");
               link.classList.add("lg:text-white");
            }
          });
        }
      };

      const navbarToggler = document.querySelector("#navbarToggler");
      const navbarCollapse = document.querySelector("#navbarCollapse");

      navbarToggler.addEventListener("click", () => {
        navbarToggler.classList.toggle("navbarTogglerActive");
        navbarCollapse.classList.toggle("hidden");
      });

      document.querySelectorAll("#navbarCollapse ul li a").forEach((e) =>
        e.addEventListener("click", () => {
          navbarToggler.classList.remove("navbarTogglerActive");
          navbarCollapse.classList.add("hidden");
        })
      );

      const themeToggler = document.getElementById('themeToggler');
      const sunIcon = document.getElementById('sunIcon');
      const moonIcon = document.getElementById('moonIcon');

      if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
      } else {
        document.documentElement.classList.remove('dark');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
      }

      themeToggler.addEventListener('click', () => {
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('theme', 'light');
          sunIcon.classList.add('hidden');
          moonIcon.classList.remove('hidden');
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('theme', 'dark');
          sunIcon.classList.remove('hidden');
          moonIcon.classList.add('hidden');
        }
      });
    </script>
  </body>
</html>