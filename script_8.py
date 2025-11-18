# Membuat file dashboard.js
dashboard_js = '''// SIPANDU DATA - Dashboard JavaScript
// Dashboard-specific functionality

class DashboardManager {
  constructor() {
    this.currentPage = 'dashboard';
    this.sidebarCollapsed = false;
    this.charts = {};
    this.activities = [];
    this.init();
  }

  init() {
    this.checkAuthentication();
    this.initializeUI();
    this.bindEvents();
    this.loadDashboardData();
    this.renderActivityFeed();
  }

  // Check if user is authenticated
  checkAuthentication() {
    if (!authHandler.isAuthenticated()) {
      window.location.href = 'login.html';
      return;
    }

    // Update user info in UI
    const currentUser = authHandler.getCurrentUser();
    this.updateUserInfo(currentUser);
  }

  // Update user information in the UI
  updateUserInfo(user) {
    const userName = document.getElementById('user-name');
    const sidebarUserName = document.getElementById('sidebar-user-name');
    const sidebarUserRole = document.getElementById('sidebar-user-role');

    if (userName) userName.textContent = user.name;
    if (sidebarUserName) sidebarUserName.textContent = user.name;
    if (sidebarUserRole) sidebarUserRole.textContent = user.position;
  }

  // Initialize UI components
  initializeUI() {
    this.initializeSidebar();
    this.initializeNavigation();
    this.initializeDropdowns();
    UIComponents.renderNotifications();
  }

  // Initialize sidebar functionality
  initializeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', () => {
        this.sidebarCollapsed = !this.sidebarCollapsed;
        
        if (this.sidebarCollapsed) {
          sidebar.classList.add('collapsed');
        } else {
          sidebar.classList.remove('collapsed');
        }

        // Store preference
        localStorage.setItem('sipandu_sidebar_collapsed', this.sidebarCollapsed);
      });
    }

    // Restore sidebar state
    const savedState = localStorage.getItem('sipandu_sidebar_collapsed');
    if (savedState === 'true') {
      this.sidebarCollapsed = true;
      sidebar.classList.add('collapsed');
    }
  }

  // Initialize navigation
  initializeNavigation() {
    // Sidebar navigation
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const page = link.dataset.page;
        if (page) {
          this.switchPage(page);
          this.updateActiveNavigation(link);
        }
      });
    });

    // Mobile bottom navigation
    const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
    bottomNavItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const page = item.dataset.page;
        if (page) {
          this.switchPage(page);
          this.updateActiveBottomNav(item);
        }
      });
    });
  }

  // Switch between pages
  switchPage(pageName) {
    // Hide all pages
    const pages = document.querySelectorAll('.page');
    pages.forEach(page => page.classList.remove('active'));

    // Show target page
    const targetPage = document.getElementById(pageName + '-page');
    if (targetPage) {
      targetPage.classList.add('active');
      this.currentPage = pageName;

      // Page-specific initialization
      if (pageName === 'dashboard') {
        this.refreshCharts();
      }
    }
  }

  // Update active navigation state
  updateActiveNavigation(activeLink) {
    const navItems = document.querySelectorAll('.sidebar .nav-item');
    navItems.forEach(item => item.classList.remove('active'));
    activeLink.closest('.nav-item').classList.add('active');
  }

  // Update active bottom navigation state
  updateActiveBottomNav(activeItem) {
    const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
    bottomNavItems.forEach(item => item.classList.remove('active'));
    activeItem.classList.add('active');
  }

  // Initialize dropdown functionality
  initializeDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[data-dropdown]');
    
    dropdownTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdownId = trigger.dataset.dropdown;
        const dropdown = document.getElementById(dropdownId);
        
        if (dropdown) {
          // Close other dropdowns
          document.querySelectorAll('.dropdown.show').forEach(d => {
            if (d !== dropdown) d.classList.remove('show');
          });
          
          dropdown.classList.toggle('show');
        }
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
      document.querySelectorAll('.dropdown.show').forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    });

    // Notification and user menu dropdowns
    const notifications = document.getElementById('notifications');
    const userMenu = document.getElementById('user-menu');

    if (notifications) {
      notifications.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdown = document.getElementById('notification-dropdown');
        if (dropdown) {
          dropdown.classList.toggle('show');
          document.getElementById('user-dropdown')?.classList.remove('show');
        }
      });
    }

    if (userMenu) {
      userMenu.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdown = document.getElementById('user-dropdown');
        if (dropdown) {
          dropdown.classList.toggle('show');
          document.getElementById('notification-dropdown')?.classList.remove('show');
        }
      });
    }
  }

  // Bind events
  bindEvents() {
    // Logout button
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.handleLogout();
      });
    }

    // Quick action buttons
    const quickActionBtns = document.querySelectorAll('.quick-action-btn');
    quickActionBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const action = btn.querySelector('span').textContent;
        Utils.showToast(`${action} feature coming soon!`, 'info');
      });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
      searchInput.addEventListener('input', Utils.debounce((e) => {
        const query = e.target.value.toLowerCase();
        if (query.length > 2) {
          this.performSearch(query);
        }
      }, 300));
    }
  }

  // Handle logout
  handleLogout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
      authHandler.logout();
    }
  }

  // Perform search
  performSearch(query) {
    // Simple search implementation
    const results = dinasData.filter(dinas => 
      dinas.name.toLowerCase().includes(query) ||
      dinas.fullName.toLowerCase().includes(query)
    );

    if (results.length > 0) {
      Utils.showToast(`Found ${results.length} results for "${query}"`, 'success');
    } else {
      Utils.showToast(`No results found for "${query}"`, 'info');
    }
  }

  // Load dashboard data
  loadDashboardData() {
    // Simulate API call
    setTimeout(() => {
      this.updateKPICards();
      this.initializeCharts();
    }, 500);
  }

  // Update KPI cards with animation
  updateKPICards() {
    const stats = this.calculateStatistics();
    
    // Animate KPI numbers
    this.animateKPI('total-dinas', stats.totalDinas);
    this.animateKPI('complete-data', stats.completeData);
    this.animateKPI('avg-progress', stats.avgProgress, '%');
    this.animateKPI('pending-reviews', stats.pendingReviews);
  }

  // Calculate statistics from dinas data
  calculateStatistics() {
    const totalDinas = dinasData.length;
    const completeData = dinasData.filter(d => d.status === 'Complete').length;
    const avgProgress = Math.round(dinasData.reduce((sum, d) => sum + d.progress, 0) / totalDinas);
    const pendingReviews = dinasData.filter(d => d.status === 'Pending').length + 
                          dinasData.filter(d => d.status === 'Progress').length;

    return { totalDinas, completeData, avgProgress, pendingReviews };
  }

  // Animate KPI numbers
  animateKPI(elementId, finalValue, suffix = '') {
    const element = document.getElementById(elementId);
    if (!element) return;

    const startValue = 0;
    const duration = 1500;
    const startTime = Date.now();

    const animate = () => {
      const currentTime = Date.now();
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Easing function
      const easedProgress = 1 - Math.pow(1 - progress, 3);
      const currentValue = Math.round(startValue + (finalValue - startValue) * easedProgress);

      element.textContent = currentValue + suffix;

      if (progress < 1) {
        requestAnimationFrame(animate);
      }
    };

    requestAnimationFrame(animate);
  }

  // Initialize charts
  initializeCharts() {
    // Initialize charts if ChartManager is available
    if (typeof ChartManager !== 'undefined') {
      this.chartManager = new ChartManager();
      this.chartManager.initializeAllCharts();
    }
  }

  // Refresh charts
  refreshCharts() {
    if (this.chartManager) {
      this.chartManager.refreshAllCharts();
    }
  }

  // Render activity feed
  renderActivityFeed() {
    const activities = [
      {
        id: 1,
        type: 'upload',
        title: 'Data Upload',
        description: 'Perdagangan mengupload data triwulan III',
        time: '5 menit lalu',
        icon: 'upload',
        color: '#10b981'
      },
      {
        id: 2,
        type: 'approval',
        title: 'Data Approved',
        description: 'Data Perikanan telah disetujui oleh Bappeda',
        time: '2 jam lalu',
        icon: 'check-circle',
        color: '#22c55e'
      },
      {
        id: 3,
        type: 'discussion',
        title: 'New Discussion',
        description: 'Forum diskusi baru: Standarisasi Format Data',
        time: '4 jam lalu',
        icon: 'comments',
        color: '#3b82f6'
      },
      {
        id: 4,
        type: 'deadline',
        title: 'Reminder',
        description: 'Deadline pengumpulan data: 3 hari lagi',
        time: '1 hari lalu',
        icon: 'exclamation-triangle',
        color: '#f59e0b'
      },
      {
        id: 5,
        type: 'system',
        title: 'System Update',
        description: 'SIPANDU DATA telah diperbarui ke versi 1.1',
        time: '2 hari lalu',
        icon: 'cog',
        color: '#64748b'
      }
    ];

    const activityList = document.getElementById('activity-list');
    if (activityList) {
      activityList.innerHTML = activities.map(activity => `
        <div class="activity-item">
          <div class="activity-icon" style="background-color: ${activity.color}">
            <i class="fas fa-${activity.icon}"></i>
          </div>
          <div class="activity-content">
            <div class="activity-title">${activity.title}</div>
            <div class="activity-description">${activity.description}</div>
            <div class="activity-time">${activity.time}</div>
          </div>
        </div>
      `).join('');
    }
  }

  // Mobile responsive handling
  handleMobileResize() {
    const isMobile = window.innerWidth <= 768;
    const sidebar = document.getElementById('sidebar');
    
    if (isMobile && sidebar) {
      sidebar.classList.add('collapsed');
    }
  }
}

// Auto-refresh functionality
class AutoRefresh {
  constructor(dashboardManager) {
    this.dashboardManager = dashboardManager;
    this.interval = null;
    this.refreshRate = 30000; // 30 seconds
  }

  start() {
    this.interval = setInterval(() => {
      if (this.dashboardManager.currentPage === 'dashboard') {
        this.dashboardManager.loadDashboardData();
        console.log('Dashboard auto-refreshed');
      }
    }, this.refreshRate);
  }

  stop() {
    if (this.interval) {
      clearInterval(this.interval);
      this.interval = null;
    }
  }
}

// Initialize dashboard when DOM is ready
let dashboardManager;
let autoRefresh;

document.addEventListener('DOMContentLoaded', () => {
  // Only initialize if we're on the dashboard page
  if (window.location.pathname.includes('dashboard.html')) {
    dashboardManager = new DashboardManager();
    
    // Start auto-refresh
    autoRefresh = new AutoRefresh(dashboardManager);
    autoRefresh.start();

    // Handle window resize
    window.addEventListener('resize', Utils.debounce(() => {
      dashboardManager.handleMobileResize();
    }, 250));

    // Handle visibility change (pause auto-refresh when tab is not active)
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        autoRefresh.stop();
      } else {
        autoRefresh.start();
      }
    });

    console.log('Dashboard initialized successfully');
  }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
  if (autoRefresh) {
    autoRefresh.stop();
  }
});'''

# Simpan file dashboard.js
with open('dashboard.js', 'w', encoding='utf-8') as f:
    f.write(dashboard_js)

print("✅ dashboard.js created successfully")
print("📊 Dashboard functionality dengan sidebar navigation, KPI animation, dan auto-refresh")