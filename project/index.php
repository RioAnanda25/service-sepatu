<!doctype html>
<html lang="id" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Clean | Sistem Informasi Service Sepatu</title>
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

  <body class="bg-white text-gray-800 dark:bg-slate-900 dark:text-gray-100 overflow-x-hidden">
    <header class="absolute top-0 left-0 right-0 z-40 flex items-center w-full bg-transparent ud-header">
      <div class="container px-4 mx-auto">
        <div class="relative flex items-center justify-between">
          <div class="max-w-full px-4 w-60">
            <a href="index.php" class="py-5 text-2xl font-bold text-white navbar-logo flex items-center gap-2">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
              <span>The Clean.</span>
            </a>
          </div>
          <div class="flex flex-1 items-center justify-between px-4">
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
                    <a href="#home" class="flex py-2 mx-4 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Beranda</a>
                  </li>
                  <li class="relative group">
                    <a href="#about" class="flex py-2 mx-4 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Tentang</a>
                  </li>
                  <li class="relative group">
                    <a href="#features" class="flex py-2 mx-4 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Fitur</a>
                  </li>
                  <li class="relative group">
                    <a href="#pricing" class="flex py-2 mx-4 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Harga</a>
                  </li>
                  <li class="relative group">
                    <a href="#team" class="flex py-2 mx-4 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Tim</a>
                  </li>
                  <li class="relative group">
                    <a href="#contact" class="flex py-2 mx-4 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200">Kontak</a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="hidden sm:flex items-center justify-end pr-4 lg:pr-0 gap-4">
              <a href="signin.php" class="px-[22px] py-2 text-base font-medium text-white hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200 theme-signin">Login</a>
              <a href="signup.php" class="px-6 py-2 text-base font-medium text-white duration-300 ease-in-out rounded-md bg-white/20 hover:bg-white/100 hover:text-blue-600 theme-signup whitespace-nowrap">Register</a>
              
              <button id="themeToggler" class="flex h-9 w-9 items-center justify-center rounded-full text-white hover:bg-white/10 transition-colors" aria-label="theme toggler">
                <svg id="sunIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.243 3.05a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-3.05 4.243a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414zM10 14a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.243-3.05a1 1 0 010 1.414l.707-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0zM3 10a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zm3.05-4.243a1 1 0 010 1.414l-.707.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0zM10 5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"></path>
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

    <section id="home" class="relative overflow-hidden bg-blue-600 pt-[140px] pb-[100px] md:pt-[150px] lg:pt-[180px] z-10 w-full">
      <div class="absolute left-0 top-0 -z-10 h-80 w-80 rounded-full bg-gradient-to-br from-teal-400/20 to-indigo-500/30 blur-3xl pointer-events-none"></div>
      <div class="absolute right-0 bottom-0 -z-10 h-[350px] w-[350px] rounded-full bg-gradient-to-tr from-cyan-400/20 to-emerald-400/20 blur-3xl pointer-events-none"></div>
      
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap items-center">
          <div class="w-full px-4">
            <div class="hero-content mx-auto max-w-[780px] text-center wow fadeInUp" data-wow-delay=".2s">
              <h1 class="mb-6 text-3xl font-bold leading-snug text-white sm:text-4xl sm:leading-snug lg:text-5xl lg:leading-[1.2]">
                The Clean Cleaning Shoes and Care
              </h1>
              <p class="mx-auto mb-9 max-w-[600px] text-base font-medium text-blue-100 sm:text-lg sm:leading-[1.44]">
                Solusi digital terpadu untuk pembersihan dan perawatan sepatu Anda. Cepat, mudah, dan terpercaya dengan layanan antar-jemput.
              </p>
              <ul class="flex flex-wrap items-center justify-center gap-5 mb-10">
                <li>
                  <a href="#pricing" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-center text-blue-600 bg-white rounded-md hover:bg-gray-100 lg:px-7">
                    Mulai Sekarang
                  </a>
                </li>
                <li>
                  <a href="#about" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-center text-white rounded-md bg-white/20 hover:bg-white/30 lg:px-7">
                    Pelajari Lebih Lanjut
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="features" class="pt-20 pb-8 lg:pt-[120px] lg:pb-[70px] dark:bg-slate-900 w-full overflow-hidden">
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap">
          <div class="w-full px-4">
            <div class="mx-auto mb-12 max-w-[510px] text-center lg:mb-20 wow fadeInUp" data-wow-delay=".1s">
              <span class="block mb-2 text-lg font-semibold text-blue-600">Fitur Sistem</span>
              <h2 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl md:text-[40px]">Layanan Kami</h2>
              <p class="text-base text-gray-600 dark:text-gray-300">Sistem informasi ini dirancang untuk mengatasi berbagai kendala operasional dengan fitur digital yang mempermudah pelanggan dan pemilik usaha.</p>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap">
          <div class="w-full px-4 md:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".1s">
            <div class="mb-8 p-10 shadow-md rounded-xl hover:shadow-lg transition duration-300 bg-white dark:bg-slate-800 border dark:border-slate-700">
              <div class="flex items-center justify-center w-[70px] h-[70px] mb-8 bg-blue-600 rounded-2xl p-4 shadow-lg shadow-blue-500/50 dark:shadow-blue-500/30 transform transition-all duration-300 ease-in-out hover:-translate-y-1 hover:scale-110">
                <img src="https://cdn-icons-png.flaticon.com/128/3829/3829385.png" alt="Icon Pemesanan" class="w-full h-full object-contain brightness-0 invert" />
              </div>
              <h4 class="mb-3 text-xl font-bold text-dark dark:text-white">Pemesanan Layanan</h4>
              <p class="text-gray-600 dark:text-gray-300">Pelanggan dapat melakukan pemesanan layanan pencucian dan perawatan sepatu secara online tanpa harus antri.</p>
            </div>
          </div>
          <div class="w-full px-4 md:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".2s">
            <div class="mb-8 p-10 shadow-md rounded-xl hover:shadow-lg transition duration-300 bg-white dark:bg-slate-800 border dark:border-slate-700">
              <div class="flex items-center justify-center w-[70px] h-[70px] mb-8 bg-blue-600 rounded-2xl p-4 shadow-lg shadow-blue-500/50 dark:shadow-blue-500/30 transform transition-all duration-300 ease-in-out hover:-translate-y-1 hover:scale-110">
                <img src="https://cdn-icons-png.flaticon.com/128/3273/3273365.png" alt="Icon Pelacakan" class="w-full h-full object-contain brightness-0 invert" />
              </div>
              <h4 class="mb-3 text-xl font-bold text-dark dark:text-white">Pelacakan Status Pencucian</h4>
              <p class="text-gray-600 dark:text-gray-300">Pantau proses pembersihan sepatu Anda secara real-time dari mulai pengerjaan hingga selesai.</p>
            </div>
          </div>
          <div class="w-full px-4 md:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".3s">
            <div class="mb-8 p-10 shadow-md rounded-xl hover:shadow-lg transition duration-300 bg-white dark:bg-slate-800 border dark:border-slate-700">
              <div class="flex items-center justify-center w-[70px] h-[70px] mb-8 bg-blue-600 rounded-2xl p-4 shadow-lg shadow-blue-500/50 dark:shadow-blue-500/30 transform transition-all duration-300 ease-in-out hover:-translate-y-1 hover:scale-110">
                <img src="https://cdn-icons-png.flaticon.com/128/11478/11478026.png" alt="Icon Antar Jemput" class="w-full h-full object-contain brightness-0 invert" />
              </div>
              <h4 class="mb-3 text-xl font-bold text-dark dark:text-white">Layanan Antar Jemput</h4>
              <p class="text-gray-600 dark:text-gray-300">Pick up and delivery service yang memudahkan pelanggan tanpa perlu datang langsung ke lokasi usaha.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <section id="about" class="pt-20 pb-20 bg-gray-50 dark:bg-slate-800 lg:pt-[120px] lg:pb-[120px] w-full overflow-hidden">
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap items-center">
          <div class="w-full px-4 lg:w-1/2 wow fadeInUp" data-wow-delay=".1s">
            <div class="mb-12 lg:mb-0 max-w-[540px]">
              <h2 class="mb-5 text-3xl font-bold leading-tight text-dark dark:text-white sm:text-[40px] sm:leading-[1.2]">
                Tentang The Clean
              </h2>
              <p class="mb-10 text-base leading-relaxed text-gray-600 dark:text-gray-300">
                The Clean Cleaning Shoes and Care adalah usaha yang bergerak di bidang jasa pembersihan dan perawatan sepatu yang berlokasi di Kabupaten Sukoharjo, Jawa Tengah. Kami menggunakan metode <strong>deep clean</strong> untuk menghilangkan kotoran secara menyeluruh.
                <br/><br/>
                Sistem informasi ini dikembangkan untuk mendigitalisasi operasional usaha, mulai dari transaksi hingga pembayaran pelanggan yang lebih transparan.
              </p>
              <a href="#contact" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white transition duration-300 bg-blue-600 rounded-md hover:bg-blue-700">Hubungi Kami</a>
            </div>
          </div>
          <div class="w-full px-4 lg:w-1/2 wow fadeInUp" data-wow-delay=".2s">
            <div class="flex flex-wrap -mx-2 sm:-mx-4">
              <div class="w-full px-2 sm:px-4">
                <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="about image" class="object-cover object-center w-full h-[400px] rounded-xl shadow-lg" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <section id="pricing" class="relative z-20 overflow-hidden bg-white dark:bg-slate-900 pt-20 pb-12 lg:pt-[120px] lg:pb-[90px] w-full">
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap">
          <div class="w-full px-4">
            <div class="mx-auto mb-[60px] max-w-[510px] text-center wow fadeInUp" data-wow-delay=".1s">
              <span class="block mb-2 text-lg font-semibold text-blue-600">Harga Layanan</span>
              <h2 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl md:text-[40px]">Paket Perawatan Sepatu</h2>
              <p class="text-base text-gray-600 dark:text-gray-300">Harga murah dan terjangkau, khusus didesain untuk pelajar, mahasiswa, and pekerja yang peduli pada penampilan.</p>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap justify-center">
          <div class="w-full px-4 md:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".1s">
            <div class="relative z-10 px-8 py-10 mb-10 overflow-hidden bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-pricing sm:p-12 lg:px-6 lg:py-10 xl:p-14 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
              <span class="block mb-4 text-lg font-semibold text-blue-600">Fast Clean</span>
              <h2 class="mb-5 text-4xl font-bold text-dark dark:text-white xl:text-[42px]">Rp 20.000</h2>
              <p class="mb-8 text-base border-b border-[#F2F2F2] dark:border-slate-700 pb-8 text-gray-600 dark:text-gray-300">Layanan cuci kilat/biasa untuk membersihkan kotoran ringan di bagian luar sepatu dengan cepat.</p>
              <div class="mb-9 flex flex-col gap-[14px]">
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Pencucian Standar (Luar)</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Proses Sangat Cepat</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Pantau Status Online</p>
                <p class="text-base text-gray-400 dark:text-gray-500 line-through"><span class="text-red-500 font-bold mr-1">❌</span> Delivery Service</p>
              </div>
              <a href="pesan-layanan.php" class="inline-block w-full px-7 py-3 text-base font-semibold text-center text-blue-600 dark:text-white border border-blue-600 dark:border-slate-600 rounded-md hover:bg-blue-600 hover:text-white transition">Pesan Sekarang</a>
            </div>
          </div>

          <div class="w-full px-4 md:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".2s">
            <div class="relative z-10 px-8 py-10 mb-10 overflow-hidden bg-white dark:bg-slate-800 border-2 border-blue-600 rounded-xl shadow-pricing sm:p-12 lg:px-6 lg:py-10 xl:p-14 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
              <span class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-bl-lg">REKOMENDASI</span>
              <span class="block mb-4 text-lg font-semibold text-blue-600">Deep Clean</span>
              <h2 class="mb-5 text-4xl font-bold text-dark dark:text-white xl:text-[42px]">Rp 25.000</h2>
              <p class="mb-8 text-base border-b border-[#F2F2F2] dark:border-slate-700 pb-8 text-gray-600 dark:text-gray-300">Layanan lebih bagus dan menyeluruh untuk mengangkat noda membandel pada semua bagian sepatu.</p>
              <div class="mb-9 flex flex-col gap-[14px]">
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Pencucian Mendalam (Luar & Dalam)</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Pembersihan Noda Membandel</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Penghilang Bau & Bakteri</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Pantau Status Online</p>
              </div>
              <a href="pesan-layanan.php" class="inline-block w-full px-7 py-3 text-base font-semibold text-center text-white transition bg-blue-600 rounded-md hover:bg-blue-700">Pesan Sekarang</a>
            </div>
          </div>

          <div class="w-full px-4 md:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".3s">
            <div class="relative z-10 px-8 py-10 mb-10 overflow-hidden bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-pricing sm:p-12 lg:px-6 lg:py-10 xl:p-14 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
              <span class="block mb-4 text-lg font-semibold text-blue-600">Express</span>
              <h2 class="mb-5 text-4xl font-bold text-dark dark:text-white xl:text-[42px]">Rp 45.000</h2>
              <p class="mb-8 text-base border-b border-[#F2F2F2] dark:border-slate-700 pb-8 text-gray-600 dark:text-gray-300">Layanan super cepat prioritas utama tinggi yang sudah mencakup fasilitas kirim / delivery langsung.</p>
              <div class="mb-9 flex flex-col gap-[14px]">
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Pembersihan Kilat Prioritas</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Layanan Kirim / Delivery Kurir</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Antar Jemput ke Lokasi</p>
                <p class="text-base text-gray-600 dark:text-gray-300"><span class="text-green-500 font-bold mr-1">✔</span> Pantau Status Online</p>
              </div>
              <a href="pesan-layanan.php" class="inline-block w-full px-7 py-3 text-base font-semibold text-center text-blue-600 dark:text-white border border-blue-600 dark:border-slate-600 rounded-md hover:bg-blue-600 hover:text-white transition">Pesan Sekarang</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="team" class="pt-20 pb-10 bg-gray-50 dark:bg-slate-800 lg:pt-[120px] lg:pb-[80px] w-full overflow-hidden">
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap">
          <div class="w-full px-4">
            <div class="mx-auto mb-[60px] max-w-[510px] text-center wow fadeInUp" data-wow-delay=".1s">
              <span class="block mb-2 text-lg font-semibold text-blue-600">Tim Pengembang</span>
              <h2 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl md:text-[40px]">Kelompok 2</h2>
              <p class="text-base text-gray-600 dark:text-gray-300">Fakultas Ilmu Komputer, Universitas Duta Bangsa Surakarta.</p>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap justify-center">
          <div class="w-full px-4 sm:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".1s">
            <div class="mb-10 text-center">
              <div class="mx-auto mb-5 overflow-hidden rounded-full w-[120px] h-[120px] bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold">
                AA
              </div>
              <div>
                <h4 class="mb-1 text-lg font-semibold text-dark dark:text-white">Ahmad Apriansyah</h4>
                <p class="text-sm text-gray-600 dark:text-gray-300">NIM: 240101034</p>
              </div>
            </div>
          </div>
          <div class="w-full px-4 sm:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".2s">
            <div class="mb-10 text-center">
              <div class="mx-auto mb-5 overflow-hidden rounded-full w-[120px] h-[120px] bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold">
                PA
              </div>
              <div>
                <h4 class="mb-1 text-lg font-semibold text-dark dark:text-white">Puja Auliya</h4>
                <p class="text-sm text-gray-600 dark:text-gray-300">NIM: 240101049</p>
              </div>
            </div>
          </div>
          <div class="w-full px-4 sm:w-1/2 lg:w-1/3 wow fadeInUp" data-wow-delay=".3s">
            <div class="mb-10 text-center">
              <div class="mx-auto mb-5 overflow-hidden rounded-full w-[120px] h-[120px] bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold">
                RS
              </div>
              <div>
                <h4 class="mb-1 text-lg font-semibold text-dark dark:text-white">Rio Ananda Saleh</h4>
                <p class="text-sm text-gray-600 dark:text-gray-300">NIM: 240101051</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer id="contact" class="relative z-10 bg-[#090E34] pt-20 lg:pt-[120px] text-gray-300 overflow-hidden w-full">
      <div class="absolute bottom-0 left-0 -z-10 h-72 w-72 rounded-full bg-gradient-to-tr from-green-500/20 to-cyan-400/20 blur-3xl pointer-events-none"></div>
      <div class="absolute right-0 top-12 -z-10 h-80 w-80 rounded-full bg-gradient-to-br from-blue-500/10 to-teal-400/20 blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-10 right-10 -z-10 opacity-40 pointer-events-none">
        <svg width="364" height="364" viewBox="0 0 364 364" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="182" cy="182" r="182" fill="url(#footer_paint0_linear)" />
          <defs>
            <linearGradient id="footer_paint0_linear" x1="0" y1="0" x2="364" y2="364" gradientUnits="userSpaceOnUse">
              <stop stop-color="#4ade80" stop-opacity="0.2"/>
              <stop offset="1" stop-color="#22d3ee" stop-opacity="0"/>
            </linearGradient>
          </defs>
        </svg>
      </div>
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap">
          <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-3/12 wow fadeInUp" data-wow-delay=".1s">
            <div class="mb-10 w-full">
              <a href="index.php" class="mb-6 text-2xl font-bold text-white flex items-center gap-2">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
                <span>The Clean.</span>
              </a>
              <p class="mb-8 max-w-[270px] text-base text-[#959CB1]">
                Layanan kebersihan dan perawatan sepatu profesional. Menggunakan sistem terkomputerisasi yang cepat dan andal.
              </p>
            </div>
          </div>
          
          <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-2/12 xl:w-2/12 wow fadeInUp" data-wow-delay=".15s">
            <div class="mb-10 w-full">
              <h4 class="mb-9 text-lg font-semibold text-white">Tentang Kami</h4>
              <ul class="space-y-3">
                <li><a href="#home" class="inline-block text-base text-[#959CB1] hover:text-white transition">Beranda</a></li>
                <li><a href="#about" class="inline-block text-base text-[#959CB1] hover:text-white transition">Tentang</a></li>
                <li><a href="#features" class="inline-block text-base text-[#959CB1] hover:text-white transition">Fitur</a></li>
                <li><a href="#team" class="inline-block text-base text-[#959CB1] hover:text-white transition">Tim</a></li>
              </ul>
            </div>
          </div>

          <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-3/12 xl:w-3/12 wow fadeInUp" data-wow-delay=".2s">
            <div class="mb-10 w-full">
              <h4 class="mb-9 text-lg font-semibold text-white">Fitur Sistem</h4>
              <ul class="space-y-3 text-base text-[#959CB1]">
                <li>Pemesanan Layanan Online</li>
                <li>Pelacakan Status Real-time</li>
                <li>Layanan Antar Jemput</li>
              </ul>
            </div>
          </div>

          <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-3/12 xl:w-4/12 wow fadeInUp" data-wow-delay=".25s">
            <div class="mb-10 w-full">
              <h4 class="mb-9 text-lg font-semibold text-white">Informasi Kontak</h4>
              <ul class="space-y-3 text-[#959CB1]">
                <li class="flex items-start text-base">
                  <span class="mr-3 text-white">📍</span>
                  <span>Kabupaten Sukoharjo, Jawa Tengah</span>
                </li>
                <li class="flex items-start text-base">
                  <span class="mr-3 text-white">✉</span>
                  <span>theclean@gmail.com</span>
                </li>
                <li class="flex items-start text-base">
                  <span class="mr-3 text-white">🏢</span>
                  <span>The Clean Cleaning Shoes and Care</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-12 border-t border-white border-opacity-10 py-8 bg-[#0b113c] w-full">
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
        const signIn = ud_header.querySelector("a[href='signin.php']");
        const signUp = ud_header.querySelector("a[href='signup.php']");
        const themeToggler = document.getElementById('themeToggler');
        const menuToggle = ud_header.querySelectorAll(".ud-menu-toggle");

        if (window.pageYOffset > 50) {
          ud_header.classList.remove("absolute", "bg-transparent");
          ud_header.classList.add("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50", "transition-all", "duration-200");
          
          logo.classList.remove("text-white");
          logo.classList.add("text-blue-600", "dark:text-white");

          themeToggler.classList.remove("text-white");
          themeToggler.classList.add("text-gray-800", "dark:text-white");

          menuToggle.forEach(toggle => {
            toggle.classList.remove("bg-white");
            toggle.classList.add("bg-dark", "dark:bg-white");
          });

          navLinks.forEach(link => {
            link.classList.remove("lg:text-white");
            link.classList.add("text-gray-800", "dark:text-gray-200");
          });

          if (signIn) {
            signIn.classList.remove("text-white");
            signIn.classList.add("text-gray-800", "dark:text-gray-200");
          }
          if (signUp) {
            signUp.classList.remove("text-white", "bg-white/20", "hover:bg-white/100", "hover:text-blue-600");
            signUp.classList.add("bg-blue-600", "text-white", "hover:bg-blue-700");
          }
        } else {
          ud_header.classList.remove("fixed", "bg-white", "dark:bg-slate-900", "shadow-md", "z-50");
          ud_header.classList.add("absolute", "bg-transparent");
          
          logo.classList.remove("text-blue-600", "dark:text-white");
          logo.classList.add("text-white");

          themeToggler.classList.remove("text-gray-800", "dark:text-white");
          themeToggler.classList.add("text-white");

          menuToggle.forEach(toggle => {
            toggle.classList.remove("bg-dark", "dark:bg-white");
            toggle.classList.add("bg-white");
          });

          navLinks.forEach(link => {
            link.classList.remove("text-gray-800", "dark:text-gray-200");
            link.classList.add("lg:text-white");
          });

          if (signIn) {
            signIn.classList.remove("text-gray-800", "dark:text-gray-200");
            signIn.classList.add("text-white");
          }
          if (signUp) {
            signUp.classList.remove("bg-blue-600", "hover:bg-blue-700");
            signUp.classList.add("text-white", "bg-white/20", "hover:bg-white/100", "hover:text-blue-600");
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