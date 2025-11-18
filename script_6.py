# Membuat file mobile.css
mobile_css = '''/* SIPANDU DATA - Mobile Responsive Styles */

/* Mobile Breakpoints */
@media (max-width: 768px) {
  
  /* Container adjustments */
  .container {
    padding: 0 var(--space-3);
  }

  /* Header Navigation */
  .nav-container {
    padding: 0 var(--space-3);
  }

  .nav-menu {
    position: fixed;
    top: 100%;
    left: 0;
    width: 100%;
    background: white;
    flex-direction: column;
    gap: 0;
    padding: var(--space-6) var(--space-4);
    box-shadow: var(--shadow-lg);
    border-top: 1px solid var(--gray-200);
    transform: translateY(0);
    transition: transform var(--transition-base);
    z-index: 1000;
  }

  .nav-menu.show {
    transform: translateY(-100%);
  }

  .nav-menu li {
    width: 100%;
    margin-bottom: var(--space-4);
  }

  .nav-menu .nav-link {
    display: block;
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius);
  }

  .nav-toggle {
    display: block;
  }

  /* Hero Section */
  .hero {
    padding: calc(80px + var(--space-12)) 0 var(--space-12);
  }

  .hero-container {
    grid-template-columns: 1fr;
    gap: var(--space-12);
    text-align: center;
  }

  .hero-title {
    font-size: var(--font-size-3xl);
  }

  .hero-subtitle {
    font-size: var(--font-size-base);
  }

  .hero-stats {
    justify-content: center;
    gap: var(--space-6);
  }

  .hero-actions {
    flex-direction: column;
    align-items: center;
    gap: var(--space-3);
  }

  .hero-actions .btn {
    width: 100%;
    max-width: 280px;
  }

  .hero-graphic {
    width: 200px;
    height: 200px;
    font-size: 80px;
  }

  /* Section spacing */
  section {
    padding: var(--space-12) 0;
  }

  .section-header {
    margin-bottom: var(--space-12);
  }

  .section-header h2 {
    font-size: var(--font-size-2xl);
  }

  /* About Section */
  .about-grid {
    grid-template-columns: 1fr;
    gap: var(--space-8);
  }

  .developer-info {
    flex-direction: column;
    text-align: center;
  }

  .developer-avatar {
    align-self: center;
  }

  /* Features Grid */
  .features-grid {
    grid-template-columns: 1fr;
    gap: var(--space-6);
  }

  .feature-card {
    padding: var(--space-6);
  }

  /* Dinas Grid */
  .dinas-grid {
    grid-template-columns: 1fr;
    gap: var(--space-4);
  }

  /* Footer */
  .footer-content {
    grid-template-columns: 1fr;
    gap: var(--space-8);
    text-align: center;
  }

  /* Dashboard Mobile Styles */
  .dashboard-page {
    padding-bottom: 80px; /* Account for bottom nav */
  }

  /* Top Navigation Mobile */
  .top-nav {
    padding: 0 var(--space-3);
  }

  .nav-center {
    display: none; /* Hide search on mobile */
  }

  .nav-right {
    gap: var(--space-3);
  }

  .user-name {
    display: none; /* Hide username on mobile */
  }

  /* Sidebar Mobile */
  .sidebar {
    width: 100%;
    transform: translateX(-100%);
  }

  .sidebar.show {
    transform: translateX(0);
  }

  .main-content {
    margin-left: 0;
    padding: var(--space-4);
  }

  /* KPI Grid Mobile */
  .kpi-grid {
    grid-template-columns: 1fr 1fr;
    gap: var(--space-4);
  }

  .kpi-card {
    padding: var(--space-4);
    flex-direction: column;
    text-align: center;
    gap: var(--space-3);
  }

  .kpi-icon {
    width: 50px;
    height: 50px;
    font-size: var(--font-size-lg);
  }

  .kpi-number {
    font-size: var(--font-size-2xl);
  }

  /* Charts Grid Mobile */
  .charts-grid {
    grid-template-columns: 1fr;
    gap: var(--space-4);
  }

  .chart-card .card-body {
    padding: var(--space-4);
    height: 250px;
  }

  /* Bottom Grid Mobile */
  .bottom-grid {
    grid-template-columns: 1fr;
    gap: var(--space-4);
  }

  /* Activity List Mobile */
  .activity-item {
    flex-direction: column;
    gap: var(--space-2);
    align-items: flex-start;
  }

  .activity-icon {
    align-self: flex-start;
  }

  /* Quick Actions Mobile */
  .quick-actions {
    grid-template-columns: 1fr 1fr;
    gap: var(--space-3);
  }

  .quick-action-btn {
    flex-direction: column;
    padding: var(--space-3);
    text-align: center;
    gap: var(--space-2);
  }

  .quick-action-btn span {
    font-size: var(--font-size-xs);
  }

  /* Show Mobile Bottom Navigation */
  .mobile-bottom-nav {
    display: flex;
  }

  /* Login Page Mobile */
  .login-container {
    padding: var(--space-4);
  }

  .login-card {
    padding: var(--space-6);
    margin: var(--space-4) 0;
  }

  .login-logo h1 {
    font-size: var(--font-size-2xl);
  }

  .demo-accounts {
    gap: var(--space-3);
  }

  .demo-account {
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: var(--space-3);
    padding: var(--space-4);
  }

  .demo-info {
    text-align: center;
  }
}

/* Small Mobile (320px - 480px) */
@media (max-width: 480px) {
  .kpi-grid {
    grid-template-columns: 1fr;
  }

  .hero-stats {
    flex-direction: column;
    gap: var(--space-4);
  }

  .quick-actions {
    grid-template-columns: 1fr;
  }

  .hero-title {
    font-size: var(--font-size-2xl);
  }

  .section-header h2 {
    font-size: var(--font-size-xl);
  }

  .page-header h1 {
    font-size: var(--font-size-2xl);
  }
}

/* Tablet Styles (768px - 1024px) */
@media (min-width: 768px) and (max-width: 1024px) {
  .hero-container {
    grid-template-columns: 1fr 1fr;
    gap: var(--space-12);
  }

  .features-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .dinas-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .charts-grid {
    grid-template-columns: 1fr 1fr;
  }

  .bottom-grid {
    grid-template-columns: 1fr;
  }

  /* Dashboard tablet adjustments */
  .sidebar {
    width: 240px;
  }

  .main-content {
    margin-left: 240px;
  }

  .kpi-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Touch Interactions */
@media (pointer: coarse) {
  /* Larger touch targets */
  .btn {
    min-height: 48px;
    padding: var(--space-4) var(--space-6);
  }

  .nav-link {
    min-height: 48px;
    display: flex;
    align-items: center;
  }

  .quick-action-btn {
    min-height: 64px;
  }

  .bottom-nav-item {
    min-height: 64px;
    justify-content: center;
  }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
  :root {
    --gray-50: #0f172a;
    --gray-100: #1e293b;
    --gray-200: #334155;
    --gray-300: #475569;
    --gray-400: #64748b;
    --gray-500: #94a3b8;
    --gray-600: #cbd5e1;
    --gray-700: #e2e8f0;
    --gray-800: #f1f5f9;
    --gray-900: #f8fafc;
  }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }

  .hero-graphic {
    animation: none;
  }
}

/* High Contrast Support */
@media (prefers-contrast: high) {
  .btn-primary {
    border: 2px solid var(--primary-blue-dark);
  }

  .btn-secondary {
    border-width: 3px;
  }

  .feature-card,
  .kpi-card,
  .chart-card {
    border-width: 2px;
  }
}

/* Print Styles */
@media print {
  .header,
  .top-nav,
  .sidebar,
  .mobile-bottom-nav,
  .nav-toggle,
  .notifications,
  .user-menu {
    display: none !important;
  }

  .main-content {
    margin: 0 !important;
    padding: 0 !important;
  }

  .hero {
    padding: var(--space-8) 0 !important;
  }

  .charts-grid,
  .kpi-grid {
    grid-template-columns: 1fr 1fr !important;
  }

  body {
    background: white !important;
    color: black !important;
  }
}'''

# Simpan file mobile.css
with open('mobile.css', 'w', encoding='utf-8') as f:
    f.write(mobile_css)

print("✅ mobile.css created successfully")
print("📱 Mobile-responsive styles dengan breakpoints, touch support, dan accessibility")