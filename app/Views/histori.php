<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Pemesanan - Hotelku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .histori-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .histori-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            transition: all 0.3s;
        }
        .histori-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }
        .card-body-custom {
            padding: 25px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #666;
            font-weight: 500;
        }
        .info-value {
            color: #333;
            font-weight: 600;
            text-align: right;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-pending { background: #ffc107; color: #333; }
        .status-confirmed { background: #28a745; color: white; }
        .status-checked-in { background: #17a2b8; color: white; }
        .status-checked-out { background: #6c757d; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .bukti-pembayaran {
            max-width: 200px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .bukti-pembayaran:hover {
            transform: scale(1.05);
        }
        .catatan-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
            }
            .info-value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-6">
                    <a href="/home" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku
                    </a>
                </div>
                <div class="col-md-6 col-6 text-end">
                    <a href="/profil" class="text-white">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="histori-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-history me-2" style="color: #667eea;"></i>
                Histori Pemesanan
            </h2>
            <span class="badge bg-primary">
                <?= count($histori) ?> Pemesanan
            </span>
        </div>

        <?php if(empty($histori)): ?>
            <!-- Empty State -->
            <div class="histori-card">
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4 class="text-muted">Belum Ada Riwayat Pemesanan</h4>
                    <p class="text-muted">Anda belum pernah melakukan pemesanan di Hotelku</p>
                    <a href="/home" class="btn btn-primary mt-3">
                        <i class="fas fa-search me-2"></i>Mulai Booking
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Histori Cards -->
            <?php foreach($histori as $h): ?>
                <div class="histori-card">
                    <!-- Card Header -->
                    <div class="card-header-custom">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-1">
                                    <i class="fas fa-hotel me-2"></i>
                                    <?= esc($h['nama_akomodasi']) ?>
                                </h5>
                                <small style="opacity: 0.9;">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= esc($h['kota']) ?>
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <small class="d-block" style="opacity: 0.9;">
                                    Kode Booking
                                </small>
                                <strong style="font-size: 18px;">
                                    HK<?= str_pad($h['id'], 6, '0', STR_PAD_LEFT) ?>
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Info Pemesanan -->
                                <div class="info-row">
                                    <span class="info-label">Tipe Kamar</span>
                                    <span class="info-value"><?= esc($h['nama_tipe']) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Tanggal Check-in</span>
                                    <span class="info-value">
                                        <?= date('d F Y', strtotime($h['tanggal_checkin'])) ?>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Tanggal Check-out</span>
                                    <span class="info-value">
                                        <?= date('d F Y', strtotime($h['tanggal_checkout'])) ?>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Jumlah Malam</span>
                                    <span class="info-value">
                                        <?= $h['jumlah_malam'] ?> malam
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Total Harga</span>
                                    <span class="info-value" style="color: #667eea; font-size: 20px;">
                                        Rp <?= number_format($h['total_harga'], 0, ',', '.') ?>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Metode Pembayaran</span>
                                    <span class="info-value text-uppercase">
                                        <?= str_replace('_', ' ', $h['metode_pembayaran']) ?>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Status Pemesanan</span>
                                        <span class="status-badge status-<?= $h['status_terkini'] ?>">
                                            <?= ucfirst($h['status_terkini']) ?>
                                        </span>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Tanggal Pemesanan</span>
                                    <span class="info-value">
                                        <?= date('d F Y H:i', strtotime($h['created_at'])) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Bukti Pembayaran (HANYA jika BUKAN cash) -->
                            <div class="col-md-4">
                                <?php if($h['metode_pembayaran'] !== 'cash' && $h['bukti_pembayaran']): ?>
                                    <div class="text-center">
                                        <label class="form-label">Bukti Pembayaran</label>
                                        <div>
                                            <img src="/uploads/bukti_pembayaran/<?= esc($h['bukti_pembayaran']) ?>" 
                                                 alt="Bukti Pembayaran" 
                                                 class="bukti-pembayaran img-fluid"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#modal<?= $h['id'] ?>">
                                        </div>
                                    </div>
                                    
                                <?php elseif($h['metode_pembayaran'] === 'cash'): ?>
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                        <p class="mb-0"><strong>Pembayaran Cash</strong></p>
                                        <small>Bayar di tempat saat check-in</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Catatan dari Petugas (jika ada) -->
                        <?php if(!empty($h['catatan_petugas'])): ?>
                            <div class="catatan-box mt-3">
                                <strong style="color: #667eea;">
                                <i class="fas fa-comment-dots me-2"></i>
                                Catatan dari Petugas:
                                </strong>
                                <p class="mb-0 mt-2"><?= esc($h['catatan_petugas']) ?></p>
                            </div>
                        <?php else: ?>
                            <div class="catatan-box mt-3">
                                <em class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Tidak ada catatan dari petugas
                                </em>
                            </div>
                        <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>