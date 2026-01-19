<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Highlights - Admin Hotelku</title>
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
        .form-container {
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .highlight-item {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .highlight-item:hover {
            background: #e9ecef;
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="/admin/dashboard" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku Admin
                    </a>
                </div>
                <div class="col-6 text-end">
                    <a href="/admin/akomodasi" class="text-white">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="form-container">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <h2 class="mb-4">
            <i class="fas fa-star me-2" style="color: #667eea;"></i>
            Kelola Highlights - <?= esc($akomodasi['nama']) ?>
        </h2>

        <!-- Form Tambah Highlight -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus me-2"></i>Tambah Highlight Baru
            </div>
            <div class="card-body">
                <form action="/admin/highlights/save" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="akomodasi_id" value="<?= $akomodasi['id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-10 mb-3">
                            <label class="form-label">Highlight</label>
                            <input type="text" class="form-control" name="highlight" 
                                   placeholder="Contoh: Lokasi strategis di pusat kota" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- List Highlights -->
        <h5 class="mb-3">Daftar Highlights</h5>
        
        <?php if(empty($highlights)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada highlight. Tambahkan highlight pertama!
            </div>
        <?php else: ?>
            <?php foreach($highlights as $h): ?>
                <div class="highlight-item">
                    <div>
                        <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                        <?= esc($h['highlight']) ?>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" 
                                data-bs-target="#editModal<?= $h['id'] ?>">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <a href="/admin/highlights/delete/<?= $h['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Hapus highlight ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $h['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Highlight</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="/admin/highlights/save" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $h['id'] ?>">
                                <input type="hidden" name="akomodasi_id" value="<?= $akomodasi['id'] ?>">
                                
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Highlight</label>
                                        <input type="text" class="form-control" name="highlight" 
                                               value="<?= esc($h['highlight']) ?>" required>
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
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>