<?php
include '../config/database.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = (int)$_GET['id'];

// Ambil data transaksi
$transaksi = fetch_one("SELECT t.*, u.nama_lengkap as petugas 
                        FROM transaksi t 
                        LEFT JOIN users u ON t.petugas_id = u.id 
                        WHERE t.id = $id");

if (!$transaksi) {
    echo "Data transaksi tidak ditemukan!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk Parkir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .struk-card {
                box-shadow: none;
                border: 1px solid #ccc;
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Struk -->
            <div class="bg-white rounded-lg shadow-lg border-2 border-gray-300 overflow-hidden struk-card">
                <div class="bg-gray-800 text-white text-center py-3">
                    <h2 class="text-xl font-bold">STRUK PARKIR</h2>
                    <p class="text-xs">No. Transaksi: #<?php echo str_pad($transaksi['id'], 6, '0', STR_PAD_LEFT); ?></p>
                </div>
                
                <div class="p-4 space-y-2">
                    <div class="flex justify-between border-b py-1">
                        <span class="text-gray-600">Nomor Plat:</span>
                        <span class="font-bold"><?php echo $transaksi['no_plat']; ?></span>
                    </div>
                    
                    <div class="flex justify-between border-b py-1">
                        <span class="text-gray-600">Waktu Masuk:</span>
                        <span><?php echo date('d/m/Y H:i', strtotime($transaksi['waktu_masuk'])); ?></span>
                    </div>
                    
                    <div class="flex justify-between border-b py-1">
                        <span class="text-gray-600">Waktu Keluar:</span>
                        <span><?php echo date('d/m/Y H:i', strtotime($transaksi['waktu_keluar'])); ?></span>
                    </div>
                    
                    <div class="flex justify-between border-b py-1">
                        <span class="text-gray-600">Durasi:</span>
                        <span><?php echo $transaksi['durasi']; ?> jam</span>
                    </div>
                    
                    <div class="flex justify-between border-b py-1">
                        <span class="text-gray-600">Tarif per Jam:</span>
                        <span>Rp <?php echo number_format($transaksi['tarif_per_jam'], 0, ',', '.'); ?></span>
                    </div>
                    
                    <div class="border-t-2 border-dashed pt-2 mt-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>TOTAL BAYAR:</span>
                            <span class="text-green-600">Rp <?php echo number_format($transaksi['total_biaya'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    
                    <div class="text-center text-gray-400 text-xs mt-4 pt-2 border-t">
                        Terima kasih sudah menggunakan layanan parkir kami
                    </div>
                    <div class="text-center text-gray-400 text-xs">
                        <?php echo date('d/m/Y H:i:s'); ?>
                    </div>
                </div>
            </div>
            
            <!-- Tombol Aksi (tidak ikut cetak) -->
            <div class="flex gap-3 mt-4 no-print">
                <button onclick="window.print()" class="flex-1 bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                    🖨️ Cetak Struk
                </button>
                <button onclick="window.close()" class="flex-1 bg-gray-500 text-white py-2 rounded hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print jika parameter print=1
        if (window.location.search.includes('print=1')) {
            window.print();
        }
    </script>
</body>
</html>