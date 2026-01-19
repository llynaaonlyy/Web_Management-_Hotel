<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Booking - Admin Hotelku</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            background: #f4f6f9;
        }

        /* TOP BAR */
        .top-bar {
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .logo {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            text-decoration: none;
        }

        /* LAYOUT */
        .dashboard-wrapper {
            display: flex;
            padding-top: 70px;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            position: fixed;
            top: 70px;
            bottom: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px 24px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #34495e;
            border-left: 4px solid #667eea;
        }

        /* CONTENT */
        .content-area {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 32px;
            max-width: none !important;
        }

        /* BOOKING CARD */
        .booking-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
            margin-bottom: 16px;
        }

        .booking-meta {
            font-size: 13px;
            color: #666;
        }

        .status {
            font-size: 11px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .pending { background: #ffc107; color: #333; }
        .confirmed { background: #28a745; color: #fff; }
        .cancelled { background: #dc3545; color: #fff; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
            }

            .content-area {
                margin-left: 0;
                width: 100%;
                padding: 16px;
            }
        }
    </style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <a href="/admin/dashboard" class="logo">
        <i class="fas fa-hotel"></i> Hotelku Admin
    </a>

    <div class="ms-auto dropdown">
        <a href="#" class="text-white dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
            <i class="fas fa-user-shield me-2"></i><?= esc($user['nama']) ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="/profil">Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
        </ul>
    </div>
</div>

<!-- LAYOUT -->
<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar d-none d-md-block">
        <a href="/admin/dashboard"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="/admin/akomodasi"><i class="fas fa-building me-2"></i>Kelola Akomodasi</a>
        <a href="/admin/tipe-kamar"><i class="fas fa-bed me-2"></i>Kelola Tipe Kamar</a>
        <a href="/admin/booking" class="active"><i class="fas fa-calendar-check me-2"></i>Data Booking</a>
        <a href="/admin/users"><i class="fas fa-users me-2"></i>Manajemen User</a>
        <a href="/admin/laporan"><i class="fas fa-file-pdf me-2"></i>Laporan</a>
    </aside>

    <!-- CONTENT -->
    <main class="content-area">

        <h2 class="fw-bold mb-3">
            <i class="fas fa-calendar-check text-primary me-2"></i>Data Booking
        </h2>

        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            Admin hanya dapat <strong>melihat</strong> data booking.
        </div>

        <?php if(empty($booking)): ?>
            <div class="text-center text-muted py-5">
                Belum ada data booking
            </div>
        <?php else: ?>
            <?php foreach($booking as $b): ?>
                <div class="booking-card d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <strong>HK<?= str_pad($b['id'], 6, '0', STR_PAD_LEFT) ?></strong><br>
                        <?= esc($b['nama_tamu']) ?>
                        <div class="booking-meta">
                            <?= esc($b['nama_tipe']) ?> ·
                            <?= date('d M Y', strtotime($b['tanggal_checkin'])) ?> –
                            <?= date('d M Y', strtotime($b['tanggal_checkout'])) ?>
                        </div>
                    </div>

                    <div class="text-end mt-3 mt-md-0">
                        <div class="fw-bold text-primary mb-1">
                            Rp <?= number_format($b['total_harga'], 0, ',', '.') ?>
                        </div>
                        <span class="status <?= $b['status'] ?>">
                            <?= ucfirst($b['status']) ?>
                        </span>
                        <br>
                        <button class="btn btn-sm btn-info text-white mt-2"
                                data-bs-toggle="modal"
                                data-bs-target="#detail<?= $b['id'] ?>">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>

                </div>

                <!-- MODAL DETAIL -->
                <div class="modal fade" id="detail<?= $b['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Detail Booking</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Tamu:</strong> <?= esc($b['nama_tamu']) ?></p>
                                <p><strong>Akomodasi:</strong> <?= esc($b['nama_akomodasi']) ?></p>
                                <p><strong>Tipe Kamar:</strong> <?= esc($b['nama_tipe']) ?></p>
                                <p><strong>Check-in:</strong> <?= date('d F Y', strtotime($b['tanggal_checkin'])) ?></p>
                                <p><strong>Check-out:</strong> <?= date('d F Y', strtotime($b['tanggal_checkout'])) ?></p>
                                <p><strong>Total:</strong> Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></p>

                                <?php if($b['catatan']): ?>
                                    <div class="alert alert-info mt-3">
                                        <strong>Catatan Tamu:</strong><br>
                                        <?= esc($b['catatan']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
