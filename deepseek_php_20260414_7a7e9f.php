<?php
include '../config/database.php';
session_start();

$id = (int)$_GET['id'];
$transaksi = fetch_one("SELECT t.*, u.nama_lengkap as petugas 
                        FROM transaksi t 
                        LEFT JOIN users u ON t.petugas_id = u.id 
                        WHERE t.id = $id");

if (!$transaksi) {
    header("Location: ../dashboard.php");
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
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto mt-8 p-4">
        <div class="bg-white rounded-lg shadow-lg border-2 border-gray-300 overflow-hidden">
            <div class="bg-gray-800 text-white text-center py-3">
                <h2 class="text-xl font-bold">STRUK PARKIR</h2>
                <p class="text-sm"><?php echo date('d/m/Y H:i:s', strtotime($transaksi['created_at'])); ?></p>
            </div>
            <div class="p-4 space-y-2">
                <div class="flex justify-between border-b py-1">
                    <span class="font-semibold">Nomor Transaksi:</span>
                    <span>#<?php echo str_pad($transaksi['id'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="flex justify-between border-b py-1">
                    <span class="font-semibold">Nomor Plat:</span>
                    <span class="font-bold text-lg"><?php echo $transaksi['no_plat']; ?></span>
                </div>
                <div class="flex justify-between border-b py-1">
                    <span>Waktu Masuk:</span>
                    <span><?php echo date('d/m/Y H:i', strtotime($transaksi['waktu_masuk'])); ?></span>
                </div>
                <div class="flex justify-between border-b py-1">
                    <span>Waktu Keluar:</span>
                    <span><?php echo date('d/m/Y H:i', strtotime($transaksi['waktu_keluar'])); ?></span>
                </div>
                <div class="flex justify-between border-b py-1">
                    <span>Durasi:</span>
                    <span><?php echo $transaksi['durasi']; ?> jam</span>
                </div>
                <div class="flex justify-between border-b py-1">
                    <span>Tarif/Jam:</span>
                    <span>Rp <?php echo number_format($transaksi['tarif_per_jam'], 0, ',', '.'); ?></span>
                </div>
                <div class="border-t-2 border-dashed pt-2 mt-2">
                    <div class="flex justify-between font-bold text-lg">
                        <span>TOTAL BAYAR:</span>
                        <span class="text-green-600">Rp <?php echo number_format($transaksi['total_biaya'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <div class="text-center text-gray-400 text-sm mt-4">
                    Terima kasih sudah menggunakan layanan parkir kami
                </div>
            </div>
        </div>
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                🖨️ Cetak Struk
            </button>
            <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">
                Tutup
            </button>
        </div>
    </div>
    <script>
        window.onload = function() {
            // Auto print jika parameter print=1
            if (window.location.search.includes('print=1')) {
                window.print();
            }
        }
    </script>
</body>
</html>