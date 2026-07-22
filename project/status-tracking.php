<?php
session_start();
include 'koneksi.php'; // PASTIIN KONEKSI DI-INCLUDE YA BANG

$nama_user = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Pelanggan';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

// Nilai default buat jaga-jaga kalau ID gaada
$invoice = "Belum Ada Data";
$paket_sepatu = "-";
$status_db = "Menunggu Konfirmasi";
$tgl_pesan = date('d M Y - H:i') . ' WIB';

$ada_pesanan = false; // Flag tambahan untuk mengecek validitas data pesanan

// Ambil data pesanan dari DB berdasarkan ID dari URL riwayat-pesanan
if (!empty($order_id)) {
    try {
        $stmt = $koneksi->prepare("SELECT * FROM tabel_pesanan WHERE id_pesanan = ?");
        $stmt->execute([$order_id]);
        $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pesanan) {
            $ada_pesanan = true; // Data ditemukan
            $invoice = "ORD-2026-" . str_pad($pesanan['id_pesanan'], 4, '0', STR_PAD_LEFT);
            $paket_sepatu = $pesanan['merek_sepatu'] . " - " . $pesanan['paket_treatment'];
            $status_db = $pesanan['status_pesanan'];
            if(isset($pesanan['tanggal_order'])) {
                $tgl_pesan = $pesanan['tanggal_order'];
            }
        }
    } catch (PDOException $e) {}
}

// LOGIKA SHOPEE: Tentukan level status
$level = 1;
$status_lower = strtolower($status_db);

