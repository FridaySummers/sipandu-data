# Membuat file HTML utama - index.html
html_content = '''<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU DATA - Sistem Pemantauan dan Update Data Perencanaan</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/mobile.css">
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
                        <li><a href="login.html" class="nav-link btn-primary">Masuk Aplikasi</a></li>
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
                    Dikembangkan oleh H. Agus Salim, S.Pi - Kepala Bidang Ekonomi dan SDA Bappeda.
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Dinas Terintegrasi</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">70%</div>
                        <div class="stat-label">Data Completion</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Mobile Ready</div>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="login.html" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk Aplikasi
                    </a>
                    <a href="#about" class="btn btn-secondary">
                        <i class="fas fa-info-circle"></i>
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-graphic">
                    <i class="fas fa-laptop-code"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2>Tentang SIPANDU DATA</h2>
                <p>Solusi digital terdepan untuk koordinasi data perencanaan pembangunan daerah</p>
            </div>
            <div class="about-grid">
                <div class="about-content">
                    <h3>Latar Belakang Proyek</h3>
                    <p>SIPANDU DATA dikembangkan sebagai respons terhadap tantangan koordinasi data antar dinas dalam penyusunan RKPD. Sistem ini mengotomatisasi proses pengumpulan, validasi, dan kompilasi data dari 12 dinas/lembaga di Kabupaten Kolaka Utara.</p>
                    
                    <div class="developer-info">
                        <div class="developer-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="developer-details">
                            <h4>H. Agus Salim, S.Pi</h4>
                            <p>Kepala Bidang Ekonomi dan SDA</p>
                            <p>Bappeda Kabupaten Kolaka Utara</p>
                            <span class="badge">Developer & Innovator</span>
                        </div>
                    </div>
                </div>
                <div class="about-features">
                    <div class="feature-item">
                        <i class="fas fa-bullseye"></i>
                        <h4>Visi</h4>
                        <p>Terwujudnya koordinasi data perencanaan yang efektif, efisien, dan berkelanjutan</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-rocket"></i>
                        <h4>Misi</h4>
                        <p>Mengoptimalkan pengelolaan data dukung OPD melalui digitalisasi dan automasi</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-target"></i>
                        <h4>Tujuan</h4>
                        <p>Meningkatkan kualitas RKPD berbasis data yang akurat dan terkini</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <h2>Fitur Unggulan</h2>
                <p>Teknologi modern untuk pemerintahan digital yang efektif</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Dashboard Real-time</h3>
                    <p>Monitoring progress dan status data dari semua dinas secara real-time dengan visualisasi interaktif</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Responsive</h3>
                    <p>Akses penuh dari smartphone dan tablet dengan interface yang dioptimalkan untuk touch interaction</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Data Management</h3>
                    <p>Upload, validasi, dan approve data dengan workflow terstruktur dan audit trail lengkap</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Collaboration Tools</h3>
                    <p>Forum diskusi, calendar coordination, dan notification system untuk komunikasi antar dinas</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Analytics & Reports</h3>
                    <p>Generate laporan dan analisis dengan berbagai format export untuk keperluan perencanaan</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Security & Compliance</h3>
                    <p>Sistem keamanan berlapis dengan role-based access control dan compliance standar pemerintahan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dinas Section -->
    <section id="dinas" class="dinas">
        <div class="container">
            <div class="section-header">
                <h2>12 Dinas Terintegrasi</h2>
                <p>Koordinasi data terpusat untuk seluruh OPD mitra Bidang Ekonomi dan SDA</p>
            </div>
            <div class="dinas-grid" id="dinas-grid">
                <!-- Dinas cards will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Mulai Koordinasi Data Digital Hari Ini</h2>
                <p>Bergabung dengan transformasi digital pemerintahan Kabupaten Kolaka Utara</p>
                <a href="login.html" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i>
                    Akses SIPANDU DATA
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <i class="fas fa-chart-line"></i>
                        <span>SIPANDU DATA</span>
                    </div>
                    <p>Sistem Pemantauan dan Update Data Perencanaan<br>Kabupaten Kolaka Utara</p>
                </div>
                <div class="footer-info">
                    <h4>Dikembangkan oleh:</h4>
                    <p><strong>H. Agus Salim, S.Pi</strong></p>
                    <p>Kepala Bidang Ekonomi dan SDA</p>
                    <p>Bappeda Kabupaten Kolaka Utara</p>
                </div>
                <div class="footer-project">
                    <h4>Proyek:</h4>
                    <p>Proyek Perubahan</p>
                    <p>DIKLAT PKA 2025</p>
                    <p>&copy; 2025 Bappeda Kolaka Utara</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/utils.js"></script>
    <script src="js/app.js"></script>
</body>
</html>'''

# Simpan file HTML
with open('index.html', 'w', encoding='utf-8') as f:
    f.write(html_content)

print("✅ index.html created successfully")
print(f"📄 File size: {len(html_content.encode('utf-8'))} bytes")
print("🏠 Landing page dengan hero section, about, features, dan dinas grid")