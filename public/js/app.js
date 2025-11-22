// SIPANDU DATA - Main Application JavaScript
// Modern ES6+ JavaScript with modules and classes

// Application Configuration
const CONFIG = {
  APP_NAME: 'SIPANDU DATA',
  VERSION: '1.0.0',
  DEVELOPER: 'H. Agus Salim, S.Pi',
  API_BASE: '/api',
  STORAGE_PREFIX: 'sipandu_'
};

// Application State Management
class AppState {
  constructor() {
    this.currentUser = null;
    this.isAuthenticated = false;
    this.currentPage = 'dashboard';
    this.notifications = [];
    this.dinasData = [];
  }

  // Load state from localStorage
  loadState() {
    try {
      const savedState = localStorage.getItem(CONFIG.STORAGE_PREFIX + 'state');
      if (savedState) {
        const state = JSON.parse(savedState);
        this.currentUser = state.currentUser;
        this.isAuthenticated = state.isAuthenticated;
        this.notifications = state.notifications || [];
      }
    } catch (error) {
      console.error('Error loading state:', error);
    }
  }

  // Save state to localStorage
  saveState() {
    try {
      const state = {
        currentUser: this.currentUser,
        isAuthenticated: this.isAuthenticated,
        notifications: this.notifications
      };
      localStorage.setItem(CONFIG.STORAGE_PREFIX + 'state', JSON.stringify(state));
    } catch (error) {
      console.error('Error saving state:', error);
    }
  }

  // Set current user
  setUser(user) {
    this.currentUser = user;
    this.isAuthenticated = true;
    this.saveState();
  }

  // Clear user session
  clearUser() {
    this.currentUser = null;
    this.isAuthenticated = false;
    this.saveState();
  }
}

// Initialize app state
const appState = new AppState();

// Sample Data - Dinas Information
const dinasData = [
  {
    id: 1,
    name: 'Bappeda',
    fullName: 'Badan Perencanaan Pembangunan Daerah',
    status: 'Complete',
    progress: 100,
    icon: 'fas fa-chart-line',
    color: '#22c55e'
  },
  {
    id: 2,
    name: 'DPMPTSP',
    fullName: 'Dinas Penanaman Modal dan PTSP',
    status: 'Pending',
    progress: 25,
    icon: 'fas fa-handshake',
    color: '#ef4444'
  },
  {
    id: 3,
    name: 'Perdagangan',
    fullName: 'Dinas Perdagangan',
    status: 'Complete',
    progress: 95,
    icon: 'fas fa-store',
    color: '#22c55e'
  },
  {
    id: 4,
    name: 'Perindustrian',
    fullName: 'Dinas Perindustrian',
    status: 'Progress',
    progress: 70,
    icon: 'fas fa-industry',
    color: '#f59e0b'
  },
  {
    id: 5,
    name: 'Koperasi',
    fullName: 'Dinas Koperasi dan UKM',
    status: 'Complete',
    progress: 90,
    icon: 'fas fa-users',
    color: '#22c55e'
  },
  {
    id: 6,
    name: 'Tanaman Pangan',
    fullName: 'Dinas Pertanian Tanaman Pangan',
    status: 'Complete',
    progress: 88,
    icon: 'fas fa-seedling',
    color: '#22c55e'
  },
  {
    id: 7,
    name: 'Perkebunan',
    fullName: 'Dinas Perkebunan dan Peternakan',
    status: 'Progress',
    progress: 75,
    icon: 'fas fa-tree',
    color: '#f59e0b'
  },
  {
    id: 8,
    name: 'Perikanan',
    fullName: 'Dinas Perikanan',
    status: 'Complete',
    progress: 92,
    icon: 'fas fa-fish',
    color: '#22c55e'
  },
  {
    id: 9,
    name: 'Ketahanan Pangan',
    fullName: 'Dinas Ketahanan Pangan',
    status: 'Pending',
    progress: 35,
    icon: 'fas fa-wheat',
    color: '#ef4444'
  },
  {
    id: 10,
    name: 'Pariwisata',
    fullName: 'Dinas Pariwisata',
    status: 'Complete',
    progress: 85,
    icon: 'fas fa-map-marked-alt',
    color: '#22c55e'
  },
  {
    id: 11,
    name: 'DLH',
    fullName: 'Dinas Lingkungan Hidup',
    status: 'Progress',
    progress: 65,
    icon: 'fas fa-leaf',
    color: '#f59e0b'
  },
  {
    id: 12,
    name: 'Bapenda',
    fullName: 'Badan Pendapatan Daerah',
    status: 'Pending',
    progress: 20,
    icon: 'fas fa-coins',
    color: '#ef4444'
  }
];

