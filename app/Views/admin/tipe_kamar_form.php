<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tipe_kamar ? 'Edit' : 'Tambah' ?> Tipe Kamar - Admin Hotelku</title>
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
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="/admin/dashboard" class="logo">
                        <i class="fas fa-hotel"></i> Hotelku Admin
                    </a>
                </div>
                <div class="col-6 text-end">
                    <a href="/admin/tipe-kamar" class="text-white">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="form-container">
        <h2 class="mb-4">
            <i class="fas fa-bed me-2" style="color: #667eea;"></i>
            <?= $tipe_kamar ? 'Edit' : 'Tambah' ?> Tipe Kamar
        </h2>

        <form action="/admin/tipe-kamar/save" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $tipe_kamar['id'] ?? '' ?>">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Akomodasi <span class="text-danger">*</span></label>
                    <select class="form-select" name="akomodasi_id" required>
                        <option value="">Pilih Akomodasi</option>
                        <?php foreach($akomodasi as $a): ?>
                            <option value="<?= $a['id'] ?>" 
                                <?= old('akomodasi_id', $tipe_kamar['akomodasi_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                                <?= esc($a['nama']) ?> (<?= ucfirst($a['tipe']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Tipe Kamar <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_tipe" 
                           value="<?= old('nama_tipe', $tipe_kamar['nama_tipe'] ?? '') ?>" 
                           placeholder="Contoh: Deluxe Room" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga per Malam <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="harga_per_malam" 
                           value="<?= old('harga_per_malam', $tipe_kamar['harga_per_malam'] ?? '') ?>" 
                           placeholder="Contoh: 850000" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Kapasitas (orang) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="kapasitas" 
                           value="<?= old('kapasitas', $tipe_kamar['kapasitas'] ?? '2') ?>" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Ukuran Kamar <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="ukuran_kamar" 
                           value="<?= old('ukuran_kamar', $tipe_kamar['ukuran_kamar'] ?? '') ?>" 
                           placeholder="Contoh: 32 m²" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Stok Kamar <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="stok_kamar" 
                           value="<?= old('stok_kamar', $tipe_kamar['stok_kamar'] ?? '1') ?>" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Fasilitas Kamar <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="fasilitas_kamar" rows="3" required><?= old('fasilitas_kamar', $tipe_kamar['fasilitas_kamar'] ?? '') ?></textarea>
                    <small class="text-muted">Contoh: King Bed, TV LED 42", AC, Mini Bar, Kamar Mandi Pribadi</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="available" <?= old('status', $tipe_kamar['status'] ?? 'available') == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="maintenance" <?= old('status', $tipe_kamar['status'] ?? '') == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Kamar</label>
                    <input type="file" class="form-control" name="foto" accept="image/*" 
                           onchange="previewImage(this)">
                    <div id="preview" class="mt-2"></div>
                    <?php if($tipe_kamar && $tipe_kamar['foto']): ?>
                        <div class="mt-2">
                            <img src="/uploads/kamar/<?= esc($tipe_kamar['foto']) ?>" 
                                 style="max-width: 200px; border-radius: 10px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
                <a href="/admin/tipe-kamar" class="btn btn-secondary px-5">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 200px; border-radius: 10px;">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>