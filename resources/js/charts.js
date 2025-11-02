// SIPANDU DATA - Charts JavaScript
// Updated for Dynamic API Data

class ChartManager {
  constructor() {
    this.charts = {};
    this.chartData = {
      monthlyProgress: null,
      dinasProgress: null,
      dataCategories: null
    };
    // ... (color definitions tetap sama)
  }

  // Set data from API
  setMonthlyData(data) {
    this.chartData.monthlyProgress = data;
  }

  setDinasData(data) {
    this.chartData.dinasProgress = data;
  }

  setCategoryData(data) {
    this.chartData.dataCategories = data;
  }

  // Initialize all charts dengan data dari API
  initializeAllCharts() {
    this.initMonthlyProgressChart();
    this.initDinasStatusChart();
    this.initDataCategoryChart();
  }

  // Monthly Progress Chart dengan data dinamis
  initMonthlyProgressChart() {
    const ctx = document.getElementById('monthly-progress-chart');
    if (!ctx) return;

    if (this.charts.monthlyProgress) {
      this.charts.monthlyProgress.destroy();
    }

    // Use API data or fallback to static data
    const data = this.chartData.monthlyProgress || {
      labels: ['Agustus', 'September', 'Oktober', 'November', 'Desember'],
      datasets: [
        {
          label: 'Data Submission',
          data: [45, 62, 75, 85, 90]
        },
        {
          label: 'Approval Rate',
          data: [40, 58, 70, 80, 85]
        }
      ]
    };

    const chartData = {
      labels: data.labels,
      datasets: data.datasets.map((dataset, index) => ({
        label: dataset.label,
        data: dataset.data,
        borderColor: index === 0 ? this.chartColors.primary : this.chartColors.success,
        backgroundColor: this.hexToRgba(index === 0 ? this.chartColors.primary : this.chartColors.success, 0.1),
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: index === 0 ? this.chartColors.primary : this.chartColors.success,
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 6
      }))
    };

    // ... (config options tetap sama)
    this.charts.monthlyProgress = new Chart(ctx, config);
  }

  // Dinas Status Chart dengan data dinamis
  initDinasStatusChart() {
    const ctx = document.getElementById('dinas-status-chart');
    if (!ctx) return;

    if (this.charts.dinasStatus) {
      this.charts.dinasStatus.destroy();
    }

    // Use API data or fallback to static data
    const dinasData = this.chartData.dinasProgress || appState.dinasData || dinasData;
    const sortedDinas = [...dinasData].sort((a, b) => b.progress - a.progress);

    const data = {
      labels: sortedDinas.map(d => d.name || d.nama_dinas),
      datasets: [{
        label: 'Progress (%)',
        data: sortedDinas.map(d => d.progress),
        backgroundColor: sortedDinas.map(d => d.color || this.getColorByProgress(d.progress)),
        borderColor: sortedDinas.map(d => d.color || this.getColorByProgress(d.progress)),
        borderWidth: 1,
        borderRadius: 4,
        borderSkipped: false
      }]
    };

    // ... (config options tetap sama)
    this.charts.dinasStatus = new Chart(ctx, config);
  }

  // Helper function untuk menentukan warna berdasarkan progress
  getColorByProgress(progress) {
    if (progress >= 80) return this.chartColors.success;
    if (progress >= 60) return this.chartColors.warning;
    return this.chartColors.danger;
  }

  // ... (methods lainnya tetap sama)
}