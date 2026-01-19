<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Admin Hotelku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .sidebar {
            background: #2c3e50;
            min-height: calc(100vh - 70px);
            padding: 20px 0;
            position: fixed;
            width: 250px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
            font-size: 14px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
            border-left: 4px solid #667eea;
        }
        .content-area {
            margin-left: 250px;
            padding: 20px;
            background: #f8f9fa;
            min-height: calc(100vh - 70px);
            overflow-x: hidden;
        }
        .role-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-admin { background: #dc3545; color: white; }
        .role-pegawai { background: #ffc107; color: #333; }
        .role-pelanggan { background: #28a745; color: white; }
        .table-wrapper {
            overflow-x: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }
        .table {
            width: 100%;
            font-size: 12px !important;
        }
        .table thead th {
            padding: 8px 6px !important;
            font-size: 11px !important;
            font-weight: 600;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 8px 6px !important;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .btn-sm {
            padding: 4px 8px !important;
            font-size: 10px !important;
        }
        .btn-sm i {
            margin-right: 2px !important;
        }
        .role-badge {
            font-size: 10px !important;
            padding: 3px 8px !important;
        }
        table { font-size: 13px; }
        @media (max-width: 768px) {
            .content-area { margin-left: 0; padding: 10px; }
            .sidebar { width: 100%; position: relative; min-height: auto; }
            table { font-size: 11px; }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="/admin/dashboard" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku Admin
                    </a>
                </div>
                <div class="col-6 text-end">
                    <div class="dropdown d-inline">
                        <a href="#" class="text-white dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-2"></i><?= esc($user['nama']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profil">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="sidebar d-none d-md-block">
                <a href="/admin/dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="/admin/akomodasi">
                    <i class="fas fa-building me-2"></i>Kelola Akomodasi
                </a>
                <a href="/admin/tipe-kamar">
                    <i class="fas fa-bed me-2"></i>Kelola Tipe Kamar
                </a>
                <a href="/admin/booking">
                    <i class="fas fa-calendar-check me-2"></i>Data Booking
                </a>
                <a href="/admin/users" class="active">
                    <i class="fas fa-users me-2"></i>Manajemen User
                </a>
                <a href="/admin/laporan">
                    <i class="fas fa-file-pdf me-2"></i>Laporan
                </a>
            </div>

            <div class="content-area">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <h2 class="mb-4">
                    <i class="fas fa-users me-2" style="color: #667eea;"></i>
                    Manajemen User
                </h2>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Admin & Pegawai dapat diedit. Pelanggan hanya dapat dilihat (read-only).
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-wrapper">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $u): ?>
                                        <tr>
                                            <td><?= $u['id'] ?></td>
                                            <td><strong><?= esc($u['nama']) ?></strong></td>
                                            <td><?= esc($u['email']) ?></td>
                                            <td><?= esc($u['no_telp']) ?></td>
                                            <td>
                                                <span class="role-badge role-<?= $u['role'] ?>">
                                                    <?= ucfirst($u['role']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($u['role'] === 'pelanggan'): ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fas fa-lock"></i> Read Only
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-warning" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editModal<?= $u['id'] ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal (Hanya untuk Admin & Pegawai) -->
                                        <?php if($u['role'] !== 'pelanggan'): ?>
                                            <div class="modal fade" id="editModal<?= $u['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit User - <?= esc($u['nama']) ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="/admin/users/update" method="post">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                            
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama</label>
                                                                        <input type="text" class="form-control" name="nama" 
                                                                        value="<?= esc($u['nama']) ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Email</label>
                                                                        <input type="email" class="form-control" name="email" 
                                                                        value="<?= esc($u['email']) ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">No. Telepon</label>
                                                                        <input type="tel" class="form-control" name="no_telp" 
                                                                        value="<?= esc($u['no_telp']) ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                                                        <input type="password" class="form-control" name="password" 
                                                                        placeholder="••••••••">
                                                                        <small class="text-muted">Password akan di-hash dengan bcrypt</small>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-save me-2"></i>Simpan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
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