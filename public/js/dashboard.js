class DashboardManager {
  constructor() {
    this.currentPage = 'dashboard'; // Menyimpan halaman aktif
    this.sidebarCollapsed = false;
    this.charts = {}; 
    this.activities = [];
    this.init();
  }

  // Inisialisasi dashboard
  init() {
    this.checkAuthentication();
    this.initializeUI();
    this.bindEvents();
    this.loadDashboardData();
    this.renderActivityFeed();
  }

  // Cek apakah user sudah terautentikasi
  checkAuthentication() {
    if (!authHandler.isAuthenticated()) {
      window.location.href = 'login.html';
      return;
    }

    const currentUser = authHandler.getCurrentUser();
    this.updateUserInfo(currentUser);
  }

  // Update informasi user di UI
  updateUserInfo(user) {
    const userName = document.getElementById('user-name');
    const sidebarUserName = document.getElementById('sidebar-user-name');
    const sidebarUserRole = document.getElementById('sidebar-user-role');

    if (userName) userName.textContent = user.name;
    if (sidebarUserName) sidebarUserName.textContent = user.name;
    if (sidebarUserRole) sidebarUserRole.textContent = user.position;
  }

  // Inisialisasi komponen UI
  initializeUI() {
    this.initializeSidebar();
    this.initializeNavigation();
    this.initializeDropdowns();
    UIComponents.renderNotifications();
  }

  // Inisialisasi sidebar
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

        // Menyimpan status sidebar pada localStorage
        localStorage.setItem('sipandu_sidebar_collapsed', this.sidebarCollapsed);
      });
    }

    // Mengembalikan status sidebar dari penyimpanan lokal
    const savedState = localStorage.getItem('sipandu_sidebar_collapsed');
    if (savedState === 'true') {
      this.sidebarCollapsed = true;
      sidebar.classList.add('collapsed');
    }
  }

  // Inisialisasi navigasi
  initializeNavigation() {
    // Sidebar navigation
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const page = link.dataset.page; // Mendapatkan nama halaman dari data-page
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

  // Fungsi untuk beralih halaman
  switchPage(pageName) {
    const pages = document.querySelectorAll('.page');
    pages.forEach(page => page.classList.remove('active')); // Sembunyikan semua halaman

    const targetPage = document.getElementById(pageName + '-page'); // Mendapatkan halaman target berdasarkan ID
    if (targetPage) {
      targetPage.classList.add('active'); // Menampilkan halaman target
      this.currentPage = pageName;

      // Inisialisasi khusus untuk halaman dashboard
      if (pageName === 'dashboard') {
        this.refreshCharts(); // Refresh grafik jika halaman dashboard yang aktif
      }
    }
  }

  // Mengupdate status aktif untuk item navigasi di sidebar
  updateActiveNavigation(activeLink) {
    const navItems = document.querySelectorAll('.sidebar .nav-item');
    navItems.forEach(item => item.classList.remove('active')); // Menghapus status aktif dari semua item
    activeLink.closest('.nav-item').classList.add('active'); // Menambahkan status aktif pada item yang dipilih
  }

  // Mengupdate status aktif untuk item navigasi di mobile bottom nav
  updateActiveBottomNav(activeItem) {
    const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
    bottomNavItems.forEach(item => item.classList.remove('active')); // Menghapus status aktif dari semua item
    activeItem.classList.add('active'); // Menambahkan status aktif pada item yang dipilih
  }

  // Menginisialisasi dropdown (untuk menu notifikasi dan user)
  initializeDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[data-dropdown]');
    dropdownTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdownId = trigger.dataset.dropdown;
        const dropdown = document.getElementById(dropdownId);

        if (dropdown) {
          // Menutup dropdown lainnya
          document.querySelectorAll('.dropdown.show').forEach(d => {
            if (d !== dropdown) d.classList.remove('show');
          });
          dropdown.classList.toggle('show');
        }
      });
    });

    // Menutup dropdown saat klik di luar
    document.addEventListener('click', () => {
      document.querySelectorAll('.dropdown.show').forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    });

    // Dropdown untuk notifikasi dan user menu
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

  bindEvents() {
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.handleLogout();
      });
    }

    const quickActionBtns = document.querySelectorAll('.quick-action-btn');
    quickActionBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const action = btn.querySelector('span').textContent;
        Utils.showToast(`${action} feature coming soon!`, 'info');
      });
    });

    // Pencarian data
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

  handleLogout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
      authHandler.logout();
    }
  }

  performSearch(query) {
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

  loadDashboardData() {
    setTimeout(() => {
      this.updateKPICards();
      this.initializeCharts();
    }, 500);
  }

  updateKPICards() {
    const stats = this.calculateStatistics();

    this.animateKPI('total-dinas', stats.totalDinas);
    this.animateKPI('complete-data', stats.completeData);
    this.animateKPI('avg-progress', stats.avgProgress, '%');
    this.animateKPI('pending-reviews', stats.pendingReviews);
  }

  calculateStatistics() {
    const totalDinas = dinasData.length;
    const completeData = dinasData.filter(d => d.status === 'Complete').length;
    const avgProgress = Math.round(dinasData.reduce((sum, d) => sum + d.progress, 0) / totalDinas);
    const pendingReviews = dinasData.filter(d => d.status === 'Pending').length + 
                          dinasData.filter(d => d.status === 'Progress').length;

    return { totalDinas, completeData, avgProgress, pendingReviews };
  }

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

      const easedProgress = 1 - Math.pow(1 - progress, 3);
      const currentValue = Math.round(startValue + (finalValue - startValue) * easedProgress);

      element.textContent = currentValue + suffix;

      if (progress < 1) {
        requestAnimationFrame(animate);
      }
    };

    requestAnimationFrame(animate);
  }

  initializeCharts() {
    if (typeof ChartManager !== 'undefined') {
      this.chartManager = new ChartManager();
      this.chartManager.initializeAllCharts();
    }
  }

  refreshCharts() {
    if (this.chartManager) {
      this.chartManager.refreshAllCharts();
    }
  }

  renderActivityFeed() {
    const activities = [
      { id: 1, type: 'upload', title: 'Data Upload', description: 'Perdagangan mengupload data triwulan III', time: '5 menit lalu', icon: 'upload', color: '#10b981' },
      { id: 2, type: 'approval', title: 'Data Approved', description: 'Data Perikanan telah disetujui oleh Bappeda', time: '2 jam lalu', icon: 'check-circle', color: '#22c55e' },
      { id: 3, type: 'discussion', title: 'New Discussion', description: 'Forum diskusi baru: Standarisasi Format Data', time: '4 jam lalu', icon: 'comments', color: '#3b82f6' },
      { id: 4, type: 'deadline', title: 'Reminder', description: 'Deadline pengumpulan data: 3 hari lagi', time: '1 hari lalu', icon: 'exclamation-triangle', color: '#f59e0b' },
      { id: 5, type: 'system', title: 'System Update', description: 'SIPANDU DATA telah diperbarui ke versi 1.1', time: '2 hari lalu', icon: 'cog', color: '#64748b' }
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

  handleMobileResize() {
    const isMobile = window.innerWidth <= 768;
    const sidebar = document.getElementById('sidebar');

    if (isMobile && sidebar) {
      sidebar.classList.add('collapsed');
    }
  }
}
