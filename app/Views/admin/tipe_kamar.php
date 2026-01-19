<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tipe Kamar - Admin Hotelku</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            background: #f4f6f9;
        }

        /* ===== TOP BAR ===== */
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

        /* ===== LAYOUT ===== */
        .dashboard-wrapper {
            display: flex;
            padding-top: 70px;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
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

        /* ===== CONTENT ===== */
        .content-area {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 32px;
            max-width: none !important;
        }

        /* ===== TABLE ===== */
        .card {
            border-radius: 12px;
        }

        .table th {
            white-space: nowrap;
        }

        /* STATUS */
        .status-available { color: #28a745; }
        .status-maintenance { color: #dc3545; }

        /* ===== RESPONSIVE ===== */
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

<!-- ===== TOP BAR ===== -->
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

<!-- ===== LAYOUT ===== -->
<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar d-none d-md-block">
        <a href="/admin/dashboard">
            <i class="fas fa-home me-2"></i>Dashboard
        </a>
        <a href="/admin/akomodasi">
            <i class="fas fa-building me-2"></i>Kelola Akomodasi
        </a>
        <a href="/admin/tipe-kamar" class="active">
            <i class="fas fa-bed me-2"></i>Kelola Tipe Kamar
        </a>
        <a href="/admin/booking">
            <i class="fas fa-calendar-check me-2"></i>Data Booking
        </a>
        <a href="/admin/users">
            <i class="fas fa-users me-2"></i>Manajemen User
        </a>
        <a href="/admin/laporan">
            <i class="fas fa-file-pdf me-2"></i>Laporan
        </a>
    </aside>

    <!-- CONTENT -->
    <main class="content-area">

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-bed me-2 text-primary"></i>
                Kelola Tipe Kamar
                <?php if($akomodasi): ?>
                    <small class="text-muted">- <?= esc($akomodasi['nama']) ?></small>
                <?php endif; ?>
            </h2>

            <a href="/admin/tipe-kamar/tambah" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Tipe Kamar
            </a>
        </div>

        <!-- TABLE -->
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Akomodasi</th>
                            <th>Tipe Kamar</th>
                            <th>Harga / Malam</th>
                            <th>Kapasitas</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($tipe_kamar)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada tipe kamar
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($tipe_kamar as $tk): ?>
                                <tr>
                                    <td><?= esc($tk['nama_akomodasi']) ?></td>
                                    <td><strong><?= esc($tk['nama_tipe']) ?></strong></td>
                                    <td>Rp <?= number_format($tk['harga_per_malam'], 0, ',', '.') ?></td>
                                    <td><?= $tk['kapasitas'] ?> orang</td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $tk['stok_kamar'] ?> kamar
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-circle status-<?= $tk['status'] ?> me-1"></i>
                                        <?= ucfirst($tk['status']) ?>
                                    </td>
                                    <td>
                                        <a href="/admin/tipe-kamar/edit/<?= $tk['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin/tipe-kamar/delete/<?= $tk['id'] ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Hapus tipe kamar ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>