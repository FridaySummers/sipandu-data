// SIPANDU DATA - Utility Functions
// Common utilities and helper functions

// API Utilities
const ApiUtils = {
  // Handle API errors consistently
  handleApiError: (error) => {
    console.error('API Error:', error);
    
    if (error.message.includes('401') || error.message.includes('Unauthorized')) {
      Utils.showToast('Session expired. Please login again.', 'error');
      authHandler.logout();
      return;
    }
    
    if (error.message.includes('Network Error')) {
      Utils.showToast('Network error. Please check your connection.', 'error');
      return;
    }
    
    Utils.showToast('Operation failed. Please try again.', 'error');
  },

  // Format data for API submission
  formatFormData: (formData) => {
    const data = {};
    for (const [key, value] of formData.entries()) {
      data[key] = value;
    }
    return data;
  },

  // Validate API response
  validateResponse: (response) => {
    if (!response || typeof response !== 'object') {
      throw new Error('Invalid API response');
    }
    return response;
  }
};

// Export API utilities
if (typeof window !== 'undefined') {
  window.ApiUtils = ApiUtils;
}

// Date and Time Utilities
const DateUtils = {
  // Format date to Indonesian locale
  formatDate: (date, options = {}) => {
    const defaultOptions = {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    };
    return new Date(date).toLocaleDateString('id-ID', { ...defaultOptions, ...options });
  },

  // Format time to Indonesian locale
  formatTime: (date) => {
    return new Date(date).toLocaleTimeString('id-ID', {
      hour: '2-digit',
      minute: '2-digit'
    });
  },

  // Format relative time (e.g., "2 hours ago")
  formatRelativeTime: (date) => {
    const now = new Date();
    const past = new Date(date);
    const diffMs = now - past;
    const diffMinutes = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMinutes / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffMinutes < 1) return 'Baru saja';
    if (diffMinutes < 60) return `${diffMinutes} menit lalu`;
    if (diffHours < 24) return `${diffHours} jam lalu`;
    if (diffDays < 30) return `${diffDays} hari lalu`;
    return DateUtils.formatDate(date);
  },

  // Get current academic year
  getCurrentAcademicYear: () => {
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1;

    // Academic year starts in January
    return currentMonth >= 1 ? currentYear : currentYear - 1;
  }
};

// Number and Currency Utilities
const NumberUtils = {
  // Format number with Indonesian locale
  formatNumber: (num, minimumFractionDigits = 0) => {
    return new Intl.NumberFormat('id-ID', {
      minimumFractionDigits,
      maximumFractionDigits: 2
    }).format(num);
  },

  // Format currency (Indonesian Rupiah)
  formatCurrency: (amount, showSymbol = true) => {
    const formatted = new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(amount);

    return showSymbol ? formatted : formatted.replace('Rp', '').trim();
  },

  // Format percentage
  formatPercentage: (value, decimals = 1) => {
    return `${value.toFixed(decimals)}%`;
  },

  // Format large numbers with K, M, B suffixes
  formatCompactNumber: (num) => {
    if (num >= 1e9) return (num / 1e9).toFixed(1) + 'B';
    if (num >= 1e6) return (num / 1e6).toFixed(1) + 'M';
    if (num >= 1e3) return (num / 1e3).toFixed(1) + 'K';
    return num.toString();
  },

  // Calculate percentage change
  calculatePercentageChange: (oldValue, newValue) => {
    if (oldValue === 0) return newValue > 0 ? 100 : 0;
    return ((newValue - oldValue) / oldValue) * 100;
  }
};

// String Utilities
const StringUtils = {
  // Capitalize first letter
  capitalize: (str) => {
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
  },

  // Convert to title case
  toTitleCase: (str) => {
    return str.replace(/\w\S*/g, (txt) => 
      txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
    );
  },

  // Generate slug from string
  slugify: (str) => {
    return str
      .toLowerCase()
      .replace(/[^\w ]+/g, '')
      .replace(/ +/g, '-');
  },

  // Truncate string with ellipsis
  truncate: (str, maxLength, suffix = '...') => {
    if (str.length <= maxLength) return str;
    return str.substring(0, maxLength - suffix.length) + suffix;
  },

  // Remove HTML tags
  stripHtml: (html) => {
    const tmp = document.createElement('DIV');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
  },

  // Generate random string
  randomString: (length = 8) => {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
  }
};

// Array Utilities
const ArrayUtils = {
  // Remove duplicates from array
  unique: (arr) => [...new Set(arr)],

  // Group array by property
  groupBy: (arr, key) => {
    return arr.reduce((groups, item) => {
      const group = item[key];
      if (!groups[group]) groups[group] = [];
      groups[group].push(item);
      return groups;
    }, {});
  },

  // Sort array by property
  sortBy: (arr, key, direction = 'asc') => {
    return arr.sort((a, b) => {
      const aVal = a[key];
      const bVal = b[key];

      if (direction === 'asc') {
        return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
      } else {
        return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
      }
    });
  },

  // Find item by property
  findBy: (arr, key, value) => {
    return arr.find(item => item[key] === value);
  },

  // Check if array is empty
  isEmpty: (arr) => !arr || arr.length === 0,

  // Chunk array into smaller arrays
  chunk: (arr, size) => {
    const chunks = [];
    for (let i = 0; i < arr.length; i += size) {
      chunks.push(arr.slice(i, i + size));
    }
    return chunks;
  }
};

