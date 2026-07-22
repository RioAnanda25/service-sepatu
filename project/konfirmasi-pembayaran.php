<?php 
// 1. Memulai session
session_start(); 

// 2. Hubungkan koneksi database
include 'koneksi.php';

// Pastikan user login biar tidak error mengambil data pesanan
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit();
}

$email_login = $_SESSION['email'];
// Ambil session nama sesuai login
$nama_user = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Pelanggan';

// 3. TANGKAP VALUE order_id DARI URL ?order_id=
$order_id_otomatis = isset($_GET['order_id']) ? $_GET['order_id'] : '';

// LOGIKA CERDAS: Jika order_id kosong (misal diklik dari navbar), cari pesanan terbaru milik user ini yang belum dibayar
if (empty($order_id_otomatis)) {
    try {
        $stmt_cek = $koneksi->prepare("SELECT id_pesanan FROM tabel_pesanan WHERE email_pelanggan = ? AND (LOWER(status_pesanan) = 'belum bayar' OR status_pesanan = '' OR status_pesanan IS NULL) ORDER BY id_pesanan DESC LIMIT 1");
        $stmt_cek->execute([$email_login]);
        $pesanan_terakhir = $stmt_cek->fetch(PDO::FETCH_ASSOC);
        
        if ($pesanan_terakhir) {
            $order_id_otomatis = $pesanan_terakhir['id_pesanan'];
        }
    } catch (PDOException $e) {
        // Abaikan jika error, nanti ditangani di bawah
    }
}

// 4. LOGIKA TARIK DATA DAN HARGA 
$harga_terkunci = 0;
$nama_paket_terkunci = "Detail Pesanan Tidak Ditemukan";
$invoice_text = "ORD-0000";

