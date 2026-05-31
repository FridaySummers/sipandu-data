// SIPANDU DATA - Charts JavaScript
// Chart.js implementation for data visualization

class ChartManager {
  constructor() {
    this.charts = {};
    this.chartColors = {
      primary: '#2563eb',
      success: '#22c55e',
      warning: '#f59e0b',
      danger: '#ef4444',
      info: '#06b6d4',
      gray: '#64748b'
    };
  }

  // Initialize all charts
  initializeAllCharts() {
    this.initMonthlyProgressChart();
    this.initDinasStatusChart();
    this.initDataCategoryChart();
  }

  // Monthly Progress Chart (Line Chart)
  initMonthlyProgressChart() {
    const ctx = document.getElementById('monthly-progress-chart');
    if (!ctx) return;

    // Destroy existing chart if it exists
    if (this.charts.monthlyProgress) {
      this.charts.monthlyProgress.destroy();
    }

    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const empty = new Array(12).fill(0);
    const data = {
      labels: months,
      datasets: [
        {
          label: 'Data Masuk',
          data: empty.slice(),
          borderColor: this.chartColors.primary,
          backgroundColor: this.hexToRgba(this.chartColors.primary, 0.1),
          borderWidth: 3,
          fill: true,
          tension: 0.3,
          pointBackgroundColor: this.chartColors.primary,
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointRadius: 5
        },
        {
          label: 'Data yang Disetujui',
          data: empty.slice(),
          borderColor: this.chartColors.success,
          backgroundColor: this.hexToRgba(this.chartColors.success, 0.1),
          borderWidth: 3,
          fill: true,
          tension: 0.3,
          pointBackgroundColor: this.chartColors.success,
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointRadius: 5
        }
      ]
    };

    const config = {
      type: 'line',
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              usePointStyle: true,
              font: {
                family: 'Inter',
                size: 12,
                weight: '500'
              }
            }
          },
          tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: {
              family: 'Inter',
              size: 13,
              weight: '600'
            },
            bodyFont: {
              family: 'Inter',
              size: 12
            },
            borderColor: this.chartColors.primary,
            borderWidth: 1
          }
        },
        interaction: {
          mode: 'nearest',
          axis: 'x',
          intersect: false
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Bulan',
              font: {
                family: 'Inter',
                size: 12,
                weight: '600'
              }
            },
            grid: {
              display: false
            },
            ticks: {
              font: {
                family: 'Inter',
                size: 11
              }
            }
          },
          y: {
            display: true,
            title: {
              display: true,
              text: 'Jumlah Data (unit)',
              font: {
                family: 'Inter',
                size: 12,
                weight: '600'
              }
            },
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
              font: {
                family: 'Inter',
                size: 11
              },
              precision: 0
            }
          }
        },
        animation: {
          duration: 2000,
          easing: 'easeInOutCubic'
        }
      }
    };

    this.charts.monthlyProgress = new Chart(ctx, config);
    const yearSel = document.getElementById('mp-year');
    const loadData = async (year) => {
      try {
        const resp = await fetch(`/dashboard/data/monthly?year=${year}`);
        const json = await resp.json();
        const s = json.submissions || empty;
        const a = json.approvals || empty;
        this.charts.monthlyProgress.data.datasets[0].data = s;
        this.charts.monthlyProgress.data.datasets[1].data = a;
        this.charts.monthlyProgress.update();
      } catch(err) {
        console.error('Failed to load monthly data', err);
      }
    };
    const initYear = yearSel ? yearSel.value : new Date().getFullYear();
    loadData(initYear);
    if (yearSel) yearSel.addEventListener('change', ()=> loadData(yearSel.value));
  }

  // Dinas Status Chart (Horizontal Bar Chart)
  initDinasStatusChart() {
    const ctx = document.getElementById('dinas-status-chart');
    if (!ctx) return;

    if (this.charts.dinasStatus) {
      this.charts.dinasStatus.destroy();
    }

    const data = {
      labels: [],
      datasets: [{
        label: 'Jumlah Data',
        data: [],
        backgroundColor: '#2563eb',
        borderColor: '#2563eb',
        borderWidth: 1,
        borderRadius: 4,
        borderSkipped: false
      }]
    };

    const config = {
      type: 'bar',
      data: data,
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: {
              family: 'Inter',
              size: 13,
              weight: '600'
            },
            bodyFont: {
              family: 'Inter',
              size: 12
            },
            callbacks: {
              label: function(context) {
                return `${context.label}: ${context.parsed.x}`;
              }
            }
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
              font: {
                family: 'Inter',
                size: 11
              }
            }
          },
          y: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                family: 'Inter',
                size: 10,
                weight: '500'
              },
              maxRotation: 0
            }
          }
        },
        animation: {
          duration: 1500,
          easing: 'easeInOutQuad'
        }
      }
    };

    this.charts.dinasStatus = new Chart(ctx, config);
    const yearSel = document.getElementById('mp-year');
    const loadData = async (year) => {
      try {
        const resp = await fetch(`/dashboard/data/dinas-comparison?year=${year}`);
        const json = await resp.json();
        const labels = json.labels || [];
        const vals = json.values || [];
        this.charts.dinasStatus.data.labels = labels;
        this.charts.dinasStatus.data.datasets[0].data = vals;
        this.charts.dinasStatus.update();
      } catch(err) {
        console.error('Failed to load dinas comparison', err);
      }
    };
    const initYear = yearSel ? yearSel.value : new Date().getFullYear();
    loadData(initYear);
    if (yearSel) yearSel.addEventListener('change', ()=> loadData(yearSel.value));
  }

  // Data Category Chart (Doughnut Chart)
  initDataCategoryChart() {
    const ctx = document.getElementById('data-category-chart');
    if (!ctx) return;

    if (this.charts.dataCategory) {
      this.charts.dataCategory.destroy();
    }

    const data = {
      labels: [],
      datasets: [{
        data: [],
        backgroundColor: [
          '#1d4ed8','#22c55e','#f59e0b','#ef4444','#06b6d4','#7c3aed','#10b981','#f43f5e','#14b8a6','#a855f7','#94a3b8','#e11d48'
        ],
        borderColor: '#ffffff',
        borderWidth: 2,
        hoverOffset: 8
      }]
    };

    const config = {
      type: 'doughnut',
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              font: {
                family: 'Inter',
                size: 11,
                weight: '500'
              },
              padding: 15
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: {
              family: 'Inter',
              size: 13,
              weight: '600'
            },
            bodyFont: {
              family: 'Inter',
              size: 12
            },
            callbacks: {
              label: function(context) {
                return `${context.label}: ${context.parsed}`;
              }
            }
          }
        },
        cutout: '60%',
        animation: {
          duration: 2000,
          easing: 'easeInOutCubic'
        }
      }
    };

    this.charts.dataCategory = new Chart(ctx, config);
    const yearSel = document.getElementById('mp-year');
    const loadData = async (year) => {
      try {
        const resp = await fetch(`/dashboard/data/category?year=${year}`);
        const json = await resp.json();
        const labels = json.labels || [];
        const vals = json.values || [];
        this.charts.dataCategory.data.labels = labels;
        this.charts.dataCategory.data.datasets[0].data = vals;
        this.charts.dataCategory.update();
      } catch(err) {
        console.error('Failed to load category data', err);
      }
    };
    const initYear = yearSel ? yearSel.value : new Date().getFullYear();
    loadData(initYear);
    if (yearSel) yearSel.addEventListener('change', ()=> loadData(yearSel.value));
  }

  // Utility function to convert hex to rgba
  hexToRgba(hex, alpha = 1) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
  }

  // Refresh all charts
  refreshAllCharts() {
    Object.values(this.charts).forEach(chart => {
      if (chart && typeof chart.update === 'function') {
        chart.update('resize');
      }
    });
  }

  // Destroy all charts
  destroyAllCharts() {
    Object.values(this.charts).forEach(chart => {
      if (chart && typeof chart.destroy === 'function') {
        chart.destroy();
      }
    });
    this.charts = {};
  }

  // Update chart data dynamically
  updateChartData(chartName, newData) {
    const chart = this.charts[chartName];
    if (chart) {
      chart.data = newData;
      chart.update();
    }
  }

  // Export chart as image
  exportChart(chartName, fileName = null) {
    const chart = this.charts[chartName];
    if (!chart) return;

    const link = document.createElement('a');
    link.download = fileName || `${chartName}-chart.png`;
    link.href = chart.toBase64Image();
    link.click();
  }

  // Get chart statistics
  getChartStats() {
    const completeCount = dinasData.filter(d => d.status === 'Complete').length;
    const progressCount = dinasData.filter(d => d.status === 'Progress').length;
    const pendingCount = dinasData.filter(d => d.status === 'Menunggu Persetujuan').length;
    const avgProgress = Math.round(dinasData.reduce((sum, d) => sum + d.progress, 0) / dinasData.length);

    return {
      total: dinasData.length,
      complete: completeCount,
      progress: progressCount,
      pending: pendingCount,
      averageProgress: avgProgress
    };
  }
}

// Chart configuration defaults
Chart.defaults.font.family = 'Inter';
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = '#e2e8f0';

// Global chart options
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.displayColors = true;
Chart.defaults.plugins.tooltip.borderWidth = 1;

// Initialize charts when document is ready
document.addEventListener('DOMContentLoaded', () => {
  // Only initialize if we're on the dashboard page and Chart.js is available
  if (window.location.pathname.includes('dashboard.html') && typeof Chart !== 'undefined') {
    console.log('Chart.js detected, charts will be initialized by DashboardManager');
  }
});

// Export for use in other modules
if (typeof window !== 'undefined') {
  window.ChartManager = ChartManager;
}
