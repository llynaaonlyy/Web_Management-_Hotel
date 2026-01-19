<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Hotelku</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
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

        /* CARD */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
            height: 100%;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content-area {
                margin-left: 0;
                width: 100%;
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
            <i class="fas fa-user-shield me-2"></i>
            <?= esc($user['nama']) ?>
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
        <a href="/admin/dashboard" class="active"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="/admin/akomodasi"><i class="fas fa-building me-2"></i>Kelola Akomodasi</a>
        <a href="/admin/tipe-kamar"><i class="fas fa-bed me-2"></i>Kelola Tipe Kamar</a>
        <a href="/admin/booking"><i class="fas fa-calendar-check me-2"></i>Data Booking</a>
        <a href="/admin/users"><i class="fas fa-users me-2"></i>Manajemen User</a>
        <a href="/admin/laporan"><i class="fas fa-file-pdf me-2"></i>Laporan</a>
    </aside>

    <!-- CONTENT -->
    <main class="content-area">

        <h2 class="mb-4 fw-bold">Dashboard Admin</h2>

        <!-- STAT -->
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_akomodasi'] ?></div>
                    <small>Total Akomodasi</small>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_pemesanan'] ?></div>
                    <small>Total Pemesanan</small>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_users'] ?></div>
                    <small>Total Pelanggan</small>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_pegawai'] ?></div>
                    <small>Total Pegawai</small>
                </div>
            </div>
        </div>

        <!-- QUICK ACTION -->
        <div class="card border-0 shadow-sm mt-5">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="/admin/akomodasi/tambah" class="btn btn-primary w-100">Tambah Akomodasi</a>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin/tipe-kamar/tambah" class="btn btn-success w-100">Tambah Tipe Kamar</a>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin/booking" class="btn btn-info w-100 text-white">Lihat Booking</a>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin/laporan" class="btn btn-warning w-100">Generate Laporan</a>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
