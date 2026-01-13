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
    </style>
</head>
<body>
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
        <div class="profile-card">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            
            <h3 class="text-center mb-4">Profil Pengguna</h3>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="/profil/update" method="post">
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
                           value="<?= esc($user['no_telp']) ?>">
                </div>

                <div class="text-center">
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