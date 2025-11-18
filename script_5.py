# Membuat file dashboard.css
dashboard_css = '''/* SIPANDU DATA - Dashboard Styles */

/* Dashboard Layout */
.dashboard-page {
  margin: 0;
  padding: 0;
  background: var(--gray-50);
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Top Navigation */
.top-nav {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 64px;
  background: white;
  border-bottom: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 var(--space-6);
  z-index: 1000;
  box-shadow: var(--shadow-sm);
}

.nav-left {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.sidebar-toggle {
  width: 40px;
  height: 40px;
  background: var(--gray-100);
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--gray-600);
  transition: all var(--transition-fast);
}

.sidebar-toggle:hover {
  background: var(--gray-200);
  color: var(--gray-800);
}

.nav-center {
  flex: 1;
  max-width: 500px;
  margin: 0 var(--space-8);
}

.search-box {
  position: relative;
  width: 100%;
}

.search-box i {
  position: absolute;
  left: var(--space-4);
  top: 50%;
  transform: translateY(-50%);
  color: var(--gray-400);
}

.search-box input {
  width: 100%;
  padding: var(--space-3) var(--space-4) var(--space-3) var(--space-12);
  border: 1px solid var(--gray-300);
  border-radius: var(--radius-full);
  background: var(--gray-50);
  font-size: var(--font-size-sm);
  transition: all var(--transition-fast);
}

.search-box input:focus {
  outline: none;
  border-color: var(--primary-blue);
  background: white;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.nav-right {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.nav-item {
  position: relative;
  cursor: pointer;
}

.notifications {
  width: 40px;
  height: 40px;
  background: var(--gray-100);
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--gray-600);
  transition: all var(--transition-fast);
}

.notifications:hover {
  background: var(--gray-200);
}

.notifications .badge {
  position: absolute;
  top: -4px;
  right: -4px;
  background: var(--error-red);
  color: white;
  font-size: var(--font-size-xs);
  font-weight: 600;
  padding: 2px 6px;
  border-radius: var(--radius-full);
  min-width: 18px;
  text-align: center;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-2) var(--space-4);
  background: var(--gray-100);
  border-radius: var(--radius-lg);
  transition: all var(--transition-fast);
}

.user-menu:hover {
  background: var(--gray-200);
}

.user-avatar {
  width: 32px;
  height: 32px;
  background: var(--primary-blue);
  color: white;
  border-radius: var(--radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: var(--font-size-sm);
}

.user-name {
  font-weight: 500;
  color: var(--gray-800);
  font-size: var(--font-size-sm);
}

/* Dropdowns */
.dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: var(--space-2);
  background: white;
  border: 1px solid var(--gray-200);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  min-width: 250px;
  z-index: 1100;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all var(--transition-fast);
}

.dropdown.show {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-header {
  padding: var(--space-4);
  border-bottom: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.dropdown-header h3 {
  font-weight: 600;
  font-size: var(--font-size-base);
}

.mark-all-read {
  font-size: var(--font-size-xs);
  color: var(--primary-blue);
  background: none;
  border: none;
  cursor: pointer;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-3) var(--space-4);
  color: var(--gray-800);
  font-size: var(--font-size-sm);
  transition: background var(--transition-fast);
}

.dropdown-item:hover {
  background: var(--gray-50);
}

.dropdown-divider {
  height: 1px;
  background: var(--gray-200);
  margin: var(--space-2) 0;
}

/* Sidebar */
.sidebar {
  position: fixed;
  top: 64px;
  left: 0;
  width: 280px;
  height: calc(100vh - 64px);
  background: white;
  border-right: 1px solid var(--gray-200);
  z-index: 900;
  transform: translateX(0);
  transition: transform var(--transition-base);
  overflow-y: auto;
}

.sidebar.collapsed {
  transform: translateX(-280px);
}

.sidebar-header {
  padding: var(--space-6) var(--space-6) var(--space-4);
  border-bottom: 1px solid var(--gray-200);
}

.user-info {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.user-info .user-avatar {
  width: 48px;
  height: 48px;
  font-size: var(--font-size-lg);
}

.user-details h4 {
  font-weight: 600;
  color: var(--gray-900);
  margin-bottom: var(--space-1);
}

.user-details p {
  color: var(--gray-600);
  font-size: var(--font-size-sm);
}

/* Sidebar Navigation */
.sidebar-nav {
  padding: var(--space-4) 0;
}

.nav-menu {
  list-style: none;
}

.nav-item {
  margin: 0 var(--space-4) var(--space-1);
}

.nav-item .nav-link {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-3) var(--space-4);
  color: var(--gray-600);
  font-weight: 500;
  border-radius: var(--radius);
  transition: all var(--transition-fast);
}

.nav-item .nav-link:hover {
  background: var(--gray-100);
  color: var(--gray-800);
}

.nav-item.active .nav-link {
  background: var(--primary-blue);
  color: white;
}

.nav-item .nav-link i {
  width: 20px;
  text-align: center;
}

/* Main Content */
.main-content {
  margin-left: 280px;
  margin-top: 64px;
  padding: var(--space-8);
  min-height: calc(100vh - 64px);
  transition: margin-left var(--transition-base);
}

.sidebar.collapsed ~ .main-content {
  margin-left: 0;
}

/* Page Styles */
.page {
  display: none;
}

.page.active {
  display: block;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.page-header {
  margin-bottom: var(--space-8);
}

.page-header h1 {
  font-size: var(--font-size-3xl);
  font-weight: 700;
  color: var(--gray-900);
  margin-bottom: var(--space-2);
}

.page-header p {
  color: var(--gray-600);
  font-size: var(--font-size-lg);
}

/* KPI Cards */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: var(--space-6);
  margin-bottom: var(--space-8);
}

.kpi-card {
  background: white;
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  gap: var(--space-4);
  transition: all var(--transition-fast);
}

.kpi-card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.kpi-icon {
  width: 60px;
  height: 60px;
  background: var(--primary-blue);
  color: white;
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: var(--font-size-xl);
  flex-shrink: 0;
}

.kpi-icon.success {
  background: var(--success-green);
}

.kpi-icon.warning {
  background: var(--warning-amber);
}

.kpi-icon.info {
  background: var(--info-blue);
}

.kpi-content {
  flex: 1;
}

.kpi-number {
  font-size: var(--font-size-3xl);
  font-weight: 700;
  color: var(--gray-900);
  margin-bottom: var(--space-1);
}

.kpi-label {
  color: var(--gray-600);
  font-size: var(--font-size-sm);
  margin-bottom: var(--space-2);
}

.kpi-trend {
  display: flex;
  align-items: center;
  gap: var(--space-1);
  font-size: var(--font-size-xs);
  font-weight: 600;
}

.kpi-trend.positive {
  color: var(--success-green);
}

.kpi-trend.negative {
  color: var(--error-red);
}

/* Charts Grid */
.charts-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: var(--space-6);
  margin-bottom: var(--space-8);
}

.chart-card {
  background: white;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
  overflow: hidden;
}

.chart-card .card-header {
  padding: var(--space-4) var(--space-6);
  border-bottom: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.chart-card .card-header h3 {
  font-weight: 600;
  color: var(--gray-900);
}

.card-actions {
  display: flex;
  gap: var(--space-2);
}

.chart-card .card-body {
  padding: var(--space-6);
  height: 300px;
  position: relative;
}

.chart-card canvas {
  max-width: 100%;
  max-height: 100%;
}

/* Bottom Grid */
.bottom-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: var(--space-6);
}

.activity-card,
.quick-actions-card {
  background: white;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
}

.card-header {
  padding: var(--space-4) var(--space-6);
  border-bottom: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.card-header h3 {
  font-weight: 600;
  color: var(--gray-900);
}

.view-all {
  color: var(--primary-blue);
  font-size: var(--font-size-sm);
  font-weight: 500;
}

/* Activity List */
.activity-list {
  padding: var(--space-6);
}

.activity-item {
  display: flex;
  gap: var(--space-4);
  padding: var(--space-4) 0;
  border-bottom: 1px solid var(--gray-100);
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: var(--radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: var(--font-size-sm);
  flex-shrink: 0;
}

.activity-content {
  flex: 1;
}

.activity-title {
  font-weight: 500;
  color: var(--gray-900);
  margin-bottom: var(--space-1);
}

.activity-description {
  font-size: var(--font-size-sm);
  color: var(--gray-600);
  margin-bottom: var(--space-1);
}

.activity-time {
  font-size: var(--font-size-xs);
  color: var(--gray-400);
}

/* Quick Actions */
.quick-actions {
  padding: var(--space-6);
  display: grid;
  gap: var(--space-3);
}

.quick-action-btn {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-4);
  background: var(--gray-50);
  border: 1px solid var(--gray-200);
  border-radius: var(--radius);
  transition: all var(--transition-fast);
  cursor: pointer;
  color: var(--gray-700);
}

.quick-action-btn:hover {
  background: var(--gray-100);
  border-color: var(--primary-blue);
  color: var(--primary-blue);
}

.quick-action-btn i {
  color: var(--primary-blue);
}

/* Coming Soon */
.coming-soon {
  text-align: center;
  padding: var(--space-16);
  color: var(--gray-600);
}

.coming-soon i {
  font-size: 4rem;
  margin-bottom: var(--space-4);
  color: var(--gray-400);
}

.coming-soon h2 {
  font-size: var(--font-size-2xl);
  font-weight: 600;
  margin-bottom: var(--space-2);
  color: var(--gray-800);
}

/* Mobile Bottom Navigation */
.mobile-bottom-nav {
  display: none;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: white;
  border-top: 1px solid var(--gray-200);
  padding: var(--space-2) var(--space-4);
  z-index: 1000;
}

.bottom-nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-1);
  padding: var(--space-2);
  color: var(--gray-600);
  font-size: var(--font-size-xs);
  text-decoration: none;
  flex: 1;
  transition: color var(--transition-fast);
}

.bottom-nav-item.active,
.bottom-nav-item:hover {
  color: var(--primary-blue);
}

.bottom-nav-item i {
  font-size: var(--font-size-lg);
}'''

# Simpan file dashboard.css
with open('dashboard.css', 'w', encoding='utf-8') as f:
    f.write(dashboard_css)

print("✅ dashboard.css created successfully")
print("📊 Dashboard-specific styles dengan modern layout dan interactive components")