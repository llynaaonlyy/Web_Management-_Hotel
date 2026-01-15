<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Hotelku</title>
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
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            margin: 0 auto 30px;
        }
        .form-label {
            font-weight: 600;
            color: #667eea;
        }
        .btn-update {
            background: #667eea;
            color: white;
            padding: 12px 40px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
        }
        .btn-update:hover {
            background: #764ba2;
        }
        .menu-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
            cursor: pointer;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .menu-icon {
            font-size: 36px;
            color: #667eea;
            margin-bottom: 10px;
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="/home" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku
                    </a>
                </div>
                <div class="col-6 text-end">
                    <a href="/home" class="text-white">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-container">
        <!-- Menu Cards -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="menu-card text-center" onclick="window.location.href='/histori'">
                    <div class="menu-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h5>Histori Pemesanan</h5>
                    <p class="text-muted mb-0">Lihat riwayat pemesanan Anda</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="menu-card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="menu-icon" style="color: white;">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <h5>Edit Profil</h5>
                    <p class="mb-0" style="opacity: 0.9;">Update informasi Anda</p>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="profile-card">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            
            <h3 class="text-center mb-4">Profil Pengguna</h3>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/profil/update" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" 
                           value="<?= esc($user['nama']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" 
                           value="<?= esc($user['email']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" class="form-control" name="no_telp" 
                           value="<?= esc($user['no_telp']) ?>" required>
                </div>

                <div class="text-center mb-3">
                    <button type="submit" class="btn btn-update">
                        <i class="fas fa-save me-2"></i>Update Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>