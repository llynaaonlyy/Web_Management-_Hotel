<!-- File: app/Views/pemesanan.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan - Hotelku</title>
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
        .booking-container {
            max-width: 800px;
            margin: 40px auto;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-label {
            color: #666;
            font-weight: 500;
        }
        .detail-value {
            color: #333;
            font-weight: 600;
        }
        .payment-option {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .payment-option input[type="radio"] {
            margin-right: 10px;
        }
        .payment-option.selected {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .qris-card {
            max-width: 360px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            animation: fadeUp 0.4s ease;
        }

        .qris-header h5 {
            margin: 10px 0 0;
            font-weight: 700;
        }

        .qris-header small {
            color: #777;
        }

        .qris-logo {
            width: 80px;
        }

        .qris-amount {
            font-size: 22px;
            font-weight: bold;
            margin: 15px 0;
            color: #0d6efd;
        }

        .qris-qr {
            padding: 15px;
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            display: inline-block;
        }

        .qris-footer {
            margin-top: 15px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .detail-label {
            color: #555;
        }

        .detail-value {
            font-weight: 500;
        }

        .total-price h3,
        .detail-row.fw-bold .detail-value {
            color: #1d4ed8;
        }
        .btn-submit {
            background: #667eea;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <a href="/" class="logo">
                <i class="fas fa-hotel"></i> Hotelku
            </a>
        </div>
        <div class="col-6 text-end">
            <a href="/detail" class="text-white">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="booking-container">
        <h2 class="text-center mb-4">Detail Pemesanan</h2>

        <form action="/pemesanan/proses" method="post" enctype="multipart/form-data">
            <input type="hidden" name="tipe_kamar_id" value="<?= $tipe_kamar['id'] ?>">
            <input type="hidden" name="tanggal_checkin" value="<?= $checkin ?>">
            <input type="hidden" name="tanggal_checkout" value="<?= $checkout ?>">

            <!-- Info Akomodasi -->
            <div class="info-card">
                <div class="section-title">Informasi Menginap</div>
                <div class="detail-row">
                    <span class="detail-label">Nama Akomodasi</span>
                    <span class="detail-value"><?= esc($akomodasi['nama']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Lokasi</span>
                    <span class="detail-value"><?= esc($akomodasi['kota']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipe Kamar</span>
                    <span class="detail-value"><?= esc($tipe_kamar['nama_tipe']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-in</span>
                    <span class="detail-value">
                        <?= date('d F Y', strtotime($checkin)) ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-out</span>
                    <span class="detail-value">
                        <?= date('d F Y', strtotime($checkout)) ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jumlah Malam</span>
                    <span class="detail-value">
                        <?php 
                        $date1 = new DateTime($checkin);
                        $date2 = new DateTime($checkout);
                        $malam = $date2->diff($date1)->days;
                        echo $malam . ' malam';
                        ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Harga per Malam</span>
                    <span class="detail-value">
                        Rp <?= number_format($tipe_kamar['harga_per_malam'], 0, ',', '.') ?>
                    </span>
                </div>
            </div>

            <!-- Total -->
            <div class="info-card">
                <div class="section-title">Rincian Pembayaran</div>

                <div class="detail-row">
                    <span class="detail-label">Harga Dasar</span>
                    <span class="detail-value">
                        Rp <?= number_format($harga_dasar, 0, ',', '.') ?>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Biaya Admin + PPN (22%)</span>
                    <span class="detail-value text-danger">
                        Rp <?= number_format($biaya_admin_ppn, 0, ',', '.') ?>
                    </span>
                </div>

                <hr>

                <div class="detail-row fw-bold">
                    <span class="detail-label">Total Pembayaran</span>
                    <span class="detail-value">
                        Rp <?= number_format($total_akhir, 0, ',', '.') ?>
                    </span>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="info-card">
                <div class="section-title">Metode Pembayaran</div>
                
                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="tf_bank" required>
                    <i class="fas fa-university fa-lg text-primary"></i>
                    <strong class="ms-2">Transfer Bank</strong>
                    <p class="ms-4 mb-0 small text-muted">BCA, Mandiri, BNI, BRI</p>
                </label>

                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="e_wallet">
                    <i class="fas fa-wallet fa-lg text-success"></i>
                    <strong class="ms-2">E-Wallet</strong>
                    <p class="ms-4 mb-0 small text-muted">GoPay, OVO, DANA, ShopeePay</p>
                </label>

                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" id="metode-qris" value="qris">
                    <i class="fas fa-qrcode fa-lg text-info"></i>
                    <strong class="ms-2">QRIS</strong>
                    <p class="ms-4 mb-0 small text-muted">Scan QR untuk pembayaran</p>
                </label>

                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="cash">
                    <i class="fas fa-money-bill-wave fa-lg text-warning"></i>
                    <strong class="ms-2">Cash</strong>
                    <p class="ms-4 mb-0 small text-muted">Bayar di tempat</p>
                </label>
            </div>

            <!-- Info Pembayaran Dinamis -->
            <div class="info-card d-none" id="paymentInfoCard">
                <div class="section-title">Informasi Pembayaran</div>

                <!-- Transfer Bank -->
                <div id="info-bank" class="d-none">
                    <p><strong>Silakan transfer ke rekening berikut:</strong></p>
                    <ul class="mb-0">
                        <li>BCA: 1234567890 a.n Hotelku</li>
                        <li>Mandiri: 9876543210 a.n Hotelku</li>
                    </ul>
                </div>

                <!-- E-Wallet -->
                <div id="info-ewallet" class="d-none">
                    <p><strong>Nomor E-Wallet:</strong></p>
                    <ul class="mb-0">
                        <li>GoPay / OVO / DANA: <strong>0812-3456-7890</strong></li>
                    </ul>
                </div>

                <!-- QRIS CARD -->
                <div id="qris-wrapper" class="qris-card d-none">
                    <div class="qris-header">
                        <h5>Hotelku</h5>
                        <small>Pembayaran via QRIS</small>
                    </div>

                    <div class="qris-amount">
                        Rp <?= number_format($total_akhir, 0, ',', '.') ?>
                    </div>

                    <div class="qris-qr">
                        <div id="qrcode"></div>
                    </div>
                </div>

                <!-- Cash -->
                <div id="info-cash" class="d-none">
                    <p class="mb-0">
                        Pembayaran dilakukan langsung di lokasi saat check-in.
                        Silakan tunjukkan bukti pemesanan kepada petugas.
                    </p>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="info-card d-none" id="buktiPembayaranCard">
                <div class="section-title">Bukti Pembayaran</div>

                <input type="file"
                    name="bukti_pembayaran"
                    class="form-control"
                    accept="image/*"
                    onchange="previewBukti(event)">

                <div class="mt-3 d-none" id="previewContainer">
                    <p class="mb-1"><strong>Preview:</strong></p>
                    <img id="previewImage" class="img-fluid rounded" style="max-height:300px;">
                </div>
            </div>

            <!-- Catatan -->
            <div class="info-card">
                <div class="section-title">Catatan (Opsional)</div>
                <textarea class="form-control" name="catatan" rows="3" 
                          placeholder="Tambahkan catatan khusus untuk pemesanan Anda..."></textarea>
            </div>

            <button type="submit" class="btn btn-submit" id="btnSubmit" disabled>
                <i class="fas fa-check-circle me-2"></i>Konfirmasi Pemesanan
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const paymentInfoCard = document.getElementById('paymentInfoCard');
        const buktiCard = document.getElementById('buktiPembayaranCard');

        const infoBank = document.getElementById('info-bank');
        const infoEwallet = document.getElementById('info-ewallet');
        const infoCash = document.getElementById('info-cash');

        function hideAllInfo() {
            infoBank.classList.add('d-none');
            infoEwallet.classList.add('d-none');
            infoCash.classList.add('d-none');
        }

        document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
            radio.addEventListener('change', function () {
                paymentInfoCard.classList.remove('d-none');
                hideAllInfo();

                if (this.value === 'tf_bank') infoBank.classList.remove('d-none');
                if (this.value === 'e_wallet') infoEwallet.classList.remove('d-none');
                if (this.value === 'cash') infoCash.classList.remove('d-none');

                // Bukti pembayaran hanya muncul kalau bukan cash
                if (this.value === 'cash') {
                    buktiCard.classList.add('d-none');
                } else {
                    buktiCard.classList.remove('d-none');
                }
            });
        });

        function previewBukti(event) {
            const img = document.getElementById('previewImage');
            const container = document.getElementById('previewContainer');

            img.src = URL.createObjectURL(event.target.files[0]);
            container.classList.remove('d-none');
        }

        const btnSubmit = document.getElementById('btnSubmit');
        const buktiInput = document.querySelector('input[name="bukti_pembayaran"]');

        function checkSubmitAvailability(metode) {
            if (metode === 'cash') {
                btnSubmit.disabled = false;
            } else {
                btnSubmit.disabled = !buktiInput.files.length;
            }
        }

        // saat metode pembayaran dipilih
        document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
            radio.addEventListener('change', function () {
                checkSubmitAvailability(this.value);
            });
        });

        // saat upload bukti
        if (buktiInput) {
            buktiInput.addEventListener('change', function () {
                const metode = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
                if (metode) checkSubmitAvailability(metode);
            });
        }

        // Highlight payment option when selected
        document.querySelectorAll('.payment-option input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.closest('.payment-option').classList.add('selected');
            });
        });
    </script>
    <script>
        const qrisWrapper = document.getElementById('qris-wrapper');
        const metodeRadios = document.querySelectorAll('input[name="metode_pembayaran"]');

        const totalHarga = <?= $total_akhir ?>;

        function generateQRIS() {
            document.getElementById("qrcode").innerHTML = "";

            const payload = `
        QRIS
        Hotelku
        TOTAL:${totalHarga}
        REF:${Date.now()}
            `.trim();

            new QRCode(document.getElementById("qrcode"), {
                text: payload,
                width: 200,
                height: 200
            });
        }

        metodeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'qris') {
                    qrisWrapper.classList.remove('d-none');
                    generateQRIS();
                } else {
                    qrisWrapper.classList.add('d-none');
                }
            });
        });
        </script>

</body>
</html>