<?php
session_start();

// Validasi otentikasi keamanan sesi pengguna sebelum memberikan izin akses halaman
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: signin.php");
    exit();
}

// Otorisasi Hak Akses Eksekutif: Memastikan tingkat hak akses akun adalah Pemilik (Owner)
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'pemilik') {
    header("Location: signin.php?pesan=akses_ditolak");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Clean | Dashboard Pemilik Toko</title>
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
            <a href="dashboard-pemilik.php" class="py-5 text-2xl font-bold text-white navbar-logo flex items-center gap-2">
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
                    <a href="#overview" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Laporan Finansial</a>
                  </li>
                  <li class="relative group">
                    <a href="#orders" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Data Transaksi & Pembayaran</a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="hidden sm:flex items-center justify-end pr-16 lg:pr-0 gap-4">
              <span id="roleBadge" class="text-sm font-semibold text-white px-3 py-1 bg-white/20 rounded-md transition-colors duration-200">
                Halo, <?php echo isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Pemilik'; ?>
              </span>
              
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
                Panel Ringkasan Eksekutif Bisnis
              </h1>
              <p class="mx-auto mb-6 max-w-[600px] text-base font-medium text-blue-100">
                Sistem Informasi Terintegrasi Kerja Eksekutif. Modul Pemantauan Pendapatan, Manajemen Keuangan, dan Analisis Laporan Operasional Bisnis.
              </p>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8 max-w-5xl mx-auto wow fadeInUp" data-wow-delay=".3s">
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Total Pendapatan</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">Rp 2.450.000</h3>
          </div>
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-500 dark:text-blue-400 uppercase">Transaksi Selesai</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">98 Layanan</h3>
          </div>
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-500 dark:text-blue-300 uppercase">Menunggu Pembayaran</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">4 Invoice</h3>
          </div>
          <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border dark:border-slate-700 text-left">
            <span class="block text-xs font-bold text-blue-500 dark:text-blue-400 uppercase">Total Pelanggan</span>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">54 Orang</h3>
          </div>
        </div>
      </div>
    </section>

    <section id="orders" class="pt-16 pb-20 dark:bg-slate-900 bg-gray-50">
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap -mx-4">
          <div class="w-full px-4">
            <div class="mx-auto mb-10 max-w-[600px] text-center wow fadeInUp" data-wow-delay=".1s">
              <span class="block mb-2 text-lg font-semibold text-blue-600">Laporan Finansial Toko</span>
              <h2 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl">Manajemen Finansial & Pembayaran</h2>
              <p class="text-base text-gray-600 dark:text-gray-300">Pantau seluruh catatan transaksi kas masuk, konfirmasi validasi audit keuangan internal toko, dan kelola data operasional.</p>
            </div>
          </div>
        </div>

        <div class="w-full max-w-5xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-lg border dark:border-slate-700 overflow-hidden wow fadeInUp" data-wow-delay=".2s">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 text-sm font-semibold border-b dark:border-slate-600">
                  <th class="p-4">No. Transaksi</th>
                  <th class="p-4">Pelanggan & Item</th>
                  <th class="p-4">Paket Layanan</th>
                  <th class="p-4">Metode Bayar</th>
                  <th class="p-4 text-center">Status Finansial</th>
                  <th class="p-4 text-right">Total Pendapatan</th>
                  <th class="p-4 text-center">Tindakan Kerja</th>
                </tr>
              </thead>
              <tbody class="text-sm divide-y divide-gray-100 dark:divide-slate-700 text-gray-600 dark:text-gray-300">
                
                <tr class="align-middle">
                  <td class="p-4 font-mono font-bold text-blue-600 dark:text-blue-400">TC-2026-001</td>
                  <td class="p-4">
                    <p class="font-bold text-gray-800 dark:text-white text-base">asep</p>
                    <p class="text-xs text-gray-400">Sepatu (1 item)</p>
                  </td>
                  <td class="p-4 font-medium">Express Clean</td>
                  <td class="p-4 text-xs">Tunai (Cash)</td>
                  <td class="p-4 text-center whitespace-nowrap">
                    <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 text-xs font-bold rounded-full uppercase tracking-wide border border-green-500/20">Lunas Terverifikasi</span>
                  </td>
                  <td class="p-4 font-semibold text-gray-800 dark:text-white text-right whitespace-nowrap">Rp 45.000</td>
                  <td class="p-4 text-center whitespace-nowrap">
                    <button onclick="validasiFinansialOwner('TC-2026-001', 'Cetak Audit')" class="px-3 py-1.5 bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 text-xs font-semibold rounded transition shadow border dark:border-slate-600">🖨️ Cetak Laporan</button>
                  </td>
                </tr>
                
                <tr class="align-middle">
                  <td class="p-4 font-mono font-bold text-blue-600 dark:text-blue-400">TC-2026-002</td>
                  <td class="p-4">
                    <p class="font-bold text-gray-800 dark:text-white text-base">Dadan Bahlul</p>
                    <p class="text-xs text-gray-400">sendal jepit</p>
                  </td>
                  <td class="p-4 font-medium">Express Clean</td>
                  <td class="p-4 text-xs">Transfer Bank</td>
                  <td class="p-4 text-center whitespace-nowrap">
                    <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 text-xs font-bold rounded-full uppercase tracking-wide border border-green-500/20">Lunas Terverifikasi</span>
                  </td>
                  <td class="p-4 font-semibold text-gray-800 dark:text-white text-right whitespace-nowrap">Rp 45.000</td>
                  <td class="p-4 text-center whitespace-nowrap">
                    <button onclick="validasiFinansialOwner('TC-2026-002', 'Cetak Audit')" class="px-3 py-1.5 bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 text-xs font-semibold rounded transition shadow border dark:border-slate-600">🖨️ Cetak Laporan</button>
                  </td>
                </tr>

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
            <a href="dashboard-pemilik.php" class="mb-4 text-2xl font-bold text-white flex items-center gap-2">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
              <span>The Clean.</span>
            </a>
            <p class="text-sm text-[#959CB1]">
              Sistem Otomasi Logistik Kerja & Manajemen Toko Sepatu.
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
            link.classList.remove("lg:text-white");
            link.classList.add("text-gray-800", "dark:text-gray-200");
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
            link.classList.remove("text-gray-800", "dark:text-gray-200");
            link.classList.add("lg:text-white");
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

      function validasiFinansialOwner(orderId, jenisTindakan) {
        alert("Sistem Finansial Owner:\nTransaksi " + orderId + " Berhasil Melakukan Tindakan [" + jenisTindakan + "].");
      }

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