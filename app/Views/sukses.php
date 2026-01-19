<!-- File: app/Views/sukses.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Berhasil - Hotelku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 80px auto;
            text-align: center;
        }
        .success-icon {
            font-size: 100px;
            color: #4CAF50;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .booking-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 30px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            background: #FFC107;
            color: #333;
            border-radius: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        .detail-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            text-align: left;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h2 class="mt-4 mb-3">Pemesanan Berhasil!</h2>
        <p class="text-muted">Terima kasih telah memesan di Hotelku</p>

        <div class="booking-code">
            <small>Kode Booking</small>
            <h3>HK<?= str_pad($pemesanan['id'], 6, '0', STR_PAD_LEFT) ?></h3>
        </div>

        <div class="status-badge">
            <i class="fas fa-clock me-2"></i>Status: <?= strtoupper($pemesanan['status']) ?>
        </div>

        <div class="detail-box">
            <h5 class="mb-3">Detail Pemesanan</h5>
            <div class="row mb-2">
                <div class="col-6 text-muted">Akomodasi:</div>
                <div class="col-6 text-end"><strong><?= esc($pemesanan['nama_akomodasi']) ?></strong></div>
            </div>
            <div class="row mb-2">
                <div class="col-6 text-muted">Tipe Kamar:</div>
                <div class="col-6 text-end"><strong><?= esc($pemesanan['nama_tipe']) ?></strong></div>
            </div>
            <div class="row mb-2">
                <div class="col-6 text-muted">Check-in:</div>
                <div class="col-6 text-end"><?= date('d M Y', strtotime($pemesanan['tanggal_checkin'])) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-6 text-muted">Check-out:</div>
                <div class="col-6 text-end"><?= date('d M Y', strtotime($pemesanan['tanggal_checkout'])) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-6 text-muted">Jumlah Malam:</div>
                <div class="col-6 text-end"><?= $pemesanan['jumlah_malam'] ?> malam</div>
            </div>
            <div class="row mb-2">
                <div class="col-6 text-muted">Metode Pembayaran:</div>
                <div class="col-6 text-end text-uppercase"><?= str_replace('_', ' ', $pemesanan['metode_pembayaran']) ?></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6"><strong>Total:</strong></div>
                <div class="col-6 text-end">
                    <strong style="color: #667eea; font-size: 20px;">
                        Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?>
                    </strong>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Detail pemesanan akan segera dikirim ke email Anda. Mohon bersabar dan jika sudah dikirim silakan cek untuk informasi lebih lanjut.
        </div>

        <a href="/home" class="btn btn-primary btn-lg mt-3">
            <i class="fas fa-home me-2"></i>Kembali ke Beranda
        </a>
    </div>
</body>
</html>