// SIPANDU DATA - Dashboard JavaScript
// Updated for Laravel API Integration

class DashboardManager {
  constructor() {
    this.currentPage = 'dashboard';
    this.sidebarCollapsed = false;
    this.charts = {};
    this.activities = [];
    this.dashboardData = {};
    this.init();
  }

  async init() {
    this.checkAuthentication();
    await this.initializeUI();
    this.bindEvents();
    await this.loadDashboardData();
    this.renderActivityFeed();
  }

  // Check if user is authenticated
  checkAuthentication() {
    if (!authHandler.isAuthenticated()) {
      window.location.href = 'login.html';
      return;
    }

    const currentUser = authHandler.getCurrentUser();
    this.updateUserInfo(currentUser);
  }

  // Initialize UI components
  async initializeUI() {
    this.initializeSidebar();
    this.initializeNavigation();
    this.initializeDropdowns();
    await this.loadNotifications();
  }

  // Load notifications from API
  async loadNotifications() {
    try {
      // const notifications = await apiService.getNotifications();
      // UIComponents.renderNotifications(notifications);
      UIComponents.renderNotifications(); // Static for now
    } catch (error) {
      console.error('Failed to load notifications:', error);
      UIComponents.renderNotifications(); // Fallback to static
    }
  }

  // Load dashboard data from API
  async loadDashboardData() {
    try {
      // Load all dashboard data in parallel
      const [stats, dinasProgress, monthlyProgress] = await Promise.all([
        apiService.getDashboardStats(),
        apiService.getDinasProgress(),
        apiService.getMonthlyProgress()
      ]);

      this.dashboardData = {
        stats,
        dinasProgress,
        monthlyProgress
      };

      this.updateKPICards();
      this.initializeCharts();
      
    } catch (error) {
      console.error('Failed to load dashboard data:', error);
      // Fallback to static data
      this.updateKPICards();
      this.initializeCharts();
    }
  }

  // Update KPI cards with data from API
  updateKPICards() {
    const stats = this.dashboardData.stats || this.calculateStatistics();

    this.animateKPI('total-dinas', stats.total_dinas || stats.totalDinas);
    this.animateKPI('complete-data', stats.complete_data || stats.completeData);
    this.animateKPI('avg-progress', stats.average_progress || stats.avgProgress, '%');
    this.animateKPI('pending-reviews', stats.pending_reviews || stats.pendingReviews);
  }

  // Calculate statistics from API data or fallback
  calculateStatistics() {
    if (this.dashboardData.stats) {
      return this.dashboardData.stats;
    }

    // Fallback to static calculation
    const dinasData = appState.dinasData.length > 0 ? appState.dinasData : dinasData;
    const totalDinas = dinasData.length;
    const completeData = dinasData.filter(d => d.status === 'Complete').length;
    const avgProgress = Math.round(dinasData.reduce((sum, d) => sum + d.progress, 0) / totalDinas);
    const pendingReviews = dinasData.filter(d => d.status === 'Pending').length + 
                          dinasData.filter(d => d.status === 'Progress').length;

    return { totalDinas, completeData, avgProgress, pendingReviews };
  }

  // Initialize charts with API data
  initializeCharts() {
    if (typeof ChartManager !== 'undefined') {
      this.chartManager = new ChartManager();
      
      // Pass API data to charts
      if (this.dashboardData.monthlyProgress) {
        this.chartManager.setMonthlyData(this.dashboardData.monthlyProgress);
      }
      if (this.dashboardData.dinasProgress) {
        this.chartManager.setDinasData(this.dashboardData.dinasProgress);
      }
      
      this.chartManager.initializeAllCharts();
    }
  }

  // ... (methods lainnya tetap sama)
}

// ... (kode lainnya tetap sama)