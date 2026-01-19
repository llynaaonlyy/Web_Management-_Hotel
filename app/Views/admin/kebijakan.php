<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kebijakan - Admin Hotelku</title>
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
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
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
            <i class="fas fa-clipboard-list me-2" style="color: #667eea;"></i>
            Kelola Kebijakan - <?= esc($akomodasi['nama']) ?>
        </h2>

        <form action="/admin/kebijakan/save" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="akomodasi_id" value="<?= $akomodasi['id'] ?>">
            <input type="hidden" name="id" value="<?= $kebijakan['id'] ?? '' ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Waktu Check-in <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="check_in" 
                           value="<?= old('check_in', $kebijakan['check_in'] ?? '14:00') ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Waktu Check-out <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="check_out" 
                           value="<?= old('check_out', $kebijakan['check_out'] ?? '12:00') ?>" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Kebijakan Pembatalan</label>
                    <textarea class="form-control" name="kebijakan_pembatalan" rows="3"><?= old('kebijakan_pembatalan', $kebijakan['kebijakan_pembatalan'] ?? '') ?></textarea>
                    <small class="text-muted">Contoh: Pembatalan gratis hingga 24 jam sebelum check-in</small>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Aturan Lainnya</label>
                    <textarea class="form-control" name="aturan_lainnya" rows="4"><?= old('aturan_lainnya', $kebijakan['aturan_lainnya'] ?? '') ?></textarea>
                    <small class="text-muted">Contoh: Dilarang merokok, Hewan peliharaan tidak diperbolehkan</small>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i><?= $kebijakan ? 'Update' : 'Simpan' ?> Kebijakan
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>