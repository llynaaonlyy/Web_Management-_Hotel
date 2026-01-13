<!-- File: app/Views/staff/data_tamu.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tamu - Dashboard Pegawai</title>
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
        .content-area {
            padding: 30px;
            background: #f8f9fa;
            min-height: calc(100vh - 70px);
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6">
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
                                    <a class="dropdown-item" href="staff/profil_staff">
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
                <a href="/staff/dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="/staff/kelola-kamar">
                    <i class="fas fa-bed me-2"></i>Kelola Kamar
                </a>
                <a href="/staff/data-tamu" class="active">
                    <i class="fas fa-users me-2"></i>Data Tamu
                </a>
            </div>

            <!-- Content -->
            <div class="col-md-10 content-area">
                <h2 class="mb-4"><i class="fas fa-users me-2"></i>Data Tamu</h2>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda hanya dapat melihat data tamu, tidak dapat mengubah atau menghapus akun tamu.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Total Pemesanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($tamu)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Belum ada data tamu
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($tamu as $t): ?>
                                            <tr>
                                                <td><?= esc($t['nama']) ?></td>
                                                <td><?= esc($t['email']) ?></td>
                                                <td><?= esc($t['no_telp']) ?></td>
                                                <td>
                                                    <span class="badge bg-primary"><?= $t['total_pemesanan'] ?> pemesanan</span>
                                                </td>
                                                <td>
                                                    <a href="/staff/tamu/<?= $t['id'] ?>" class="btn btn-sm btn-info text-white">
                                                        <i class="fas fa-eye me-1"></i>Detail
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