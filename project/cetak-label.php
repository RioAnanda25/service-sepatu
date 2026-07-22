<?php
session_start();
include 'koneksi.php';

// Proteksi halaman biar ga diakses sembarangan
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID Pesanan tidak valid!'); window.close();</script>";
    exit;
}

$id_pesanan = $_GET['id'];

try {
    $stmt = $koneksi->prepare("SELECT * FROM tabel_pesanan WHERE id_pesanan = ?");
    $stmt->execute([$id_pesanan]);
    $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pesanan) {
        echo "<script>alert('Data pesanan tidak ditemukan!'); window.close();</script>";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Bikin nomor invoice format kasir
$invoice = "TC-2026-" . str_pad($pesanan['id_pesanan'], 3, '0', STR_PAD_LEFT);
$paket = $pesanan['paket_treatment'];

// Logika hitung total biaya label
$biaya = 0;
if ($paket == 'Fast Clean') $biaya = 20000;
elseif ($paket == 'Deep Clean') $biaya = 25000;
elseif ($paket == 'Express Clean') $biaya = 45000;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk Label - <?= $invoice ?></title>
    <style>
        /* Desain khusus struk printer thermal mini 80mm */
        @page { size: 80mm auto; margin: 0; }
        body { font-family: 'Courier New', Courier, monospace; width: 70mm; margin: 5mm auto; font-size: 12px; color: #000; background: #fff; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .invoice-title { font-size: 15px; margin: 5px 0; letter-spacing: 1px; }
        .data-table { width: 100%; margin-top: 5px; }
        .data-table td { padding: 3px 0; vertical-align: top; }
        .barcode-box { background: #000; color: #fff; padding: 5px; display: inline-block; font-size: 11px; font-family: sans-serif; font-weight: bold; letter-spacing: 3px; margin: 10px 0; }
        .footer-note { font-size: 9px; margin-top: 5px; font-style: italic; color: #333; }
    </style>
</head>
<body onload="window.print(); setTimeout(window.close, 500);">

    <div class="text-center">
        <span class="bold" style="font-size: 18px;">THE CLEAN</span><br>
        <span style="font-size: 10px;">Shoe Cleaning & Care System</span>
        <div class="divider"></div>
        <span class="bold invoice-title"><?= $invoice ?></span>
    </div>
    
    <div class="divider"></div>
    
    <table class="data-table">
        <tr>
            <td width="35%">Tanggal:</td>
            <td><?= isset($pesanan['tanggal_pesanan']) ? htmlspecialchars($pesanan['tanggal_pesanan']) : date('Y-m-d') ?></td>
        </tr>
        <tr>
            <td>Pelanggan:</td>
            <td><span class="bold"><?= htmlspecialchars($pesanan['nama_pemilik']) ?></span></td>
        </tr>
        <tr>
            <td>Item/Merek:</td>
            <td><?= htmlspecialchars($pesanan['merek_sepatu']) ?></td>
        </tr>
        <tr>
            <td>Treatment:</td>
            <td><span class="bold"><?= htmlspecialchars($paket) ?></span></td>
        </tr>
        <tr>
            <td>Logistik:</td>
            <td><?= htmlspecialchars($pesanan['opsi_logistik']) ?></td>
        </tr>
        <?php if(strtolower($pesanan['opsi_logistik']) == 'antar jemput' && !empty($pesanan['alamat'])): ?>
        <tr>
            <td>Alamat:</td>
            <td style="font-size: 10px; line-height: 1.2;"><?= htmlspecialchars($pesanan['alamat']) ?></td>
        </tr>
        <?php endif; ?>
    </table>
    
    <div class="divider"></div>
    
    <table class="data-table">
        <tr>
            <td class="bold">TOTAL BIAYA:</td>
            <td align="right" class="bold">Rp <?= number_format($biaya, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Status Transaksi:</td>
            <td align="right" class="bold"><?= (strtolower($pesanan['status_pesanan']) == 'belum bayar') ? 'BELUM LUNAS' : 'LUNAS'; ?></td>
        </tr>
    </table>
    
    <div class="divider"></div>
    
    <div class="text-center">
        <div class="barcode-box">*<?= $invoice ?>*</div>
        <p class="footer-note">Gunting label ini, tempelkan di plastik packing sepatu agar tidak tertukar saat pengerjaan massal!</p>
    </div>

</body>
</html>