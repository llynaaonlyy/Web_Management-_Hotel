<!-- File: app/Views/home.php (Updated with Auth) -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotelku - Pemesanan Hotel, Villa & Apartemen</title>
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
            position: relative;
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
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 5px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .search-box input {
            border: none;
            padding: 10px 20px;
        }
        .search-box input:focus {
            outline: none;
            box-shadow: none;
        }
        .search-box button {
            border-radius: 50px;
            padding: 10px 30px;
        }
        .filter-tabs {
            margin: 30px 0;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        .filter-tabs::-webkit-scrollbar {
            display: none;
        }
        .filter-btn {
            display: inline-block;
            padding: 10px 25px;
            margin-right: 10px;
            border: 2px solid #667eea;
            border-radius: 25px;
            background: white;
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #667eea;
            color: white;
        }
        .card-akomodasi {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
            height: 100%;
        }
        .card-akomodasi:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .card-akomodasi img {
            height: 250px;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
        }
        .badge-tipe {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }
        .badge-hotel { background: #4CAF50; }
        .badge-villa { background: #FF9800; }
        .badge-apart { background: #2196F3; }
        .rating {
            color: #FFC107;
            font-weight: bold;
        }
        .price {
            color: #667eea;
            font-size: 24px;
            font-weight: bold;
        }
        .location {
            color: #666;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .logo { font-size: 22px; }
            .search-box { margin-top: 15px; }
            .card-akomodasi img { height: 200px; }
            .user-name { display: none; }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 col-6">
                    <a href="/home" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku
                    </a>
                </div>
                <div class="col-md-7 col-12 order-md-2 order-3">
                    <form action="<?= base_url('/search') ?>" method="post" class="search-box">
                        <?= csrf_field() ?>
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <input type="text" name="keyword" class="form-control" 
                                       placeholder="Cari hotel, villa, atau apartemen..." 
                                       value="<?= esc($keyword) ?>">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2 col-6 text-end order-md-3 order-2">
                    <div class="user-menu d-flex align-items-center justify-content-end">
                        <span class="user-name d-none d-md-inline"><?= esc(session('nama')) ?></span>
                        <div class="dropdown">
                            <a href="#" class="profil-icon" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="/profil">
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

    <!-- Success Message -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filter Tabs -->
    <div class="container">
        <div class="filter-tabs">
            <a href="/home?tipe=semua" class="filter-btn <?= $tipe_aktif == 'semua' ? 'active' : '' ?>">
                <i class="fas fa-th"></i> Semua
            </a>
            <a href="/home?tipe=hotel" class="filter-btn <?= $tipe_aktif == 'hotel' ? 'active' : '' ?>">
                <i class="fas fa-hotel"></i> Hotel
            </a>
            <a href="/home?tipe=villa" class="filter-btn <?= $tipe_aktif == 'villa' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Villa
            </a>
            <a href="/home?tipe=apart" class="filter-btn <?= $tipe_aktif == 'apart' ? 'active' : '' ?>">
                <i class="fas fa-building"></i> Apartemen
            </a>
        </div>
    </div>

    <!-- Daftar Akomodasi -->
    <div class="container mb-5">
        <div class="row">
            <?php if(empty($akomodasi)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada hasil ditemukan</h4>
                    <p class="text-muted">Coba kata kunci lain atau ubah filter pencarian</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach($akomodasi as $item): ?>
                        <div class="col-lg-4 col-md-6 col-12">
                            <a href="/detail/<?= $item['id'] ?>" style="text-decoration: none; color: inherit;">
                                <div class="card card-akomodasi">
                                    <div style="position: relative;">
                                        <!-- <img src="https://picsum.photos/400/300?random=<?= $item['id'] ?>" 
                                            class="card-img-top" alt="<?= esc($item['nama']) ?>"> -->
                                        <img src="<?= base_url('uploads/akomodasi/' . $item['foto_utama']) ?>"
                                            class="card-img-top"
                                            alt="<?= esc($item['nama']) ?>">

                                        <span class="badge badge-<?= $item['tipe'] ?> badge-tipe">
                                            <?= ucfirst($item['tipe']) ?>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-2"><?= esc($item['nama']) ?></h5>
                                        <div class="location mb-2">
                                            <i class="fas fa-map-marker-alt"></i> <?= esc($item['kota']) ?>
                                        </div>
                                        <div class="rating mb-3">
                                            <i class="fas fa-star"></i> <?= number_format($item['rating'], 1) ?>
                                        </div>
                                        <p class="card-text text-muted" style="font-size: 14px; height: 40px; overflow: hidden;">
                                            <?= esc($item['deskripsi']) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="price">
                                                Rp <?= number_format(500000 + ($item['id'] * 100000), 0, ',', '.') ?>
                                            </div>
                                            <small class="text-muted">/ malam</small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>  
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>