// Local Storage Utilities
const StorageUtils = {
  // Set item with prefix
  set: (key, value, prefix = 'sipandu_') => {
    try {
      const serializedValue = JSON.stringify(value);
      localStorage.setItem(prefix + key, serializedValue);
      return true;
    } catch (error) {
      console.error('Error saving to localStorage:', error);
      return false;
    }
  },

  // Get item with prefix
  get: (key, defaultValue = null, prefix = 'sipandu_') => {
    try {
      const item = localStorage.getItem(prefix + key);
      return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
      console.error('Error reading from localStorage:', error);
      return defaultValue;
    }
  },

  // Remove item with prefix
  remove: (key, prefix = 'sipandu_') => {
    try {
      localStorage.removeItem(prefix + key);
      return true;
    } catch (error) {
      console.error('Error removing from localStorage:', error);
      return false;
    }
  },

  // Clear all items with prefix
  clear: (prefix = 'sipandu_') => {
    try {
      const keysToRemove = [];
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && key.startsWith(prefix)) {
          keysToRemove.push(key);
        }
      }
      keysToRemove.forEach(key => localStorage.removeItem(key));
      return true;
    } catch (error) {
      console.error('Error clearing localStorage:', error);
      return false;
    }
  }
};

// DOM Utilities
const DOMUtils = {
  // Wait for element to exist
  waitForElement: (selector, timeout = 5000) => {
    return new Promise((resolve, reject) => {
      const element = document.querySelector(selector);
      if (element) {
        resolve(element);
        return;
      }

      const observer = new MutationObserver(() => {
        const element = document.querySelector(selector);
        if (element) {
          observer.disconnect();
          resolve(element);
        }
      });

      observer.observe(document.body, {
        childList: true,
        subtree: true
      });

      setTimeout(() => {
        observer.disconnect();
        reject(new Error(`Element ${selector} not found within ${timeout}ms`));
      }, timeout);
    });
  },

  // Smooth scroll to element
  scrollToElement: (selector, offset = 0) => {
    const element = document.querySelector(selector);
    if (element) {
      const top = element.offsetTop - offset;
      window.scrollTo({ top, behavior: 'smooth' });
    }
  },

  // Check if element is in viewport
  isInViewport: (element) => {
    const rect = element.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  },

  // Add CSS class with animation
  addClass: (element, className, duration = 300) => {
    element.classList.add(className);
    if (duration > 0) {
      element.style.transition = `all ${duration}ms ease`;
    }
  },

  // Remove CSS class with animation
  removeClass: (element, className, duration = 300) => {
    if (duration > 0) {
      element.style.transition = `all ${duration}ms ease`;
      setTimeout(() => {
        element.classList.remove(className);
      }, duration);
    } else {
      element.classList.remove(className);
    }
  }
};

// File Utilities
const FileUtils = {
  // Format file size
  formatFileSize: (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  },

  // Get file extension
  getExtension: (filename) => {
    return filename.split('.').pop().toLowerCase();
  },

  // Check if file type is allowed
  isAllowedType: (filename, allowedTypes = []) => {
    const extension = FileUtils.getExtension(filename);
    return allowedTypes.includes(extension);
  },

  // Download data as file
  downloadAsFile: (data, filename, mimeType = 'text/plain') => {
    const blob = new Blob([data], { type: mimeType });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  }
};

// Validation Utilities
const ValidationUtils = {
  // Validate email
  isValidEmail: (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  },

  // Validate Indonesian phone number
  isValidPhoneNumber: (phone) => {
    const phoneRegex = /^(\+62|62|0)[0-9]{9,13}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
  },

  // Validate required fields
  validateRequired: (value) => {
    return value !== null && value !== undefined && value.toString().trim() !== '';
  },

  // Validate minimum length
  validateMinLength: (value, minLength) => {
    return value && value.length >= minLength;
  },

  // Validate numeric input
  isNumeric: (value) => {
    return !isNaN(value) && isFinite(value);
  },

  // Validate Indonesian NIK (16 digits)
  isValidNIK: (nik) => {
    const nikRegex = /^[0-9]{16}$/;
    return nikRegex.test(nik);
  }
};

// Performance Utilities
const PerformanceUtils = {
  // Debounce function
  debounce: (func, wait, immediate = false) => {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        timeout = null;
        if (!immediate) func(...args);
      };
      const callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func(...args);
    };
  },

  // Throttle function
  throttle: (func, limit) => {
    let inThrottle;
    return function(...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  },

  // Measure execution time
  measureTime: (func, label = 'Function') => {
    const start = performance.now();
    const result = func();
    const end = performance.now();
    console.log(`${label} executed in ${(end - start).toFixed(2)} milliseconds`);
    return result;
  }
};

// Export utilities for global use
if (typeof window !== 'undefined') {
  window.DateUtils = DateUtils;
  window.NumberUtils = NumberUtils;
  window.StringUtils = StringUtils;
  window.ArrayUtils = ArrayUtils;
  window.StorageUtils = StorageUtils;
  window.DOMUtils = DOMUtils;
  window.FileUtils = FileUtils;
  window.ValidationUtils = ValidationUtils;
  window.PerformanceUtils = PerformanceUtils;
}

// Console log for debugging
console.log('SIPANDU DATA Utils loaded successfully');