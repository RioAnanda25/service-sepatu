<?php 
// 1. Memulai session
session_start(); 

// 2. Hubungkan koneksi database
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit();
}

$email_login = $_SESSION['email'];
$nama_user = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Pelanggan';

// 3. Tarik semua data pesanan milik user yang sedang login
$daftar_pesanan = [];
try {
    $stmt = $koneksi->prepare("SELECT * FROM tabel_pesanan WHERE email_pelanggan = ? ORDER BY id_pesanan DESC");
    $stmt->execute([$email_login]);
    $daftar_pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "ERROR DB: " . $e->getMessage();
    exit();
}
?>
<!doctype html>
<html lang="id" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat Pesanan | The Clean</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="./src/css/tailwind.css" />

    <script src="assets/js/wow.min.js"></script>
    <script>new WOW().init();</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: "class" }</script>
  </head>

  <body class="bg-white text-gray-800 dark:bg-slate-900 dark:text-gray-100">
    <header class="absolute top-0 left-0 z-40 flex items-center w-full bg-transparent ud-header">
      <div class="container px-4 mx-auto">
        <div class="relative flex items-center justify-between -mx-4">
          <div class="max-w-full px-4 w-60">
            <div class="py-5 text-2xl font-bold text-white navbar-logo flex items-center gap-2 select-none">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
              <span>The Clean.</span>
            </div>
          </div>
          <div class="flex items-center justify-between w-full px-4">
            <div>
              <button id="navbarToggler" class="absolute right-4 top-1/2 block -translate-y-1/2 rounded-lg px-3 py-[6px] ring-primary focus:ring-2 lg:hidden">
                <span class="relative my-[6px] block h-[2px] w-[30px] bg-white ud-menu-toggle"></span>
                <span class="relative my-[6px] block h-[2px] w-[30px] bg-white ud-menu-toggle"></span>
                <span class="relative my-[6px] block h-[2px] w-[30px] bg-white ud-menu-toggle"></span>
              </button>
              <nav id="navbarCollapse" class="absolute right-4 top-full hidden w-full max-w-[250px] rounded-lg bg-white py-5 shadow-lg lg:static lg:block lg:w-full lg:max-w-full lg:bg-transparent lg:shadow-none xl:px-6 dark:bg-slate-800 lg:dark:bg-transparent">
                <ul class="block lg:flex lg:items-center 2xl:ml-20">
                  <li class="relative group">
                    <a href="pesan-layanan.php" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200 whitespace-nowrap">Pesan Layanan</a>
                  </li>
                  <li class="relative group">
                    <a href="riwayat-pesanan.php" class="flex py-2 mx-8 text-base font-medium text-blue-600 lg:text-blue-400 dark:text-blue-400 lg:py-6 transition-colors duration-200 whitespace-nowrap">Riwayat Pesanan</a>
                  </li>
                  <li class="relative group">
                    <a href="status-tracking.php" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200 whitespace-nowrap">Status Tracking</a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="hidden sm:flex items-center justify-end pr-16 lg:pr-0 gap-4">
              <span class="text-white text-base font-medium hidden lg:inline" id="userGreeting">Halo, <?php echo htmlspecialchars($nama_user); ?></span>
              <a href="logout.php" class="px-6 py-2 text-base font-medium text-white duration-300 ease-in-out rounded-md bg-white/20 hover:bg-white/100 hover:text-red-600 theme-logout">Keluar</a>
              
              <button id="themeToggler" class="flex h-9 w-9 items-center justify-center rounded-full text-white hover:bg-white/10 transition-colors">
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
      <div class="container px-4 mx-auto">
        <div class="hero-content mx-auto max-w-[780px] text-center wow fadeInUp">
          <h1 class="mb-2 text-3xl font-bold leading-snug text-white sm:text-4xl lg:text-5xl">Riwayat Pesanan Anda</h1>
          <p class="mx-auto text-base font-medium text-blue-100 sm:text-lg max-w-[600px]">Pantau status pembayaran dan proses pengerjaan sepatu kesayangan Anda di sini.</p>
        </div>
      </div>
    </section>

    <section id="riwayat" class="pt-16 pb-20 dark:bg-slate-900">
      <div class="container px-4 mx-auto">
        <div class="max-w-[850px] mx-auto space-y-6">
          
          <?php if (empty($daftar_pesanan)): ?>
            <div class="text-center bg-white dark:bg-slate-800 rounded-xl p-12 shadow-md border dark:border-slate-700">
              <img src="https://cdn-icons-png.flaticon.com/128/11545/11545464.png" alt="Kosong" class="w-20 h-20 mx-auto mb-4 opacity-70 dark:brightness-90" />
              <h3 class="text-xl font-bold text-gray-700 dark:text-white mb-1">Belum Ada Transaksi</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Anda belum pernah melakukan pemesanan perawatan sepatu.</p>
              <a href="pesan-layanan.php" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-md transition duration-300 shadow-md">Pesan Layanan Sekarang</a>
            </div>
          <?php else: ?>
            
            <?php foreach ($daftar_pesanan as $pesanan): 
              $id_pesanan = $pesanan['id_pesanan'];
              $invoice = "ORD-2026-" . str_pad($id_pesanan, 4, '0', STR_PAD_LEFT);
              
              // Menghilangkan spasi dan konversi ke huruf kecil untuk pengecekan status
              $status = isset($pesanan['status_pesanan']) ? strtolower(trim($pesanan['status_pesanan'])) : 'belum bayar';
              if (empty($status)) { $status = 'belum bayar'; }
            ?>
              <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition duration-200">
                <div class="space-y-2">
                  <div class="flex items-center gap-3 flex-wrap">
                    <span class="font-bold text-lg text-blue-600 dark:text-blue-400"><?php echo $invoice; ?></span>
                    
                    <?php if ($status == 'belum bayar'): ?>
                      <span class="text-xs font-bold px-2.5 py-1 rounded bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">BELUM BAYAR</span>
                    <?php elseif ($status == 'menunggu konfirmasi'): ?>
                      <span class="text-xs font-bold px-2.5 py-1 rounded bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">MENUNGGU KONFIRMASI</span>
                    <?php else: ?>
                      <span class="text-xs font-bold px-2.5 py-1 rounded bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400"><?php echo strtoupper($status); ?></span>
                    <?php endif; ?>
                  </div>
                  
                  <div class="text-sm space-y-1 text-gray-600 dark:text-gray-300">
                    <p><span class="font-medium">Merek Sepatu:</span> <?php echo htmlspecialchars($pesanan['merek_sepatu'] ?? '-'); ?></p>
                    <p><span class="font-medium">Layanan:</span> <?php echo htmlspecialchars($pesanan['paket_treatment']); ?></p>
                  </div>
                </div>

                <div class="w-full md:w-auto flex items-center justify-end">
                  <?php if ($status == 'belum bayar'): ?>
                    <a href="konfirmasi-pembayaran.php?order_id=<?php echo $id_pesanan; ?>" class="w-full md:w-auto text-center px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-md shadow transition duration-200">
                      Upload Struk Bayar
                    </a>
                  <?php else: ?>
                    <a href="status-tracking.php?order_id=<?php echo $id_pesanan; ?>" class="w-full md:w-auto text-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-md shadow transition duration-200">
                      Lacak Status
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>

          <?php endif; ?>
          
        </div>
      </div>
    </section>

    <footer class="relative z-10 bg-[#090E34] pt-8 text-gray-300 overflow-hidden">
      <div class="border-t border-white border-opacity-10 py-8 bg-[#0b113c]">
        <div class="container px-4 mx-auto text-center">
          <p class="text-base text-[#959CB1]">
            &copy; 2026 The Clean Cleaning Shoes and Care - Kelompok 2. All rights reserved.
          </p>
        </div>
      </div>
    </footer>

    <script>
      // Dark Mode & Navbar Scroll JS
      window.onscroll = function () {
        const ud_header = document.querySelector(".ud-header");
        const logo = document.querySelector(".navbar-logo");
        const navLinks = ud_header.querySelectorAll("nav ul li a");
        const userGreeting = document.getElementById("userGreeting");
        
        if (window.pageYOffset > 50) {
          ud_header.classList.add("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50");
          ud_header.classList.remove("absolute", "bg-transparent");
          logo.classList.add("text-blue-600", "dark:text-white");
          logo.classList.remove("text-white");
          
          if (userGreeting) {
            userGreeting.classList.add("text-gray-800", "dark:text-gray-200");
            userGreeting.classList.remove("text-white");
          }
          navLinks.forEach(link => {
            if(link.getAttribute('href') === 'riwayat-pesanan.php') {
              link.classList.add("text-blue-600", "dark:text-blue-400");
              link.classList.remove("lg:text-white");
            } else {
              link.classList.add("text-gray-800", "dark:text-gray-200");
              link.classList.remove("lg:text-white");
            }
          });
        } else {
          ud_header.classList.remove("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50");
          ud_header.classList.add("absolute", "bg-transparent");
          logo.classList.remove("text-blue-600", "dark:text-white");
          logo.classList.add("text-white");
          
          if (userGreeting) {
            userGreeting.classList.remove("text-gray-800", "dark:text-gray-200");
            userGreeting.classList.add("text-white");
          }
          navLinks.forEach(link => {
            if(link.getAttribute('href') === 'riwayat-pesanan.php') {
              link.classList.add("lg:text-blue-400");
            } else {
              link.classList.remove("text-gray-800", "dark:text-gray-200");
              link.classList.add("lg:text-white");
            }
          });
        }
      };

      const themeToggler = document.getElementById('themeToggler');
      if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }

      themeToggler.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        if (document.documentElement.classList.contains('dark')) {
          localStorage.setItem('theme', 'dark');
        } else {
          localStorage.setItem('theme', 'light');
        }
      });
    </script>
  </body>
</html>