// SIPANDU DATA - Utility Functions
// Add API-specific utilities

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

// ... (utility functions lainnya tetap sama)