if (strpos($status_lower, 'selesai') !== false || strpos($status_lower, 'diambil') !== false) {
    $level = 4;
} elseif (strpos($status_lower, 'cuci') !== false || strpos($status_lower, 'proses') !== false || strpos($status_lower, 'progress') !== false) {
    $level = 3;
} elseif (strpos($status_lower, 'kurir') !== false || strpos($status_lower, 'dijemput') !== false || strpos($status_lower, 'antrean') !== false) {
    $level = 2;
} else {
    $level = 1; // Default
}
?>
<!doctype html>
<html lang="id" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Status Tracking | The Clean - Sistem Informasi Service Sepatu</title>
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
            <div class="py-5 text-2xl font-bold text-white navbar-logo flex items-center gap-2 cursor-default select-none">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
              <span>The Clean.</span>
            </div>
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
                <ul class="block lg:flex lg:items-center 2xl:ml-20">
                  <li class="relative group">
                    <a href="pesan-layanan.php" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200 whitespace-nowrap">Pesan Layanan</a>
                  </li>
                  <li class="relative group">
                    <a href="riwayat-pesanan.php" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200 whitespace-nowrap">Riwayat Pesanan</a>
                  </li>
                  <li class="relative group">
                    <a href="#" class="flex py-2 mx-8 text-base font-medium text-blue-600 lg:text-blue-400 dark:text-blue-400 lg:py-6 transition-colors duration-200 whitespace-nowrap">Status Tracking</a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="hidden sm:flex items-center justify-end pr-16 lg:pr-0 gap-4">
              <span class="text-white text-base font-medium hidden lg:inline" id="userGreeting">Halo, <?php echo htmlspecialchars($nama_user); ?></span>
              <a href="logout.php" class="px-6 py-2 text-base font-medium text-white duration-300 ease-in-out rounded-md bg-white/20 hover:bg-white/100 hover:text-red-600 theme-logout">Keluar</a>
              
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

    <section class="relative overflow-hidden bg-blue-600 pt-[120px] pb-[60px] md:pt-[130px] lg:pt-[160px] z-10">
      <div class="absolute left-0 top-0 -z-10 h-80 w-80 rounded-full bg-gradient-to-br from-teal-400/20 to-indigo-500/30 blur-3xl pointer-events-none"></div>
      <div class="absolute right-0 bottom-0 -z-10 h-[350px] w-[350px] rounded-full bg-gradient-to-tr from-cyan-400/20 to-emerald-400/20 blur-3xl pointer-events-none"></div>
      
      <div class="container px-4 mx-auto">
        <div class="hero-content mx-auto max-w-[780px] text-center wow fadeInUp" data-wow-delay=".2s">
          <h1 class="mb-2 text-3xl font-bold leading-snug text-white sm:text-4xl lg:text-5xl">Dashboard Layanan Mandiri</h1>
          <p class="mx-auto text-base font-medium text-blue-100 sm:text-lg max-w-[600px]">Kelola transaksi pengerjaan cuci sepatu Anda mulai dari pemesanan, pemantauan status antrean, hingga upload konfirmasi pembayaran.</p>
        </div>
      </div>
    </section>

    <section id="tracking" class="pt-16 pb-20 dark:bg-slate-900">
      <div class="container px-4 mx-auto">
        <div class="max-w-[800px] mx-auto bg-white dark:bg-slate-800 rounded-xl p-8 shadow-md border dark:border-slate-700 wow fadeInUp" data-wow-delay=".2s">
          
          <?php if ($ada_pesanan) : ?>
            <div class="mb-6 flex items-center gap-3 border-b dark:border-slate-700 pb-4">
              <div class="flex items-center justify-center w-[50px] h-[50px] bg-blue-600 rounded-xl p-3 shadow-lg shadow-blue-500/50 dark:shadow-blue-500/30">
                <img src="https://cdn-icons-png.flaticon.com/128/754/754276.png" alt="Icon Status Tracking" class="w-full h-full object-contain brightness-0 invert" />
              </div>
              <h2 class="text-2xl font-bold text-dark dark:text-white">Status Realtime Tracking Antrean</h2>
            </div>
            
            <div class="p-4 bg-blue-50 dark:bg-slate-700/50 rounded-lg mb-8 flex flex-col sm:flex-row justify-between gap-4 text-sm">
              <div>
                <span class="text-gray-500 dark:text-gray-400 block">Kode Invoice Transaksi</span>
                <strong class="text-base text-blue-600 dark:text-blue-400"><?php echo $invoice; ?></strong>
              </div>
              <div>
                <span class="text-gray-500 dark:text-gray-400 block">Nama Paket & Sepatu</span>
                <strong class="text-gray-800 dark:text-gray-200 block"><?php echo htmlspecialchars($paket_sepatu); ?></strong>
              </div>
              <div class="sm:text-right">
                <span class="text-gray-500 dark:text-gray-400 block">Status Saat Ini</span>
                <span class="inline-block px-2.5 py-1 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 font-semibold text-xs mt-1 uppercase"><?php echo $status_db; ?></span>
              </div>
            </div>

            <div class="relative border-l-2 border-gray-200 dark:border-slate-700 ml-4 space-y-8">
              
              <div class="relative pl-6 <?php echo $level >= 4 ? '' : 'opacity-40'; ?> transition-all duration-300">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full <?php echo $level >= 4 ? 'bg-emerald-500 ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-gray-300 dark:bg-slate-600'; ?>"></div>
                <h4 class="font-bold <?php echo $level >= 4 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'; ?>">Selesai & Siap Diambil</h4>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Sepatu Anda sudah bersih wangi dan siap dibawa pulang.</p>
              </div>

              <div class="relative pl-6 <?php echo $level >= 3 ? '' : 'opacity-40'; ?> transition-all duration-300">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full <?php echo $level >= 3 ? ($level == 3 ? 'bg-emerald-500 ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-blue-500 ring-4 ring-blue-100 dark:ring-blue-950') : 'bg-gray-300 dark:bg-slate-600'; ?>"></div>
                <h4 class="font-bold <?php echo $level >= 3 ? ($level == 3 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-800 dark:text-gray-200') : 'text-gray-500 dark:text-gray-400'; ?>">Sepatu Sedang Dicuci (In Progress)</h4>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Sepatu Anda saat ini masuk tahap treatment oleh petugas profesional.</p>
              </div>
              
              <div class="relative pl-6 <?php echo $level >= 2 ? '' : 'opacity-40'; ?> transition-all duration-300">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full <?php echo $level >= 2 ? ($level == 2 ? 'bg-emerald-500 ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-blue-500 ring-4 ring-blue-100 dark:ring-blue-950') : 'bg-gray-300 dark:bg-slate-600'; ?>"></div>
                <h4 class="font-bold <?php echo $level >= 2 ? ($level == 2 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-800 dark:text-gray-200') : 'text-gray-500 dark:text-gray-400'; ?>">Menunggu Antrean Workshop / Dijemput</h4>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Sepatu telah sampai di gerai toko utama kami dan masuk antrean pengerjaan rak cuci.</p>
              </div>
              
              <div class="relative pl-6">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full <?php echo $level == 1 ? 'bg-emerald-500 ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-blue-500 ring-4 ring-blue-100 dark:ring-blue-950'; ?>"></div>
                <span class="text-xs text-gray-400 block"><?php echo $tgl_pesan; ?></span>
                <h4 class="font-bold <?php echo $level == 1 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-800 dark:text-gray-200'; ?>">Order Berhasil Dibuat</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sistem menerima data permintaan order cuci sepatu mandiri dari akun pelanggan.</p>
              </div>
            </div>

          <?php else : ?>
            <div class="text-center py-12 flex flex-col items-center justify-center">
              <div class="mb-6 flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-slate-700 rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400 dark:text-gray-300">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Belum Ada Transaksi</h3>
              <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm max-w-[400px]">Anda belum pernah melakukan pemesanan perawatan sepatu.</p>
              <a href="pesan-layanan.php" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md transition duration-300 text-sm">Pesan Layanan Sekarang</a>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </section>

    <footer class="relative z-10 bg-[#090E34] pt-8 text-gray-300 overflow-hidden">
      <div class="absolute bottom-0 left-0 -z-10 h-72 w-72 rounded-full bg-gradient-to-tr from-green-500/20 to-cyan-400/20 blur-3xl pointer-events-none"></div>
      <div class="absolute right-0 top-0 -z-10 h-80 w-80 rounded-full bg-gradient-to-br from-blue-500/10 to-teal-400/20 blur-3xl pointer-events-none"></div>
      
      <div class="border-t border-white border-opacity-10 py-8 bg-[#0b113c]">
        <div class="container px-4 mx-auto text-center">
          <p class="text-base text-[#959CB1]">
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
        const userGreeting = document.getElementById("userGreeting");
        const logoutBtn = ud_header.querySelector(".theme-logout");
        const themeToggler = document.getElementById('themeToggler');
        const menuToggle = ud_header.querySelectorAll(".ud-menu-toggle");

        if (window.pageYOffset > 50) {
          ud_header.classList.remove("absolute", "bg-transparent");
          ud_header.classList.add("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50", "transition-all", "duration-200");
          
          logo.classList.remove("text-white");
          logo.classList.add("text-blue-600", "dark:text-white");

          themeToggler.classList.remove("text-white");
          themeToggler.classList.add("text-gray-800", "dark:text-white");

          if (userGreeting) {
            userGreeting.classList.remove("text-white");
            userGreeting.classList.add("text-gray-800", "dark:text-gray-200");
          }

          menuToggle.forEach(toggle => {
            toggle.classList.remove("bg-white");
            toggle.classList.add("bg-dark", "dark:bg-white");
          });

          navLinks.forEach(link => {
            if(link.getAttribute('href') === '#') {
              link.classList.remove("lg:text-white");
              link.classList.add("text-blue-600", "dark:text-blue-400");
            } else {
              link.classList.remove("lg:text-white");
              link.classList.add("text-gray-800", "dark:text-gray-200");
            }
          });

          if (logoutBtn) {
            logoutBtn.classList.remove("text-white", "bg-white/20", "hover:bg-white/100", "hover:text-red-600");
            logoutBtn.classList.add("bg-red-600", "text-white", "hover:bg-red-700");
          }
        } else {
          ud_header.classList.remove("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50");
          ud_header.classList.add("absolute", "bg-transparent");
          
          logo.classList.remove("text-blue-600", "dark:text-white");
          logo.classList.add("text-white");

          themeToggler.classList.remove("text-gray-800", "dark:text-white");
          themeToggler.classList.add("text-white");

          if (userGreeting) {
            userGreeting.classList.remove("text-gray-800", "dark:text-gray-200");
            userGreeting.classList.add("text-white");
          }

          menuToggle.forEach(toggle => {
            toggle.classList.remove("bg-dark", "dark:bg-white");
            toggle.classList.add("bg-white");
          });

          navLinks.forEach(link => {
            if(link.getAttribute('href') === '#') {
              link.classList.add("lg:text-blue-400");
            } else {
              link.classList.remove("text-gray-800", "dark:text-gray-200");
              link.classList.add("lg:text-white");
            }
          });

          if (logoutBtn) {
            logoutBtn.classList.remove("bg-red-600", "hover:bg-red-700");
            logoutBtn.classList.add("text-white", "bg-white/20", "hover:bg-white/100", "hover:text-red-600");
          }
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