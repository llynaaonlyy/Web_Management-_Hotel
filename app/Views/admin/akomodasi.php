<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akomodasi - Admin Hotelku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
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
            max-width: calc(100% - 250px);
            margin-right: auto;
            overflow-x: hidden;
        }
        .akomodasi-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .akomodasi-card .card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .akomodasi-card img {
            height: 240px;
            object-fit: cover;
            width: 100%;
        }
        .akomodasi-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .badge-tipe {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-hotel { background: #4CAF50; color: white; }
        .badge-villa { background: #FF9800; color: white; }
        .badge-apart { background: #2196F3; color: white; }
    </style>
</head>
<body>
    <!-- Top Bar -->
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
            <!-- Sidebar -->
            <div class="sidebar d-none d-md-block">
                <a href="/admin/dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="/admin/akomodasi" class="active">
                    <i class="fas fa-building me-2"></i>Kelola Akomodasi
                </a>
                <a href="/admin/tipe-kamar">
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
            </div>

            <!-- Content -->
            <div class="content-area">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        <i class="fas fa-building me-2" style="color: #667eea;"></i>
                        Kelola Akomodasi
                    </h2>
                    <a href="/admin/akomodasi/tambah" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Akomodasi
                    </a>
                </div>

                <!-- Akomodasi List -->
                <div class="row g-4">
                    <?php foreach($akomodasi as $item): ?>
                        <div class="col-md-6 col-lg-6 ps-3 pe-3">
                            <div class="akomodasi-card">
                                <img src="https://picsum.photos/400/250?random=<?= $item['id'] ?>" 
                                     class="card-img-top" alt="<?= esc($item['nama']) ?>" >
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?= esc($item['nama']) ?></h5>
                                        <span class="badge-tipe badge-<?= $item['tipe'] ?>">
                                            <?= ucfirst($item['tipe']) ?>
                                        </span>
                                    </div>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= esc($item['kota']) ?>
                                    </p>
                                    <p class="mb-3" style="font-size: 14px; height: 40px; overflow: hidden;">
                                        <?= esc($item['deskripsi']) ?>
                                    </p>
                                    
                                    <div class="btn-group w-100" role="group">
                                        <a href="/admin/akomodasi/edit/<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="/admin/fasilitas/<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-cog"></i> Fasilitas
                                        </a>
                                        <a href="/admin/highlights/<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-star"></i> Highlight
                                        </a>
                                    </div>
                                    
                                    <div class="btn-group w-100 mt-2" role="group">
                                        <a href="/admin/kebijakan/<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-secondary">
                                            <i class="fas fa-clipboard-list"></i> Kebijakan
                                        </a>
                                        <a href="/admin/akomodasi/delete/<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus akomodasi ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>