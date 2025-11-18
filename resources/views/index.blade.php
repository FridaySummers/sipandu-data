<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU DATA - Sistem Pemantauan dan Update Data Perencanaan</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <div class="nav-container">
                <div class="nav-logo">
                    <i class="fas fa-chart-line"></i>
                    <span>SIPANDU DATA</span>
                </div>
                <div class="nav-menu" id="nav-menu">
                    <ul class="nav-list">
                        <li><a href="#home" class="nav-link">Beranda</a></li>
                        <li><a href="#about" class="nav-link">Tentang</a></li>
                        <li><a href="#features" class="nav-link">Fitur</a></li>
                        <li><a href="#dinas" class="nav-link">Dinas</a></li>
                        <li><a href="{{ route('login') }}" class="nav-link btn-primary">Masuk Aplikasi</a></li>
                    </ul>
                </div>
                <div class="nav-toggle" id="nav-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-award"></i>
                    <span>Proyek Perubahan DIKLAT PKA 2025</span>
                </div>
                <h1 class="hero-title">
                    <span class="highlight">SIPANDU DATA</span>
                    <br>Sistem Pemantauan dan Update Data Perencanaan
                </h1>
                <p class="hero-subtitle">
                    Inovasi digital untuk koordinasi data RKPD 12 dinas di Kabupaten Kolaka Utara.
                    Meningkatkan efektivitas perencanaan pembangunan dengan pendekatan terintegrasi.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-large">
                        <i class="fas fa-arrow-right"></i> Mulai Sekarang
                    </a>
                    <a href="#about" class="btn btn-outline btn-large">
                        <i class="fas fa-info-circle"></i> Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://via.placeholder.com/500x400" alt="SIPANDU DATA Dashboard">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="features-container">
            <h2>Fitur Utama</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Dashboard Analitik</h3>
                    <p>Monitoring real-time progress pengumpulan data dari semua dinas dengan visualisasi yang intuitif</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Keamanan Terjamin</h3>
                    <p>Sistem autentikasi dan otorisasi yang ketat untuk melindungi data sensitif pemerintah</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Manajemen Data</h3>
                    <p>Sistem upload, validasi, dan penyimpanan data terstruktur untuk semua submission dinas</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Kolaborasi Tim</h3>
                    <p>Forum diskusi dan komunikasi antar dinas untuk koordinasi yang lebih efektif</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <h3>Manajemen Timeline</h3>
                    <p>Kalender deadline, reminder otomatis, dan tracking progress untuk setiap tahap</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h3>Export & Laporan</h3>
                    <p>Generate laporan komprehensif dalam berbagai format untuk analisis dan presentasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dinas Section -->
    <section id="dinas" class="dinas-section">
        <div class="dinas-container">
            <h2>Dinas Terintegrasi</h2>
            <p class="section-subtitle">12 Dinas/Lembaga di Kabupaten Kolaka Utara</p>
            <div class="dinas-list">
                <div class="dinas-item">
                    <i class="fas fa-building"></i>
                    <h4>Bappeda</h4>
                    <p>Koordinator Utama</p>
                </div>
                <div class="dinas-item">
                    <i class="fas fa-industry"></i>
                    <h4>DPMPTSP</h4>
                    <p>Penanaman Modal</p>
                </div>
                <div class="dinas-item">
                    <i class="fas fa-handshake"></i>
                    <h4>Dinas Perdagangan</h4>
                    <p>Perdagangan</p>
                </div>
                <div class="dinas-item">
                    <i class="fas fa-factory"></i>
                    <h4>Dinas Perindustrian</h4>
                    <p>Industri</p>
                </div>
                <div class="dinas-item">
                    <i class="fas fa-seedling"></i>
                    <h4>Dinas Pertanian</h4>
                    <p>Pertanian</p>
                </div>
                <div class="dinas-item">
                    <i class="fas fa-fish"></i>
                    <h4>Dinas Perikanan</h4>
                    <p>Perikanan</p>
                </div>
                <div class="dinas-item">
                    <i class="fas fa-leaf"></i>
                    <h4>Dinas Lingkungan Hidup</h4>
                    <p>Lingkungan</p>
                </div>
                <div class="dinas-item">
                    <i class="fas fa-map"></i>
                    <h4>Dinas Pariwisata</h4>
                    <p>Pariwisata</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>SIPANDU DATA</h4>
                    <p>Sistem Pemantauan dan Update Data Perencanaan</p>
                    <p>Dikembangkan oleh Bappeda Kabupaten Kolaka Utara</p>
                </div>
                <div class="footer-section">
                    <h5>Links</h5>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Tentang</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Kontak</h5>
                    <p>Email: info@sipandu.id</p>
                    <p>Telepon: (0401) xxx-xxxx</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 SIPANDU DATA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
