<!-- File: app/Views/staff/detail_pemesanan.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Dashboard Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .detail-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-label {
            color: #666;
            font-weight: 500;
        }
        .info-value {
            color: #333;
            font-weight: 600;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #ffc107; color: #333; }
        .status-confirmed { background: #28a745; color: white; }
        .status-checked-in { background: #17a2b8; color: white; }
        .status-checked-out { background: #6c757d; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #667eea;
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -19px;
            top: 20px;
            width: 2px;
            height: calc(100% - 10px);
            background: #e0e0e0;
        }
        .timeline-item:last-child::after {
            display: none;
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="/staff/dashboard" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku
                    </a>
                </div>
                <div class="col-6 text-end">
                    <a href="/staff/dashboard" class="text-white">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <h2 class="mb-4">
            <i class="fas fa-file-invoice me-2"></i>Detail Pemesanan 
            <span class="text-primary">HK<?= str_pad($pemesanan['id'], 6, '0', STR_PAD_LEFT) ?></span>
        </h2>

        <div class="row">
            <div class="col-lg-8">
                <!-- Info Pemesanan -->
                <div class="detail-card">
                    <div class="section-title">Informasi Pemesanan</div>
                    <div class="info-row">
                        <span class="info-label">Kode Booking</span>
                        <span class="info-value">HK<?= str_pad($pemesanan['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama Akomodasi</span>
                        <span class="info-value"><?= esc($pemesanan['nama_akomodasi']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tipe Kamar</span>
                        <span class="info-value"><?= esc($pemesanan['nama_tipe']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Check-in</span>
                        <span class="info-value"><?= date('d F Y', strtotime($pemesanan['tanggal_checkin'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Check-out</span>
                        <span class="info-value"><?= date('d F Y', strtotime($pemesanan['tanggal_checkout'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jumlah Malam</span>
                        <span class="info-value"><?= $pemesanan['jumlah_malam'] ?> malam</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Harga per Malam</span>
                        <span class="info-value">Rp <?= number_format($pemesanan['harga_per_malam'], 0, ',', '.') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Harga</span>
                        <span class="info-value text-primary fs-5">Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Metode Pembayaran</span>
                        <span class="info-value text-uppercase"><?= str_replace('_', ' ', $pemesanan['metode_pembayaran']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="status-badge status-<?= $pemesanan['status'] ?>">
                            <?= ucfirst($pemesanan['status']) ?>
                        </span>
                    </div>
                </div>

                <!-- Info Tamu -->
                <div class="detail-card">
                    <div class="section-title">Informasi Tamu</div>
                    <div class="info-row">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value"><?= esc($tamu['nama']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= esc($tamu['email']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. Telepon</span>
                        <span class="info-value"><?= esc($tamu['no_telp']) ?></span>
                    </div>
                    <?php if($pemesanan['catatan']): ?>
                        <div class="info-row">
                            <span class="info-label">Catatan Tamu</span>
                            <span class="info-value"><?= esc($pemesanan['catatan']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Bukti Pembayaran -->
                <?php if($pemesanan['bukti_pembayaran']): ?>
                    <div class="detail-card">
                        <div class="section-title">Bukti Pembayaran</div>
                        <img src="/uploads/bukti_pembayaran/<?= esc($pemesanan['bukti_pembayaran']) ?>" 
                             class="img-fluid rounded" alt="Bukti Pembayaran">
                    </div>
                <?php endif; ?>

                <!-- Riwayat Status -->
                <?php if(!empty($status_log)): ?>
                    <div class="detail-card">
                        <div class="section-title">Riwayat Status</div>
                        <div class="timeline">
                            <?php foreach($status_log as $log): ?>
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong><?= ucfirst($log['status_baru']) ?></strong>
                                        <small class="text-muted"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></small>
                                    </div>
                                    <div class="text-muted small">
                                        Oleh: <?= esc($log['changed_by_name'] ?? 'Sistem') ?>
                                    </div>
                                    <?php if($log['keterangan']): ?>
                                        <div class="mt-1"><?= esc($log['keterangan']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <!-- Ubah Status -->
                <div class="detail-card">
                    <div class="section-title">Kelola Status</div>
                    <form action="/staff/update-status" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="pemesanan_id" value="<?= $pemesanan['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Status Baru</label>
                            <select class="form-select" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="confirmed" <?= $pemesanan['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="cancelled" <?= $pemesanan['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3" 
                                      placeholder="Tambahkan keterangan (opsional)"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>