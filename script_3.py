# Membuat file dashboard.html
dashboard_html = '''<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIPANDU DATA</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/mobile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="dashboard-page">
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-left">
            <button class="sidebar-toggle" id="sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-logo">
                <i class="fas fa-chart-line"></i>
                <span>SIPANDU DATA</span>
            </div>
        </div>
        
        <div class="nav-center">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari data, dinas, atau laporan...">
            </div>
        </div>
        
        <div class="nav-right">
            <div class="nav-item notifications" id="notifications">
                <i class="fas fa-bell"></i>
                <span class="badge" id="notification-count">3</span>
                <div class="dropdown notification-dropdown" id="notification-dropdown">
                    <div class="dropdown-header">
                        <h3>Notifikasi</h3>
                        <button class="mark-all-read">Tandai Semua Dibaca</button>
                    </div>
                    <div class="notification-list" id="notification-list">
                        <!-- Notifications will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            
            <div class="nav-item user-menu" id="user-menu">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span class="user-name" id="user-name">H. Agus Salim</span>
                <i class="fas fa-chevron-down"></i>
                <div class="dropdown user-dropdown" id="user-dropdown">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        Profil
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        Pengaturan
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item" id="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="user-details">
                    <h4 id="sidebar-user-name">H. Agus Salim, S.Pi</h4>
                    <p id="sidebar-user-role">Super Admin</p>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="#dashboard" class="nav-link" data-page="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#data-management" class="nav-link" data-page="data-management">
                        <i class="fas fa-database"></i>
                        <span>Data Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#reports" class="nav-link" data-page="reports">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan & Analisis</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#forum" class="nav-link" data-page="forum">
                        <i class="fas fa-comments"></i>
                        <span>Forum Diskusi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#calendar" class="nav-link" data-page="calendar">
                        <i class="fas fa-calendar"></i>
                        <span>Agenda & Kalender</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#dinas-status" class="nav-link" data-page="dinas-status">
                        <i class="fas fa-building"></i>
                        <span>Status Dinas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#settings" class="nav-link" data-page="settings">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Dashboard Page -->
        <div class="page active" id="dashboard-page">
            <div class="page-header">
                <h1>Dashboard Overview</h1>
                <p>Monitoring real-time SIPANDU DATA - RKPD 2025</p>
            </div>

            <!-- KPI Cards -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-number" id="total-dinas">12</div>
                        <div class="kpi-label">Total Dinas</div>
                        <div class="kpi-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+0%</span>
                        </div>
                    </div>
                </div>
                
                <div class="kpi-card">
                    <div class="kpi-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-number" id="complete-data">6</div>
                        <div class="kpi-label">Data Complete</div>
                        <div class="kpi-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+2</span>
                        </div>
                    </div>
                </div>
                
                <div class="kpi-card">
                    <div class="kpi-icon warning">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-number" id="avg-progress">70%</div>
                        <div class="kpi-label">Rata-rata Progress</div>
                        <div class="kpi-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+5%</span>
                        </div>
                    </div>
                </div>
                
                <div class="kpi-card">
                    <div class="kpi-icon info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-number" id="pending-reviews">8</div>
                        <div class="kpi-label">Pending Reviews</div>
                        <div class="kpi-trend negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-2</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="card-header">
                        <h3>Trend Progress Bulanan</h3>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-outline">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="monthly-progress-chart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <div class="card-header">
                        <h3>Status Data per Dinas</h3>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-outline">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="dinas-status-chart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <div class="card-header">
                        <h3>Kategori Data</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="data-category-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Activity Feed & Quick Actions -->
            <div class="bottom-grid">
                <div class="activity-card">
                    <div class="card-header">
                        <h3>Aktivitas Terbaru</h3>
                        <a href="#" class="view-all">Lihat Semua</a>
                    </div>
                    <div class="activity-list" id="activity-list">
                        <!-- Activities will be populated by JavaScript -->
                    </div>
                </div>
                
                <div class="quick-actions-card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="quick-actions">
                        <button class="quick-action-btn">
                            <i class="fas fa-upload"></i>
                            <span>Upload Data</span>
                        </button>
                        <button class="quick-action-btn">
                            <i class="fas fa-file-alt"></i>
                            <span>Generate Report</span>
                        </button>
                        <button class="quick-action-btn">
                            <i class="fas fa-plus"></i>
                            <span>New Discussion</span>
                        </button>
                        <button class="quick-action-btn">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Add Event</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other pages will be implemented with JavaScript -->
        <div class="page" id="data-management-page">
            <div class="page-header">
                <h1>Data Management</h1>
                <p>Upload, validasi, dan kelola data RKPD</p>
            </div>
            <div class="coming-soon">
                <i class="fas fa-database"></i>
                <h2>Data Management</h2>
                <p>Fitur sedang dalam pengembangan</p>
            </div>
        </div>

        <div class="page" id="reports-page">
            <div class="page-header">
                <h1>Laporan & Analisis</h1>
                <p>Generate dan export laporan RKPD</p>
            </div>
            <div class="coming-soon">
                <i class="fas fa-chart-bar"></i>
                <h2>Reports & Analytics</h2>
                <p>Fitur sedang dalam pengembangan</p>
            </div>
        </div>

        <!-- Add more pages as needed -->
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="#dashboard" class="bottom-nav-item active" data-page="dashboard">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="#data-management" class="bottom-nav-item" data-page="data-management">
            <i class="fas fa-database"></i>
            <span>Data</span>
        </a>
        <a href="#reports" class="bottom-nav-item" data-page="reports">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
        <a href="#forum" class="bottom-nav-item" data-page="forum">
            <i class="fas fa-comments"></i>
            <span>Forum</span>
        </a>
        <a href="#settings" class="bottom-nav-item" data-page="settings">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
    </nav>

    <script src="js/utils.js"></script>
    <script src="js/charts.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>'''

# Simpan file dashboard.html
with open('dashboard.html', 'w', encoding='utf-8') as f:
    f.write(dashboard_html)

print("✅ dashboard.html created successfully")
print("📊 Dashboard dengan KPI cards, charts, sidebar navigation, dan mobile bottom nav")