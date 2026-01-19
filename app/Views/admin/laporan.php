<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Laporan - Admin Hotelku</title>
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
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .content-area { margin-left: 0; padding: 10px; }
            .sidebar { width: 100%; position: relative; min-height: auto; }
            .form-container { padding: 15px; }
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
                <a href="/admin/users">
                    <i class="fas fa-users me-2"></i>Manajemen User
                </a>
                <a href="/admin/laporan" class="active">
                    <i class="fas fa-file-pdf me-2"></i>Laporan
                </a>
            </div>

            <div class="content-area">
                <div class="form-container">
                    <h2 class="mb-4 text-center">
                        <i class="fas fa-file-pdf me-2" style="color: #667eea;"></i>
                        Generate Laporan Pemesanan
                    </h2>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pilih rentang tanggal untuk generate laporan PDF format A4
                    </div>

                    <form action="/admin/laporan/generate" method="post" target="_blank">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_mulai" 
                                   value="<?= date('Y-m-01') ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_selesai" 
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="alert alert-warning">
                            <strong>Catatan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Laporan akan menampilkan semua pemesanan dalam rentang tanggal check-in</li>
                                <li>Jika tidak ada data, akan muncul pesan "Tidak ada pemesanan"</li>
                                <li>Format PDF A4 siap cetak dengan kop surat hotel</li>
                                <li>Pembuat laporan: <strong><?= esc($user['nama']) ?></strong></li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-file-download me-2"></i>
                            Generate & Download Laporan PDF
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="/admin/dashboard" class="text-muted">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>