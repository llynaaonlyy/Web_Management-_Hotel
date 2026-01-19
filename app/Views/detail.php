<!-- ============================================ -->
<!-- File: app/Views/detail.php -->
<!-- ============================================ -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($akomodasi['nama']) ?> - Hotelku</title>
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
        .carousel-img {
            height: 500px;
            object-fit: cover;
        }
        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin: 30px 0 20px 0;
            color: #333;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }
        .highlight-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            border-left: 3px solid #667eea;
        }
        .facility-item {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #e8eaf6;
            border-radius: 20px;
            color: #667eea;
        }
        .policy-box {
            background: #fff3cd;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ffc107;
        }
        .room-card {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .room-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102,126,234,0.2);
        }
        .room-price {
            color: #667eea;
            font-size: 28px;
            font-weight: bold;
        }
        .btn-pesan {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-pesan:hover {
            background: #764ba2;
            color: #e0e0e0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
        }
        .btn-disabled {
        background-color: #ff0000 !important;
        color: #ffffff !important;
        cursor: not-allowed;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        }

        /* Badge status */
        .badge-available {
            background-color: #16a34a;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-maintenance {
            background-color: #dc2626;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        .date-input-group {
            background: white;
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 20px;
            margin: 30px 0;
        }
        @media (max-width: 768px) {
            .carousel-img { height: 300px; }
            .logo { font-size: 22px; }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
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

    <!-- Carousel Gambar -->
    <div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php 
            $totalFoto = !empty($foto) ? count($foto) : 1;
            for ($i = 0; $i < $totalFoto; $i++): 
            ?>
                <button type="button"
                    data-bs-target="#photoCarousel"
                    data-bs-slide-to="<?= $i ?>"
                    <?= $i == 0 ? 'class="active"' : '' ?>>
                </button>
            <?php endfor; ?>
        </div>
        <div class="carousel-inner">
            <?php if (!empty($foto)): ?>
                <?php foreach ($foto as $index => $f): ?>
                    <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                        <img src="<?= base_url('uploads/akomodasi/' . $f['foto']) ?>"
                            class="d-block w-100 carousel-img"
                            alt="Foto <?= $index + 1 ?>">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="carousel-item active">
                    <img src="<?= base_url('uploads/akomodasi/' . $akomodasi['foto_utama']) ?>"
                        class="d-block w-100 carousel-img"
                        alt="Foto utama">
                </div>
            <?php endif; ?>
        </div>
    </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    <!-- Detail Informasi -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Info Dasar -->
                <h1 class="mb-3"><?= esc($akomodasi['nama']) ?></h1>
                <div class="mb-4">
                    <span class="badge bg-primary text-uppercase"><?= esc($akomodasi['tipe']) ?></span>
                    <span class="ms-3">
                        <i class="fas fa-star text-warning"></i> 
                        <strong><?= number_format($akomodasi['rating'], 1) ?></strong>
                    </span>
                    <span class="ms-3 text-muted">
                        <i class="fas fa-map-marker-alt"></i> <?= esc($akomodasi['alamat']) ?>
                    </span>
                </div>
                <p class="lead"><?= esc($akomodasi['deskripsi']) ?></p>

                <!-- Highlights -->
                <?php if(!empty($highlights)): ?>
                    <h3 class="section-title">Highlight</h3>
                    <?php foreach($highlights as $h): ?>
                        <div class="highlight-item">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <?= esc($h['highlight']) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Fasilitas -->
                <?php if(!empty($fasilitas)): ?>
                    <h3 class="section-title">Fasilitas</h3>
                    <div>
                        <?php foreach($fasilitas as $f): ?>
                            <div class="facility-item">
                                <i class="fas <?= esc($f['icon']) ?> me-2"></i>
                                <?= esc($f['nama_fasilitas']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Lokasi -->
                <h3 class="section-title">Lokasi</h3>
                <div class="mb-4">
                    <p><i class="fas fa-map-marker-alt text-danger me-2"></i> 
                       <?= esc($akomodasi['alamat']) ?>, <?= esc($akomodasi['kota']) ?>
                    </p>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.23698114427!2d106.68942945!3d-6.229386649999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sen!2sid!4v1234567890" 
                                style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <!-- Kebijakan -->
                <?php if(!empty($kebijakan)): ?>
                    <h3 class="section-title">Kebijakan Akomodasi</h3>
                    <div class="policy-box">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fas fa-sign-in-alt me-2"></i>Check-in:</strong>
                                <?= date('H:i', strtotime($kebijakan['check_in'])) ?>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-sign-out-alt me-2"></i>Check-out:</strong>
                                <?= date('H:i', strtotime($kebijakan['check_out'])) ?>
                            </div>
                        </div>
                        <?php if($kebijakan['kebijakan_pembatalan']): ?>
                            <p><strong>Kebijakan Pembatalan:</strong><br>
                               <?= esc($kebijakan['kebijakan_pembatalan']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if($kebijakan['aturan_lainnya']): ?>
                            <p><strong>Aturan Lainnya:</strong><br>
                               <?= esc($kebijakan['aturan_lainnya']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar Booking -->
            <div class="col-lg-4">
                <div class="date-input-group sticky-top" style="top: 20px;">
                    <h5 class="mb-3">Pilih Tanggal Menginap</h5>
                    <form id="dateForm">
                        <div class="mb-3">
                            <label class="form-label">Check-in</label>
                            <input type="date" class="form-control" id="checkinDate" 
                                   value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Check-out</label>
                            <input type="date" class="form-control" id="checkoutDate" 
                                   value="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                        <div class="alert alert-info">
                            <strong id="nightCount">1</strong> malam menginap
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tipe Kamar -->
        <h3 class="section-title mt-5">Tipe Kamar</h3>
        <div class="row">
            <?php if(!empty($tipe_kamar)): ?>
                <?php foreach($tipe_kamar as $tk): ?>
                    <div class="col-12">
                        <div class="room-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img src="https://picsum.photos/300/200?random=room<?= $tk['id'] ?>" 
                                         class="img-fluid rounded" alt="<?= esc($tk['nama_tipe']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <h4><?= esc($tk['nama_tipe']) ?></h4>
                                    <?php if ($tk['status'] === 'maintenance'): ?>
                                        <span class="badge badge-maintenance">Maintenance</span>
                                    <?php else: ?>
                                        <span class="badge badge-available">Available</span>
                                    <?php endif; ?>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-users me-2"></i><?= $tk['kapasitas'] ?> Tamu
                                        <span class="ms-3">
                                            <i class="fas fa-ruler-combined me-2"></i><?= esc($tk['ukuran_kamar']) ?>
                                        </span>
                                    </p>
                                    <p class="mb-2"><?= esc($tk['fasilitas_kamar']) ?></p>
                                    <small class="text-muted">Tersedia: <?= $tk['stok_kamar'] ?> kamar</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="room-price mb-3">
                                        Rp <?= number_format($tk['harga_per_malam'], 0, ',', '.') ?>
                                    </div>
                                    <small class="text-muted d-block mb-3">/ malam</small>
                                    <?php if ($tk['status'] === 'available'): ?>
                                        <a href="#" 
                                        class="btn btn-pesan w-100 btn-booking"
                                        data-kamar-id="<?= $tk['id'] ?>">
                                            Pesan Sekarang
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-pesan w-100 btn-disabled" disabled>
                                            Sedang Maintenance
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning">
                        Belum ada tipe kamar tersedia untuk akomodasi ini.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hitung jumlah malam
        const checkinInput = document.getElementById('checkinDate');
        const checkoutInput = document.getElementById('checkoutDate');
        const nightCount = document.getElementById('nightCount');

        function calculateNights() {
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);
            const diff = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
            nightCount.textContent = diff > 0 ? diff : 1;
        }

        checkinInput.addEventListener('change', function() {
            const minCheckout = new Date(this.value);
            minCheckout.setDate(minCheckout.getDate() + 1);
            checkoutInput.min = minCheckout.toISOString().split('T')[0];
            if(new Date(checkoutInput.value) <= new Date(this.value)) {
                checkoutInput.value = minCheckout.toISOString().split('T')[0];
            }
            calculateNights();
        });

        checkoutInput.addEventListener('change', calculateNights);

        // Booking button
        document.querySelectorAll('.btn-booking').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const kamarId = this.dataset.kamarId;
                const checkin = checkinInput.value;
                const checkout = checkoutInput.value;
                window.location.href = `/pemesanan/${kamarId}?checkin=${checkin}&checkout=${checkout}`;
            });
        });
    </script>
</body>
</html>