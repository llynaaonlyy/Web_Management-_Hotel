<!-- File: app/Views/landing.php -->
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotelku - Platform Pemesanan Hotel, Villa & Apartemen Terbaik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920') center/cover;
            opacity: 0.1;
        }
        
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            padding: 50px 0;
        }
        
        .hero-title {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease;
        }
        
        .hero-subtitle {
            font-size: 24px;
            margin-bottom: 40px;
            opacity: 0.95;
            animation: fadeInUp 1.2s ease;
        }
        
        .btn-hero {
            padding: 15px 50px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            margin: 10px;
            transition: all 0.3s;
            animation: fadeInUp 1.4s ease;
        }
        
        .btn-primary-custom {
            background: white;
            color: #667eea;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.3);
            background: #f8f9ff;
            color: #667eea;
        }
        
        .btn-outline-custom {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-outline-custom:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.3);
        }
        
        .features-section {
            padding: 100px 0;
            background: #f8f9fa;
        }
        
        .feature-card {
            text-align: center;
            padding: 40px 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
            margin-bottom: 30px;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102,126,234,0.2);
        }
        
        .feature-icon {
            font-size: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        
        .feature-desc {
            color: #666;
            font-size: 16px;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
        }
        
        .stat-item {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .cta-section {
            padding: 100px 0;
            background: white;
            text-align: center;
        }
        
        .cta-title {
            font-size: 42px;
            font-weight: 800;
            color: #333;
            margin-bottom: 20px;
        }
        
        .cta-subtitle {
            font-size: 20px;
            color: #666;
            margin-bottom: 40px;
        }
        
        footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 20px;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 36px;
            }
            .hero-subtitle {
                font-size: 18px;
            }
            .btn-hero {
                padding: 12px 30px;
                font-size: 16px;
            }
            .stat-number {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand logo" href="/">
                <i class="fas fa-hotel"></i> Hotelku
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="
                    [poiuy">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary px-4 ms-2" href="auth/login">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary px-4 ms-2 text-black" href="auth/register">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Temukan Akomodasi Impian Anda
                </h1>
                <p class="hero-subtitle">
                    Pesan hotel, villa, dan apartemen terbaik dengan mudah dan cepat
                </p>
                <div>
                    <a href="auth/register" class="btn btn-hero btn-primary-custom">
                        <i class="fas fa-rocket me-2"></i>Mulai Sekarang
                    </a>
                    <a href="#features" class="btn btn-hero btn-outline-custom">
                        <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3">Mengapa Memilih Hotelku?</h2>
                <p class="lead text-muted">Platform terbaik untuk memenuhi kebutuhan akomodasi Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search-location"></i>
                        </div>
                        <h3 class="feature-title">Pencarian Mudah</h3>
                        <p class="feature-desc">
                            Temukan akomodasi yang Anda inginkan dengan fitur pencarian canggih dan filter yang lengkap
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3 class="feature-title">Harga Terbaik</h3>
                        <p class="feature-desc">
                            Dapatkan penawaran harga terbaik untuk hotel, villa, dan apartemen pilihan Anda
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Aman & Terpercaya</h3>
                        <p class="feature-desc">
                            Sistem pembayaran yang aman dan data Anda dijamin terlindungi dengan baik
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="feature-title">Booking 24/7</h3>
                        <p class="feature-desc">
                            Lakukan pemesanan kapan saja, di mana saja, tanpa batasan waktu
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">Layanan 24 Jam</h3>
                        <p class="feature-desc">
                            Tim customer service kami siap membantu Anda kapan pun dibutuhkan
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Responsif</h3>
                        <p class="feature-desc">
                            Akses dari perangkat apa pun - desktop, tablet, atau smartphone
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Akomodasi</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">50,000+</div>
                        <div class="stat-label">Pengguna</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Kota</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">4.8/5</div>
                        <div class="stat-label">Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="about">
        <div class="container">
            <h2 class="cta-title">Siap Memulai Perjalanan Anda?</h2>
            <p class="cta-subtitle">
                Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan booking akomodasi bersama Hotelku
            </p>
            <a href="auth/register" class="btn btn-hero btn-primary-custom">
                <i class="fas fa-user-plus me-2"></i>Daftar Gratis Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-hotel me-2"></i>Hotelku
                    </h5>
                    <p class="text-white-50">
                        Platform pemesanan hotel, villa, dan apartemen terpercaya di Indonesia
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="auth/login" class="text-white-50 text-decoration-none">Login</a></li>
                        <li><a href="auth/register" class="text-white-50 text-decoration-none">Register</a></li>
                        <li><a href="#features" class="text-white-50 text-decoration-none">Fitur</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                    <p class="text-white-50">
                        <i class="fas fa-envelope me-2"></i>info@hotelku.com<br>
                        <i class="fas fa-phone me-2"></i>+62 821 2345 6789
                    </p>
                </div>
            </div>
            <hr class="bg-white-50">
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; 2025 Hotelku. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>