if (!empty($order_id_otomatis)) {
    // Format nomor invoice biar keren
    $invoice_text = "ORD-2026-" . str_pad($order_id_otomatis, 4, '0', STR_PAD_LEFT);
    
    try {
        $stmt = $koneksi->prepare("SELECT * FROM tabel_pesanan WHERE id_pesanan = ?");
        $stmt->execute([$order_id_otomatis]);
        $pesanan_terkunci = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pesanan_terkunci) {
            // Tarik nama paket dari database
            $paket_dari_db = $pesanan_terkunci['paket_treatment'];
            
            // Set harga dan nama tampilan berdasarkan pilihan (Gunakan TRIM untuk menghindari spasi tak terlihat)
            $paket_cek = trim($paket_dari_db);
            if ($paket_cek == 'Fast Clean') {
                $harga_terkunci = 20000;
                $nama_paket_terkunci = "Fast Clean (Rp 20.000)";
            } elseif ($paket_cek == 'Deep Clean') {
                $harga_terkunci = 25000;
                $nama_paket_terkunci = "Deep Clean (Rp 25.000)";
            } elseif ($paket_cek == 'Express Clean') {
                $harga_terkunci = 45000;
                $nama_paket_terkunci = "Express Clean (Rp 45.000)";
            } else {
                $nama_paket_terkunci = $paket_dari_db;
                $harga_terkunci = 0; 
            }
        } else {
            // JIKA ID TIDAK DITEMUKAN: Lempar diam-diam ke riwayat pesanan (Tanpa Alert!)
            header("Location: riwayat-pesanan.php");
            exit();
        }
    } catch (PDOException $e) {
        $nama_paket_terkunci = "ERROR DB: " . $e->getMessage();
        $harga_terkunci = 0;
    }
} else {
    // JIKA BENER-BENER GAK ADA PESANAN YANG BELUM DIBAYAR: Lempar diam-diam ke riwayat pesanan (Tanpa Alert!)
    header("Location: riwayat-pesanan.php");
    exit();
}
?>
<!doctype html>
<html lang="id" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Konfirmasi Pembayaran | The Clean</title>
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
            <a href="index.php" class="py-5 text-2xl font-bold text-white navbar-logo flex items-center gap-2">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa-htm3zT2h1cQ2cDBt_1K-2Lt_lviW4Y7oQ&s" alt="Logo The Clean" class="w-8 h-8 rounded-md object-contain" />
              <span>The Clean.</span>
            </a>
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
                    <a href="riwayat-pesanan.php" class="flex py-2 mx-8 text-base font-medium text-dark dark:text-white lg:text-white lg:py-6 hover:text-blue-600 lg:hover:text-blue-400 dark:hover:text-blue-400 transition-colors duration-200 whitespace-nowrap">Riwayat Pesanan</a>
                  </li>
                  <li class="relative group">
                    <a href="konfirmasi-pembayaran.php" class="flex py-2 mx-8 text-base font-medium text-blue-600 lg:text-blue-400 dark:text-blue-400 lg:py-6 transition-colors duration-200 whitespace-nowrap">Konfirmasi Pembayaran</a>
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
          <h1 class="mb-2 text-3xl font-bold leading-snug text-white sm:text-4xl lg:text-5xl">Konfirmasi Pembayaran</h1>
          <p class="mx-auto text-base font-medium text-blue-100 sm:text-lg max-w-[600px]">Silakan selesaikan pembayaran sesuai dengan detail pesanan yang telah Anda buat.</p>
        </div>
      </div>
    </section>

    <section id="pembayaran" class="pt-16 pb-20 dark:bg-slate-900">
      <div class="container px-4 mx-auto">
        <div class="max-w-[800px] mx-auto bg-white dark:bg-slate-800 rounded-xl p-8 shadow-md border dark:border-slate-700">
          
          <div class="mb-6 flex items-center gap-3 border-b dark:border-slate-700 pb-4">
            <div class="flex items-center justify-center w-[50px] h-[50px] bg-blue-600 rounded-xl p-3 shadow-lg">
              <img src="https://cdn-icons-png.flaticon.com/128/4021/4021708.png" alt="Icon Pembayaran" class="w-full h-full object-contain brightness-0 invert" />
            </div>
            <h2 class="text-2xl font-bold text-dark dark:text-white">Form Kirim Konfirmasi Pembayaran</h2>
          </div>
          
          <div class="mb-6 p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-sm space-y-2 border dark:border-slate-600">
            <span class="font-bold text-gray-700 dark:text-gray-200 block mb-1 text-base">Informasi Rekening Bank Toko (The Clean):</span>
            <div class="flex justify-between">
              <span>Bank BCA Transfer</span>
              <strong class="text-blue-600 dark:text-blue-400">822-0941-211 a/n The Clean Shoes</strong>
            </div>
            <div class="flex justify-between">
              <span>E-Wallet Dana / ShopeePay</span>
              <strong class="text-blue-600 dark:text-blue-400">0812-3456-7890 a/n Clean_Care_Surakarta</strong>
            </div>
          </div>

          <form action="proses-konfirmasi.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <input type="hidden" name="invoice_id" value="<?php echo htmlspecialchars($order_id_otomatis); ?>">
            <input type="hidden" name="nominal" value="<?php echo $harga_terkunci; ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
              <div>
                <label class="block text-sm font-semibold mb-2">Detail Pesanan Anda</label>
                <div class="w-full h-[52px] px-4 rounded-md bg-blue-50 dark:bg-slate-700 border border-blue-200 dark:border-slate-600 flex flex-col justify-center cursor-not-allowed">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-blue-800 dark:text-blue-300"><?php echo $invoice_text; ?></span>
                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-800 px-2 py-1 rounded"><?php echo htmlspecialchars($nama_paket_terkunci); ?></span>
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 mt-1">*Pesanan telah dikunci sistem.</p>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-2">Nama Pemilik Rekening Pengirim</label>
                <input type="text" name="nama_pengirim" placeholder="Contoh: Ahmad Apriansyah" class="w-full h-[52px] px-4 rounded-md bg-white dark:bg-slate-700 border dark:border-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm" required />
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
              <div>
                <label class="block text-sm font-semibold mb-2">Pilih Metode Transfer</label>
                <select name="metode_pembayaran" class="w-full h-[52px] px-4 rounded-md bg-white dark:bg-slate-700 border dark:border-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm" required>
                  <option value="" disabled selected>-- Pilih Bank / E-Wallet --</option>
                  <option value="bca">Bank BCA (Transfer Antar Bank)</option>
                  <option value="dana">Dana (E-Wallet)</option>
                  <option value="shopeepay">ShopeePay</option>
                  <option value="tunai">Tunai / Cash Langsung di Toko</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-2">Total Tagihan (Wajib Sesuai)</label>
                <div class="w-full h-[52px] px-4 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 flex items-center justify-between cursor-not-allowed">
                    <span class="text-sm text-red-600 dark:text-red-400 font-medium">Total:</span>
                    <span class="font-bold text-lg text-red-600 dark:text-red-400">Rp <?php echo number_format($harga_terkunci, 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-semibold mb-2">Upload Bukti Transaksi (Gambar / Screenshot JPG PNG)</label>
              <input type="file" id="fileInput" name="bukti_tf" accept="image/*" class="w-full px-4 py-2 rounded-md bg-white dark:bg-slate-700 border dark:border-slate-600 focus:outline-none text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required />
            </div>

            <div id="previewContainer" class="hidden border border-dashed dark:border-slate-600 p-4 rounded-lg flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-700/30">
              <span class="text-xs text-gray-500 mb-2">Live Preview Bukti Transaksi:</span>
              <img id="imagePreview" src="#" alt="Pratinjau Bukti" class="max-h-64 rounded-md shadow-md object-contain" />
            </div>
            
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold tracking-wide rounded-md shadow-lg transition duration-300 text-sm transform active:scale-[0.98]">KIRIM & VERIFIKASI PEMBAYARAN</button>
          </form>
          
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
      const fileInput = document.getElementById('fileInput');
      const previewContainer = document.getElementById('previewContainer');
      const imagePreview = document.getElementById('imagePreview');

      fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            imagePreview.setAttribute('src', e.target.result);
            previewContainer.classList.remove('hidden');
          }
          reader.readAsDataURL(file);
        } else {
          previewContainer.classList.add('hidden');
        }
      });

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
            if(link.getAttribute('href') === 'konfirmasi-pembayaran.php') {
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
            if(link.getAttribute('href') === 'konfirmasi-pembayaran.php') {
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