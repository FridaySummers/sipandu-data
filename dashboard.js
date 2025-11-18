// SIPANDU DATA - Dashboard JavaScript
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
        const href = link.getAttribute('href');
        // Jika href menuju file lain, biarkan browser melakukan navigasi normal
        if (href && !href.startsWith('#')) {
          return;
        }
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
        const href = item.getAttribute('href');
        if (href && !href.startsWith('#')) {
          return;
        }
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
      } else if (pageName === 'data-management') {
        this.initializeDataManagement();
      } else if (pageName === 'reports') {
        this.initializeReports();
      } else if (pageName === 'forum') {
        this.initializeForum();
      } else if (pageName === 'calendar') {
        this.initializeCalendar();
      } else if (pageName === 'dinas-status') {
        this.initializeDinasStatus();
      } else if (pageName === 'settings') {
        this.initializeSettings();
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

  initializeDataManagement() {
    const opdSelect = document.getElementById('dm-target-opd');
    if (opdSelect && opdSelect.options.length <= 1) {
      dinasData.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.name;
        opt.textContent = d.name;
        opdSelect.appendChild(opt);
      });
    }

    const dropzone = document.getElementById('dm-dropzone');
    const fileInput = document.getElementById('dm-file-input');
    const selectBtn = document.getElementById('dm-select-files');
    const fileList = document.getElementById('dm-file-list');
    const uploadBtn = document.getElementById('dm-upload-btn');

    const renderFiles = (files) => {
      fileList.innerHTML = Array.from(files).map(f => `
        <div class="file-item">
          <div class="file-meta"><span>${f.name}</span><span>${(f.size/1024).toFixed(1)} KB</span></div>
          <button class="btn btn-outline btn-sm">Hapus</button>
        </div>
      `).join('');
    };

    const handleFiles = (files) => { renderFiles(files); };

    if (selectBtn) selectBtn.onclick = () => fileInput.click();
    if (fileInput) fileInput.onchange = (e) => handleFiles(e.target.files);
    if (dropzone) {
      ['dragenter','dragover'].forEach(ev => dropzone.addEventListener(ev, (e) => { e.preventDefault(); dropzone.classList.add('drag'); }));
      ['dragleave','drop'].forEach(ev => dropzone.addEventListener(ev, (e) => { e.preventDefault(); dropzone.classList.remove('drag'); }));
      dropzone.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files));
    }

    if (uploadBtn) uploadBtn.onclick = () => {
      Utils.showToast('Upload diproses', 'success');
    };
  }

  initializeReports() {
    const fillSelects = () => {
      const opdFilter = document.getElementById('rep-opd-filter');
      if (opdFilter && opdFilter.options.length <= 1) {
        dinasData.forEach(d => {
          const opt = document.createElement('option');
          opt.value = d.name;
          opt.textContent = d.name;
          opdFilter.appendChild(opt);
        });
      }
    };
    fillSelects();

    const monthlyCtx = document.getElementById('rep-monthly-chart');
    const statusCtx = document.getElementById('rep-status-chart');
    const categoryCtx = document.getElementById('rep-category-chart');
    if (!monthlyCtx || !statusCtx || !categoryCtx) return;

    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    new Chart(monthlyCtx, { type:'line', data:{ labels:months, datasets:[{ label:'Progress', data: months.map((_,i)=> 50+Math.round(Math.sin(i)*20)), borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,0.2)', tension:0.3 }] }, options:{ responsive:true, maintainAspectRatio:false }});

    new Chart(statusCtx, { type:'bar', data:{ labels:dinasData.map(d=>d.name), datasets:[{ label:'Complete', data:dinasData.map(d=> d.status==='Complete'?1:0), backgroundColor:'#22c55e' },{ label:'Progress', data:dinasData.map(d=> d.status==='In Progress'?1:0), backgroundColor:'#f59e0b' },{ label:'Pending', data:dinasData.map(d=> d.status==='Pending'?1:0), backgroundColor:'#ef4444' }] }, options:{ responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true, stacked:true }, x:{ stacked:true } } }});

    new Chart(categoryCtx, { type:'doughnut', data:{ labels:['Keuangan','Produksi','SDM','Konsumsi'], datasets:[{ data:[30,25,20,25], backgroundColor:['#3b82f6','#10b981','#f59e0b','#64748b'] }] }, options:{ responsive:true, maintainAspectRatio:false } });

    const exportPdf = document.getElementById('rep-export-pdf');
    const exportXlsx = document.getElementById('rep-export-xlsx');
    if (exportPdf) exportPdf.onclick = () => Utils.showToast('Export PDF dibuat', 'success');
    if (exportXlsx) exportXlsx.onclick = () => Utils.showToast('Export XLSX dibuat', 'success');
  }

  initializeForum() {
    const threads = [
      { id:1, title:'Standarisasi Format Data', author:'Bappeda', replies:12, time:'2 jam lalu' },
      { id:2, title:'Deadline Triwulan III', author:'Bapenda', replies:5, time:'5 jam lalu' },
      { id:3, title:'Integrasi Portal Sektoral', author:'Perdagangan', replies:3, time:'1 hari lalu' }
    ];
    const list = document.getElementById('thread-list');
    const detail = document.getElementById('thread-detail');
    const newBtn = document.getElementById('forum-new');
    if (!list || !detail) return;
    list.innerHTML = threads.map(t=> `
      <div class="thread-item" data-id="${t.id}">
        <div>
          <div class="thread-title">${t.title}</div>
          <div class="thread-meta">${t.author} • ${t.replies} balasan • ${t.time}</div>
        </div>
        <button class="btn btn-outline btn-sm">Buka</button>
      </div>
    `).join('');
    list.querySelectorAll('.thread-item').forEach(item=>{
      item.addEventListener('click', ()=>{
        const t = threads.find(x=> x.id === parseInt(item.dataset.id));
        if (t) detail.innerHTML = `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;"><h3>${t.title}</h3><span class="thread-meta">${t.author} • ${t.time}</span></div><div>Diskusi simulasi. Balasan: ${t.replies}</div>`;
      });
    });
    if (newBtn) newBtn.onclick = () => Utils.showToast('Buat topik baru (simulasi)', 'info');
  }

  initializeCalendar() {
    const title = document.getElementById('cal-title');
    const grid = document.getElementById('calendar-month');
    const prev = document.getElementById('cal-prev');
    const next = document.getElementById('cal-next');
    const eventList = document.getElementById('event-list');
    if (!title || !grid) return;
    let date = new Date();
    const events = [ { day:5, title:'Rapat Koordinasi', color:'#2563eb' }, { day:12, title:'Deadline Upload', color:'#f59e0b' }, { day:22, title:'Review Data', color:'#10b981' } ];
    const render = () => {
      const year = date.getFullYear();
      const month = date.getMonth();
      const names = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      title.textContent = `${names[month]} ${year}`;
      const first = new Date(year, month, 1).getDay();
      const days = new Date(year, month+1, 0).getDate();
      grid.innerHTML = '';
      for(let i=0;i<first;i++){ grid.innerHTML += `<div></div>`; }
      for(let d=1; d<=days; d++){
        const isToday = new Date().getDate()===d && new Date().getMonth()===month && new Date().getFullYear()===year;
        const evs = events.filter(e=> e.day===d);
        grid.innerHTML += `<div class="calendar-cell ${isToday?'today':''}"><div>${d}</div>${evs.map(e=>`<div class="event-badge" style="background:${e.color}">${e.title}</div>`).join('')}</div>`;
      }
      eventList.innerHTML = events.map(e=> `<div class="file-item"><div>${e.title}</div><div class="thread-meta">Tanggal ${e.day}</div></div>`).join('');
    };
    render();
    if (prev) prev.onclick = () => { date.setMonth(date.getMonth()-1); render(); };
    if (next) next.onclick = () => { date.setMonth(date.getMonth()+1); render(); };
  }

  initializeDinasStatus() {
    const table = document.getElementById('ds-table');
    const search = document.getElementById('ds-search');
    if (!table) return;
    const render = (q='') => {
      const rows = dinasData.filter(d=> d.name.toLowerCase().includes(q.toLowerCase())).map(d=> `
        <tr>
          <td>${d.name}</td>
          <td>${d.fullName}</td>
          <td>
            <div class="progress-line"><div class="progress-line-fill" style="width:${d.progress}%; background:${d.color}"></div></div>
          </td>
          <td>${d.status}</td>
        </tr>
      `).join('');
      table.innerHTML = `<thead><tr><th>OPD</th><th>Nama Lengkap</th><th>Progress</th><th>Status</th></tr></thead><tbody>${rows}</tbody>`;
    };
    render();
    if (search) search.oninput = (e)=> render(e.target.value);
  }

  initializeSettings() {
    const profile = document.getElementById('profile-info');
    const themeSelect = document.getElementById('set-theme');
    const notifSelect = document.getElementById('set-notif');
    const saveBtn = document.getElementById('set-save');
    const user = authHandler.getCurrentUser();
    if (profile && user) {
      profile.innerHTML = `<div class="file-item"><div><div class="thread-title">${user.name}</div><div class="thread-meta">${user.position}</div></div><div>${user.role}</div></div>`;
    }
    if (saveBtn) saveBtn.onclick = () => {
      localStorage.setItem('sipandu_pref_theme', themeSelect?.value || 'light');
      localStorage.setItem('sipandu_pref_notif', notifSelect?.value || 'all');
      Utils.showToast('Pengaturan disimpan', 'success');
    };
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
});
