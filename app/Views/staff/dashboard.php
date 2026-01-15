<!-- File: app/Views/staff/dashboard.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai - Hotelku</title>
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
        .user-menu {
            display: flex;
        }
        .user-name {
            color: white;
            margin-right: 10px;
            font-weight: 500;
        }
        .profil-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .profil-icon:hover {
            transform: scale(1.1);
        }
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .sidebar {
            background: #2c3e50;
            min-height: calc(100vh - 70px);
            padding: 20px 0;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
            border-left: 4px solid #667eea;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .booking-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #ffc107; color: #333; }
        .status-confirmed { background: #28a745; color: white; }
        .status-checked-in { background: #17a2b8; color: white; }
        .status-checked-out { background: #6c757d; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .content-area {
            padding: 30px;
            background: #f8f9fa;
            min-height: calc(100vh - 70px);
        }
        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 25px;
        }
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            .content-area {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 col-6">
                    <a href="/staff/dashboard" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku
                    </a>
                </div>
                <div class="col-md-auto col-6 ms-auto text-end order-md-3 order-2">
                    <div class="user-menu d-flex align-items-center justify-content-end">
                        <span class="user-name d-none d-md-inline"><?= esc(session('nama')) ?></span>
                        <div class="dropdown">
                            <a href="#" class="profil-icon" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="profil_staff">
                                        <i class="fas fa-user-circle me-2"></i>Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar d-none d-md-block">
                <a href="/staff/dashboard" class="active">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="/staff/kelola-kamar">
                    <i class="fas fa-bed me-2"></i>Kelola Kamar
                </a>
                <a href="/staff/data-tamu">
                    <i class="fas fa-users me-2"></i>Data Tamu
                </a>
            </div>

            <!-- Content Area -->
            <div class="col-md-10 content-area">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <h1 class="page-title">Dashboard Pegawai</h1>

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: #667eea;">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="stat-number"><?= $stats['pemesanan_hari_ini'] ?></div>
                            <div class="stat-label">Pemesanan Hari Ini</div>
                        </div>
                    </div>
                   
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: #ffc107;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-number"><?= $stats['pending'] ?></div>
                            <div class="stat-label">Menunggu Konfirmasi</div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Pemesanan -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pemesanan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Booking</th>
                                        <th>Nama Tamu</th>
                                        <th>Akomodasi</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($pemesanan)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                Belum ada pemesanan
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($pemesanan as $p): ?>
                                            <tr>
                                                <td><strong>HK<?= str_pad($p['id'], 6, '0', STR_PAD_LEFT) ?></strong></td>
                                                <td>
                                                    <div><?= esc($p['nama_tamu']) ?></div>
                                                    <small class="text-muted"><?= esc($p['email_tamu']) ?></small>
                                                </td>
                                                <td>
                                                    <div><?= esc($p['nama_akomodasi']) ?></div>
                                                    <small class="text-muted"><?= esc($p['nama_tipe']) ?></small>
                                                </td>
                                                <td><?= date('d M Y', strtotime($p['tanggal_checkin'])) ?></td>
                                                <td><?= date('d M Y', strtotime($p['tanggal_checkout'])) ?></td>
                                                <td><strong>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></strong></td>
                                                <td>
                                                    <span class="status-badge status-<?= $p['status'] ?>">
                                                        <?= ucfirst($p['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="/staff/pemesanan/<?= $p['id'] ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>