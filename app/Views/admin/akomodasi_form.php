<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $akomodasi ? 'Edit' : 'Tambah' ?> Akomodasi - Admin Hotelku</title>
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
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .preview-item {
            position: relative;
            width: 150px;
            height: 150px;
        }
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        .preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
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

    <!-- Form -->
    <div class="form-container">
        <h2 class="mb-4">
            <i class="fas fa-<?= $akomodasi ? 'edit' : 'plus' ?> me-2" style="color: #667eea;"></i>
            <?= $akomodasi ? 'Edit' : 'Tambah' ?> Akomodasi
        </h2>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/admin/akomodasi/save" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $akomodasi['id'] ?? '' ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Akomodasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama" 
                           value="<?= old('nama', $akomodasi['nama'] ?? '') ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select" name="tipe" required>
                        <option value="">Pilih Tipe</option>
                        <option value="hotel" <?= old('tipe', $akomodasi['tipe'] ?? '') == 'hotel' ? 'selected' : '' ?>>Hotel</option>
                        <option value="villa" <?= old('tipe', $akomodasi['tipe'] ?? '') == 'villa' ? 'selected' : '' ?>>Villa</option>
                        <option value="apart" <?= old('tipe', $akomodasi['tipe'] ?? '') == 'apart' ? 'selected' : '' ?>>Apartemen</option>
                    </select>
                </div>

                <div class="col-md-8 mb-3">
                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="alamat" 
                           value="<?= old('alamat', $akomodasi['alamat'] ?? '') ?>" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Kota <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="kota" 
                           value="<?= old('kota', $akomodasi['kota'] ?? '') ?>" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="deskripsi" rows="4" required><?= old('deskripsi', $akomodasi['deskripsi'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Rating <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" min="0" max="5" class="form-control" name="rating" 
                           value="<?= old('rating', $akomodasi['rating'] ?? '4.0') ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Utama (Thumbnail)</label>
                    <input type="file" class="form-control" name="foto_utama" accept="image/*" 
                           onchange="previewSingle(this)">
                    <div id="preview-utama" class="mt-2"></div>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Foto Detail (Multiple)</label>
                    <input type="file" class="form-control" name="foto_detail[]" accept="image/*" 
                           multiple onchange="previewMultiple(this)">
                    <small class="text-muted">Pilih beberapa foto sekaligus</small>
                    <div id="preview-detail" class="preview-container"></div>
                </div>

                <?php if($akomodasi && !empty($foto)): ?>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Foto Detail yang Sudah Ada</label>
                        <div class="preview-container">
                            <?php foreach($foto as $f): ?>
                                <div class="preview-item">
                                    <img src="/uploads/akomodasi/<?= esc($f['foto']) ?>" alt="Foto">
                                    <a href="/admin/foto/delete/<?= $f['id'] ?>" 
                                       class="remove-btn"
                                       onclick="return confirm('Hapus foto ini?')">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
                <a href="/admin/akomodasi" class="btn btn-secondary px-5">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewSingle(input) {
            const preview = document.getElementById('preview-utama');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 200px; border-radius: 10px;">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewMultiple(input) {
            const preview = document.getElementById('preview-detail');
            preview.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
</body>
</html>