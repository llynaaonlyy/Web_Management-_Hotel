<!-- ============================================ -->
<!-- File: app/Views/emails/booking_confirmation.php -->
<!-- ============================================ -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pemesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .booking-code {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .booking-code h2 {
            margin: 0;
            color: #667eea;
            font-size: 24px;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
        }
        .info-table td {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏨 Hotelku</h1>
            <p>Konfirmasi Pemesanan Anda</p>
        </div>
        
        <div class="content">
            <p>Halo <strong><?= esc($tamu['nama']) ?></strong>,</p>
            
            <p>Terima kasih telah memesan di Hotelku! Pemesanan Anda telah dikonfirmasi.</p>
            
            <div class="booking-code">
                <h2>Kode Booking: HK<?= str_pad($pemesanan['id'], 6, '0', STR_PAD_LEFT) ?></h2>
            </div>
            
            <h3>Detail Pemesanan:</h3>
            <table class="info-table">
                <tr>
                    <td>Akomodasi</td>
                    <td><?= esc($pemesanan['nama_akomodasi']) ?></td>
                </tr>
                <tr>
                    <td>Tipe Kamar</td>
                    <td><?= esc($pemesanan['nama_tipe']) ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td><?= esc($pemesanan['alamat']) ?>, <?= esc($pemesanan['kota']) ?></td>
                </tr>
                <tr>
                    <td>Check-in</td>
                    <td><?= date('d F Y', strtotime($pemesanan['tanggal_checkin'])) ?></td>
                </tr>
                <tr>
                    <td>Check-out</td>
                    <td><?= date('d F Y', strtotime($pemesanan['tanggal_checkout'])) ?></td>
                </tr>
                <tr>
                    <td>Jumlah Malam</td>
                    <td><?= $pemesanan['jumlah_malam'] ?> malam</td>
                </tr>
                <tr>
                    <td>Total Pembayaran</td>
                    <td><strong>Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></strong></td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td><?= strtoupper(str_replace('_', ' ', $pemesanan['metode_pembayaran'])) ?></td>
                </tr>
            </table>
            
            <p><strong>Catatan Penting:</strong></p>
            <ul>
                <li>Simpan kode booking Anda untuk keperluan check-in</li>
                <li>Check-in dimulai pukul 14:00 WIB</li>
                <li>Check-out paling lambat pukul 12:00 WIB</li>
                <li>Harap tunjukkan email konfirmasi ini saat check-in</li>
            </ul>
            
            <p>Jika Anda memiliki pertanyaan, silakan hubungi kami di:</p>
            <ul>
                <li>Email: info@hotelku.com</li>
                <li>Telepon: +62 821 2345 6789</li>
            </ul>
            
            <p>Kami menantikan kedatangan Anda!</p>
            
            <p>Salam hangat,<br><strong>Tim Hotelku</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; 2025 Hotelku. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
