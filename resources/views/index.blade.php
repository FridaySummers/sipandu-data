<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="disable-dummy" content="false">
    <title>SIPANDU DATA - Sistem Pemantauan dan Update Data Perencanaan</title>
    <meta name="description" content="SIPANDU DATA - Sistem Pemantauan dan Update Data Perencanaan. Inovasi digital untuk koordinasi data RKPD 12 dinas di Kabupaten Kolaka Utara.">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="nav">
            <div class="nav-container">
                <div class="nav-logo"><img src="https://commons.wikimedia.org/wiki/Special:FilePath/Lambang_Kabupaten_Kolaka_Utara.svg" alt="Kolaka Utara" class="logo-img" loading="lazy" /><span>SIPANDU DATA</span></div>
                <div class="nav-menu" id="nav-menu">
                    <ul class="nav-list">
                        <li><a href="#home" class="nav-link">Beranda</a></li>
                        <li><a href="#about" class="nav-link">Tentang</a></li>
                        <li><a href="#features" class="nav-link">Fitur</a></li>
                        <li><a href="#dinas" class="nav-link">Dinas</a></li>
                        <li><a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Masuk Aplikasi</a></li>
                    </ul>
                </div>
                <div class="nav-toggle" id="nav-toggle"><i class="fas fa-bars"></i></div>
            </div>
        </nav>
    </header>

    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge"><i class="fas fa-award"></i><span>Proyek Perubahan DIKLAT PKA 2025</span></div>
                <h1 class="hero-title"><span class="highlight">SIPANDU DATA</span><br>Sistem Pemantauan dan Update Data Perencanaan</h1>
                <p class="hero-subtitle">Inovasi digital untuk koordinasi data RKPD 12 dinas di Kabupaten Kolaka Utara.</p>
                <div class="hero-stats">
                    <div class="stat-item"><div class="stat-number">12</div><div class="stat-label">Dinas Terintegrasi</div></div>
                    <div class="stat-item"><div class="stat-number">70%</div><div class="stat-label">Data Completion</div></div>
                    <div class="stat-item"><div class="stat-number">100%</div><div class="stat-label">Mobile Ready</div></div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>Masuk Aplikasi</a>
                    <a href="#about" class="btn btn-secondary"><i class="fas fa-info-circle"></i>Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="hero-image"><div class="hero-graphic"><img class="hero-logo" src="https://commons.wikimedia.org/wiki/Special:FilePath/Lambang_Kabupaten_Kolaka_Utara.svg" alt="Kolaka Utara" loading="lazy" /></div></div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <div class="section-header"><h2>Tentang SIPANDU DATA</h2><p>Solusi digital terdepan untuk koordinasi data perencanaan pembangunan daerah</p></div>
            <div class="about-grid">
                <div class="about-content" data-aos="fade-right">
                    <h3>Latar Belakang Proyek</h3>
                    <p>SIPANDU DATA dikembangkan sebagai respons terhadap tantangan koordinasi data antar dinas dalam penyusunan Rencana Kerja Pemerintah Daerah (RKPD). Sistem ini hadir untuk memecahkan silo data dan mempercepat proses konsolidasi.</p>
                    <div class="developer-info">
                        <div class="developer-avatar"><img src="https://ui-avatars.com/api/?name=Agus+Salim&background=2563eb&color=fff&size=128" alt="H. Agus Salim" style="border-radius: 50%; width: 100%; height: 100%;"></div>
                        <div class="developer-details">
                            <h4>H. Agus Salim, S.Pi</h4>
                            <p>Kepala Bidang Ekonomi dan SDA</p>
                            <p>Bappeda Kabupaten Kolaka Utara</p>
                            <span class="badge">Project Leader & Innovator</span>
                        </div>
                    </div>
                </div>
                <div class="about-features">
                    <div class="feature-item"><i class="fas fa-bullseye"></i><h4>Visi</h4><p>Koordinasi data efektif, efisien, berkelanjutan</p></div>
                    <div class="feature-item"><i class="fas fa-rocket"></i><h4>Misi</h4><p>Digitalisasi dan automasi pengelolaan data OPD</p></div>
                    <div class="feature-item"><i class="fas fa-bullseye"></i><h4>Tujuan</h4><p>RKPD berbasis data akurat dan terkini</p></div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="section-header"><h2>Fitur Unggulan</h2><p>Teknologi modern untuk pemerintahan digital</p></div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-pie"></i></div>
                    <h3>Dashboard Real-time</h3>
                    <p>Monitoring progress pengumpulan data OPD secara langsung dengan visualisasi interaktif dan metrik yang akurat.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-mobile-screen-button"></i></div>
                    <h3>Mobile Responsive</h3>
                    <p>Akses sistem dari perangkat apapun. Antarmuka yang dioptimalkan untuk pengalaman luar biasa di smartphone dan tablet.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                    <h3>Manajemen Data</h3>
                    <p>Proses unggah data terpusat dengan sistem validasi otomatis dan alur persetujuan multi-level yang aman.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-comments"></i></div>
                    <h3>Ruang Diskusi</h3>
                    <p>Kolaborasi antar instansi kini lebih mudah melalui forum diskusi terpadu, notifikasi instan, dan kalender agenda.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-file-contract"></i></div>
                    <h3>Laporan Otomatis</h3>
                    <p>Hasilkan laporan komprehensif dalam satu klik untuk mempermudah evaluasi dan pelaporan ke tingkat pimpinan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                    <h3>Keamanan Terjamin</h3>
                    <p>Dilengkapi dengan enkripsi data dan sistem kontrol akses berbasis peran (RBAC) untuk melindungi data sensitif daerah.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="dinas" class="dinas">
        <div class="container">
            <div class="section-header"><h2>12 Dinas Terintegrasi</h2><p>Koordinasi data terpusat untuk OPD mitra</p></div>
            <div class="dinas-grid" id="dinas-grid"></div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Mulai Koordinasi Data Digital Hari Ini</h2>
                <p>Bergabung dengan transformasi digital pemerintahan Kabupaten Kolaka Utara</p>
                <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-arrow-right"></i>Akses SIPANDU DATA</a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand"><div class="footer-logo"><img src="https://commons.wikimedia.org/wiki/Special:FilePath/Lambang_Kabupaten_Kolaka_Utara.svg" alt="Kolaka Utara" class="logo-img" loading="lazy" /><span>SIPANDU DATA</span></div><p>Sistem Pemantauan dan Update Data Perencanaan<br>Kabupaten Kolaka Utara</p></div>
                <div class="footer-info"><h4>Dikembangkan oleh:</h4><p><strong>H. Agus Salim, S.Pi</strong></p><p>Kepala Bidang Ekonomi dan SDA</p><p>Bappeda Kabupaten Kolaka Utara</p></div>
                <div class="footer-project"><h4>Proyek:</h4><p>Proyek Perubahan</p><p>DIKLAT PKA 2025</p><p>&copy; 2025 Bappeda Kolaka Utara</p></div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
