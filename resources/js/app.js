// SIPANDU DATA - Main Application JavaScript
// Updated for Laravel Backend Integration

// Application Configuration
const CONFIG = {
  APP_NAME: 'SIPANDU DATA',
  VERSION: '1.0.0',
  DEVELOPER: 'H. Agus Salim, S.Pi',
  API_BASE: 'http://localhost:8000/api', // Laravel API base URL
  STORAGE_PREFIX: 'sipandu_'
};

// Application State Management
class AppState {
  constructor() {
    this.currentUser = null;
    this.isAuthenticated = false;
    this.authToken = null;
    this.currentPage = 'dashboard';
    this.notifications = [];
    this.dinasData = [];
  }

  // Load state from localStorage
  loadState() {
    try {
      const savedState = localStorage.getItem(CONFIG.STORAGE_PREFIX + 'state');
      const token = localStorage.getItem(CONFIG.STORAGE_PREFIX + 'auth_token');
      
      if (savedState) {
        const state = JSON.parse(savedState);
        this.currentUser = state.currentUser;
        this.isAuthenticated = state.isAuthenticated;
        this.notifications = state.notifications || [];
      }
      
      if (token) {
        this.authToken = token;
        this.isAuthenticated = true;
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
      
      if (this.authToken) {
        localStorage.setItem(CONFIG.STORAGE_PREFIX + 'auth_token', this.authToken);
      }
    } catch (error) {
      console.error('Error saving state:', error);
    }
  }

  // Set current user with token
  setUser(user, token = null) {
    this.currentUser = user;
    this.isAuthenticated = true;
    if (token) {
      this.authToken = token;
    }
    this.saveState();
  }

  // Clear user session
  clearUser() {
    this.currentUser = null;
    this.isAuthenticated = false;
    this.authToken = null;
    localStorage.removeItem(CONFIG.STORAGE_PREFIX + 'auth_token');
    this.saveState();
  }

  // Get auth headers for API calls
  getAuthHeaders() {
    return {
      'Authorization': `Bearer ${this.authToken}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };
  }
}

// Initialize app state
const appState = new AppState();

// API Service Class
class ApiService {
  constructor() {
    this.baseURL = CONFIG.API_BASE;
  }

  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const config = {
      headers: appState.getAuthHeaders(),
      ...options
    };

    try {
      const response = await fetch(url, config);
      
      if (response.status === 401) {
        // Unauthorized - redirect to login
        authHandler.logout();
        throw new Error('Session expired. Please login again.');
      }

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('API Request failed:', error);
      throw error;
    }
  }

  // Auth endpoints
  async login(credentials) {
    const response = await fetch(`${this.baseURL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(credentials)
    });

    if (!response.ok) {
      throw new Error('Login failed');
    }

    return await response.json();
  }

  async logout() {
    return await this.request('/logout', { method: 'POST' });
  }

  // Dashboard endpoints
  async getDashboardStats() {
    return await this.request('/dashboard/statistics');
  }

  async getDinasProgress() {
    return await this.request('/dashboard/dinas-progress');
  }

  async getMonthlyProgress() {
    return await this.request('/dashboard/monthly-progress');
  }

  // Dinas endpoints
  async getAllDinas() {
    return await this.request('/dinas');
  }

  async getDinasById(id) {
    return await this.request(`/dinas/${id}`);
  }

  // Data endpoints
  async getDataList(params = {}) {
    const queryString = new URLSearchParams(params).toString();
    return await this.request(`/data?${queryString}`);
  }

  async uploadData(formData) {
    const response = await fetch(`${this.baseURL}/data/upload`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${appState.authToken}`
      },
      body: formData
    });
    return await response.json();
  }
}

// Initialize API service
const apiService = new ApiService();

// Utility Functions (tetap sama)
const Utils = {
  // ... (utility functions tetap sama)
};

// Authentication Handler - Updated for Laravel
class AuthHandler {
  constructor() {
    // Tetap ada demo credentials untuk development
    this.demoCredentials = [
      { email: 'admin@bappeda.go.id', password: 'sipandu2025', role: 'admin' },
      { email: 'perdagangan@kolakautara.go.id', password: 'dinas123', role: 'dinas' },
      { email: 'user@demo.com', password: 'user123', role: 'user' }
    ];
  }

  // Authenticate user dengan Laravel backend
  async authenticate(email, password, role) {
    try {
      // Untuk development, gunakan demo credentials dulu
      // Nanti diganti dengan API call sebenarnya
      const user = this.demoCredentials.find(u => 
        u.email === email && 
        u.password === password
      );

      if (user) {
        // Simulate API response structure
        const userInfo = {
          id: 1,
          name: role === 'admin' ? 'H. Agus Salim, S.Pi' : 
                role === 'dinas' ? 'Admin Perdagangan' : 'User Demo',
          email: email,
          role: role,
          position: role === 'admin' ? 'Super Admin' : 
                  role === 'dinas' ? 'Admin Dinas' : 'User',
          dinas_id: role === 'dinas' ? 3 : null
        };

        const token = 'demo-token-' + Date.now();
        
        appState.setUser(userInfo, token);
        return userInfo;
      } else {
        throw new Error('Invalid credentials');
      }

      // Nanti diganti dengan:
      // const result = await apiService.login({ email, password });
      // appState.setUser(result.user, result.token);
      // return result.user;
      
    } catch (error) {
      console.error('Authentication error:', error);
      throw error;
    }
  }

  // Logout user
  async logout() {
    try {
      // await apiService.logout();
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      appState.clearUser();
      window.location.href = 'index.html';
    }
  }

  // Check if user is authenticated
  isAuthenticated() {
    return appState.isAuthenticated && appState.currentUser && appState.authToken;
  }

  // Get current user
  getCurrentUser() {
    return appState.currentUser;
  }
}

// Initialize auth handler
const authHandler = new AuthHandler();

// UI Components (tetap sama)
class UIComponents {
  // ... (UI components tetap sama)
};

// Page specific functions - Updated for API calls
async function initLandingPage() {
  // ... (kode existing)
  
  try {
    // Load dinas data from API
    const dinasData = await apiService.getAllDinas();
    appState.dinasData = dinasData;
    UIComponents.renderDinasGrid();
  } catch (error) {
    console.error('Failed to load dinas data:', error);
    // Fallback to static data
    UIComponents.renderDinasGrid();
  }
}

async function initLoginPage() {
  // ... (kode existing login page)
}

// Initialize app based on current page
document.addEventListener('DOMContentLoaded', () => {
  appState.loadState();

  const pathname = window.location.pathname;
  
  if (pathname.includes('login.html')) {
    initLoginPage();
  } else if (pathname.includes('dashboard.html')) {
    // Dashboard initialization will be handled by dashboard.js
    console.log('Dashboard page detected');
  } else {
    initLandingPage();
  }

  console.log(`${CONFIG.APP_NAME} v${CONFIG.VERSION} initialized`);
});