// Utility Functions
const Utils = {
  // Format numbers with Indonesian locale
  formatNumber: (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
  },

  // Format percentage
  formatPercentage: (num) => {
    return `${Math.round(num)}%`;
  },

  // Generate random ID
  generateId: () => {
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
  },

  // Debounce function
  debounce: (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  },

  // Show toast notification
  showToast: (message, type = 'info') => {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
      <div class="toast-content">
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
      </div>
    `;

    // Add toast styles dynamically
    if (!document.getElementById('toast-styles')) {
      const styles = document.createElement('style');
      styles.id = 'toast-styles';
      styles.textContent = `
        .toast {
          position: fixed;
          top: 20px;
          right: 20px;
          background: white;
          padding: 12px 16px;
          border-radius: 8px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
          border-left: 4px solid;
          z-index: 10000;
          transform: translateX(100%);
          transition: transform 0.3s ease;
        }
        .toast-success { border-left-color: #22c55e; }
        .toast-error { border-left-color: #ef4444; }
        .toast-info { border-left-color: #06b6d4; }
        .toast-content { display: flex; align-items: center; gap: 8px; }
        .toast.show { transform: translateX(0); }
      `;
      document.head.appendChild(styles);
    }

    document.body.appendChild(toast);

    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);

    // Remove toast
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }
};

// Authentication Handler
class AuthHandler {
  constructor() {
    this.demoCredentials = [
      { username: 'admin.bappeda', password: 'sipandu2025', role: 'admin', name: 'H. Agus Salim, S.Pi', position: 'Super Admin' },
      { username: 'admin.perdagangan', password: 'dinas123', role: 'dinas', name: 'Admin Perdagangan', position: 'Admin Dinas' },
      { username: 'user.demo', password: 'user123', role: 'user', name: 'User Demo', position: 'User' }
    ];
  }

  // Authenticate user
  async authenticate(username, password, role) {
    return new Promise((resolve, reject) => {
      // Simulate API delay
      setTimeout(() => {
        const user = this.demoCredentials.find(u => 
          u.username === username && 
          u.password === password && 
          u.role === role
        );

        if (user) {
          const userInfo = { ...user };
          delete userInfo.password; // Don't store password
          appState.setUser(userInfo);
          resolve(userInfo);
        } else {
          reject(new Error('Invalid credentials'));
        }
      }, 1500);
    });
  }

  // Logout user
  logout() {
    appState.clearUser();
    window.location.href = '/fe';
  }

  // Check if user is authenticated
  isAuthenticated() {
    return appState.isAuthenticated && appState.currentUser;
  }

  // Get current user
  getCurrentUser() {
    return appState.currentUser;
  }
}

// Initialize auth handler
const authHandler = new AuthHandler();

// UI Components
class UIComponents {
  // Render dinas grid on landing page
  static renderDinasGrid() {
    const dinasGrid = document.getElementById('dinas-grid');
    if (!dinasGrid) return;

    dinasGrid.innerHTML = dinasData.map(dinas => `
      <div class="dinas-card" data-dinas-id="${dinas.id}">
        <div class="dinas-header">
          <div class="dinas-info">
            <div class="dinas-icon" style="background-color: ${dinas.color}">
              <i class="${dinas.icon}"></i>
            </div>
            <span class="dinas-name">${dinas.name}</span>
          </div>
          <span class="status-badge status-${dinas.status.toLowerCase()}">${dinas.status}</span>
        </div>
        <p class="dinas-description">${dinas.fullName}</p>
        <div class="progress-bar">
          <div class="progress-fill" style="width: ${dinas.progress}%; background-color: ${dinas.color}"></div>
        </div>
        <div class="progress-text">
          <span class="progress-label">Progress</span>
          <span class="progress-percentage">${dinas.progress}%</span>
        </div>
      </div>
    `).join('');

    // Add click handlers
    dinasGrid.addEventListener('click', (e) => {
      const card = e.target.closest('.dinas-card');
      if (card) {
        const dinasId = parseInt(card.dataset.dinasId);
        const dinas = dinasData.find(d => d.id === dinasId);
        if (dinas) {
          Utils.showToast(`${dinas.name}: ${dinas.progress}% complete`, 'info');
        }
      }
    });
  }

  // Render notifications
  static renderNotifications() {
    const notificationList = document.getElementById('notification-list');
    if (!notificationList) return;

    const notifications = [
      {
        id: 1,
        title: 'Data Baru Masuk',
        message: 'Perdagangan telah submit data triwulan III',
        type: 'info',
        time: '5 menit lalu',
        read: false
      },
      {
        id: 2,
        title: 'Reminder Deadline',
        message: 'Bapenda: deadline 3 hari lagi',
        type: 'warning',
        time: '2 jam lalu',
        read: false
      },
      {
        id: 3,
        title: 'Approval Success',
        message: 'Data Perikanan telah disetujui',
        type: 'success',
        time: '1 hari lalu',
        read: true
      }
    ];

    notificationList.innerHTML = notifications.map(notif => `
      <div class="notification-item ${notif.read ? 'read' : 'unread'}" data-notif-id="${notif.id}">
        <div class="notification-icon ${notif.type}">
          <i class="fas fa-${notif.type === 'info' ? 'info-circle' : notif.type === 'warning' ? 'exclamation-triangle' : 'check-circle'}"></i>
        </div>
        <div class="notification-content">
          <div class="notification-title">${notif.title}</div>
          <div class="notification-message">${notif.message}</div>
          <div class="notification-time">${notif.time}</div>
        </div>
      </div>
    `).join('');

    // Update notification count
    const count = notifications.filter(n => !n.read).length;
    const badge = document.getElementById('notification-count');
    if (badge) {
      badge.textContent = count;
      badge.style.display = count > 0 ? 'block' : 'none';
    }
  }
}

// Page specific functions
function initLandingPage() {
  // Mobile navigation toggle
  const navToggle = document.getElementById('nav-toggle');
  const navMenu = document.getElementById('nav-menu');

  if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.toggle('show');
    });
  }

  // Smooth scrolling for navigation links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
        if (navMenu) navMenu.classList.remove('show');
      }
    });
  });

  // Render dinas grid
  UIComponents.renderDinasGrid();

  const logoutFlag = localStorage.getItem('sipandu_logout_ok');
  if (logoutFlag) {
    localStorage.removeItem('sipandu_logout_ok');
    Utils.showToast('Anda telah keluar', 'success');
  }

  // Animate statistics on scroll
  const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const statNumbers = entry.target.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
          const finalValue = parseInt(stat.textContent);
          animateCounter(stat, 0, finalValue, 2000);
        });
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  const heroStats = document.querySelector('.hero-stats');
  if (heroStats) observer.observe(heroStats);
}

function initLoginPage() {
  const loginForm = document.getElementById('login-form');
  const togglePassword = document.getElementById('toggle-password');
  const passwordInput = document.getElementById('password');
  const loadingOverlay = document.getElementById('loading-overlay');
  const demoAccounts = document.querySelectorAll('.demo-account');

  // Toggle password visibility
  if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', () => {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      togglePassword.innerHTML = type === 'password' 
        ? '<i class="fas fa-eye"></i>' 
        : '<i class="fas fa-eye-slash"></i>';
    });
  }

  // Demo account buttons
  demoAccounts.forEach(account => {
    const button = account.querySelector('.btn');
    button.addEventListener('click', () => {
      const username = account.dataset.username;
      const password = account.dataset.password;
      const role = account.dataset.role;

      document.getElementById('username').value = username;
      document.getElementById('password').value = password;
      document.getElementById('role').value = role;

      Utils.showToast('Demo credentials filled!', 'success');
    });
  });

  // Login form submission
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData(loginForm);
      const username = formData.get('username');
      const password = formData.get('password');
      const role = formData.get('role');

      // Show loading
      if (loadingOverlay) loadingOverlay.style.display = 'flex';

      try {
        const user = await authHandler.authenticate(username, password, role);
        Utils.showToast(`Welcome, ${user.name}!`, 'success');

        // Redirect to dashboard (served by PHP)
        setTimeout(() => {
          window.location.href = '/fe/dashboard';
        }, 1000);
      } catch (error) {
        Utils.showToast('Login failed: ' + error.message, 'error');
      } finally {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
      }
    });
  }
}

// Counter animation
function animateCounter(element, start, end, duration) {
  let startTime = null;
  const isPercentage = element.textContent.includes('%');

  function animate(currentTime) {
    if (startTime === null) startTime = currentTime;
    const timeElapsed = currentTime - startTime;
    const progress = Math.min(timeElapsed / duration, 1);

    const current = Math.floor(progress * (end - start) + start);
    element.textContent = isPercentage ? `${current}%` : current;

    if (progress < 1) {
      requestAnimationFrame(animate);
    }
  }

  requestAnimationFrame(animate);
}

// Initialize app based on current page
document.addEventListener('DOMContentLoaded', () => {
  // Load app state
  appState.loadState();

  // Determine current page and initialize
  const pathname = window.location.pathname;

  if (pathname.includes('login.html') || pathname.includes('/fe/login')) {
    initLoginPage();
  } else if (pathname.includes('dashboard.html') || pathname.includes('/fe/dashboard')) {
    // Dashboard initialization will be handled by dashboard.js
    console.log('Dashboard page detected');
  } else {
    // Landing page
    initLandingPage();
  }

  console.log(`${CONFIG.APP_NAME} v${CONFIG.VERSION} initialized`);
  console.log(`Developed by ${CONFIG.DEVELOPER}`);
});

// Export for other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { Utils, AuthHandler, UIComponents, dinasData, appState };
}
// Global dropdown interactions (works across pages)
document.addEventListener('DOMContentLoaded', () => {
  const notif = document.getElementById('notifications');
  const user = document.getElementById('user-menu');
  const notifDd = document.getElementById('notification-dropdown');
  const userDd = document.getElementById('user-dropdown');
  const hasDataset = (el) => el && el.dataset && el.dataset.dropdown;
  const toggleNotif = (e) => { e.stopPropagation(); if (notifDd) { notifDd.classList.toggle('show'); } if (userDd) { userDd.classList.remove('show'); } };
  const toggleUser = (e) => { e.stopPropagation(); if (userDd) { userDd.classList.toggle('show'); } if (notifDd) { notifDd.classList.remove('show'); } };
  if (notif && !hasDataset(notif)) { notif.addEventListener('click', toggleNotif); notif.addEventListener('mousedown', (e)=>e.stopPropagation()); }
  if (user && !hasDataset(user)) { user.addEventListener('click', toggleUser); user.addEventListener('mousedown', (e)=>e.stopPropagation()); }
  document.querySelectorAll('.dropdown').forEach(dd => dd.addEventListener('click', (e)=> e.stopPropagation()));
  document.addEventListener('click', (e) => {
    const inDropdown = e.target.closest('.dropdown');
    if (inDropdown) {
      return;
    }
    const trigger = e.target.closest('[data-dropdown]');
    if (trigger) {
      e.stopPropagation();
      const id = trigger.dataset.dropdown; const dd = document.getElementById(id);
      if (dd) {
        document.querySelectorAll('.dropdown.show').forEach(x=>{ if (x !== dd) x.classList.remove('show'); });
        dd.classList.toggle('show');
      }
      return;
    }
    notifDd?.classList.remove('show'); userDd?.classList.remove('show');
  }, true);

  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) {
    const showConfirmLogout = () => {
      let overlay = document.getElementById('global-confirm-overlay');
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'global-confirm-overlay';
        overlay.className = 'modal-overlay';
        overlay.innerHTML = `
          <div class="modal">
            <div class="modal-header"><h3>Konfirmasi Keluar</h3><button class="btn btn-outline btn-sm" id="gc-close">✕</button></div>
            <div class="modal-body"><p>Anda yakin ingin keluar dari SIPANDU DATA?</p></div>
            <div class="modal-footer"><button class="btn btn-secondary" id="gc-cancel">Batal</button><button class="btn btn-primary" id="gc-ok"><i class="fas fa-sign-out-alt"></i> Keluar</button></div>
          </div>
        `;
        document.body.appendChild(overlay);
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.background = 'transparent';
        overlay.style.display = 'none';
        overlay.style.alignItems = 'flex-start';
        overlay.style.justifyContent = 'center';
        overlay.style.paddingTop = '12px';
        overlay.style.zIndex = '10000';
        const modalEl = overlay.querySelector('.modal');
        if (modalEl) { modalEl.style.maxWidth = '420px'; modalEl.style.margin = '0 auto'; }
        const close = () => { overlay.style.display = 'none'; };
        overlay.addEventListener('click', (ev) => { if (ev.target === overlay) close(); });
        overlay.querySelector('#gc-close').onclick = close;
        overlay.querySelector('#gc-cancel').onclick = close;
        overlay.querySelector('#gc-ok').onclick = () => { localStorage.setItem('sipandu_logout_ok','1'); authHandler.logout(); };
      }
      overlay.style.display = 'flex';
    };
    logoutBtn.dataset.enhanced = '1';
    logoutBtn.addEventListener('click', (e) => { e.preventDefault(); showConfirmLogout(); });
  }
});
