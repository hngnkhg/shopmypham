// ✅ Bootstrap CSS
import "bootstrap/dist/css/bootstrap.min.css";
// ✅ Bootstrap JS (rất quan trọng)
import "bootstrap/dist/js/bootstrap.bundle.min.js";
// ✅ Font Awesome nếu dùng icon
import "@fortawesome/fontawesome-free/css/all.min.css";

import { createApp } from 'vue';
import { createPinia } from 'pinia'; // Import createPinia
import App from '@/App.vue';
import router from '@/routes/index.js'; // Import router
import apiClient from '@/plugins/axios.js'; // Make sure this path is correct for your axios instance

// Import your auth store. Adjust the path if your stores directory is different.
import { useAuthStore } from '@/stores/auth.js'; 

const app = createApp(App);
const pinia = createPinia(); // Create a Pinia instance

app.use(pinia); // Use Pinia before using router or mounting the app
app.use(router); // Kích hoạt router

// --- Pinia and Axios Integration ---

// Optional: Set up an Axios interceptor to automatically add the token to requests
// This ensures that when the app reloads, if a token is present in localStorage,
// it's used for subsequent API calls.
const storedToken = localStorage.getItem('token');
if (storedToken) {
  apiClient.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
}

// Global Axios interceptor for response errors (e.g., 401 Unauthorized)
// This can be used to automatically log out users if their token expires or is invalid.
apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    // Access the auth store after Pinia has been installed.
    // This needs to be done carefully as `useAuthStore` might not be ready
    // if accessed too early during app initialization.
    // However, within an interceptor, it's generally safe as app setup is complete.
    const authStore = useAuthStore(); 

    if (error.response && error.response.status === 401 && authStore.isAuthenticated) {
      // If 401 (Unauthorized) and the user was previously authenticated,
      // it means the token might be expired or invalid.
      console.warn('Unauthorized access. Token might be expired or invalid. Logging out...');
      authStore.logout(); // Log out the user through the store's action
      router.push('/login'); // Redirect to the login page
    }
    return Promise.reject(error); // Re-throw the error for component-level handling
  }
);

// Optional: Initialize auth state (e.g., fetch user data if token exists)
// This ensures that when the app reloads, if a token is present, user data is fetched.
// This call must come *after* `app.use(pinia)`.
const authStore = useAuthStore(); // Get the auth store instance
authStore.initializeAuth(); // Call the action to initialize auth state

// --- End Pinia and Axios Integration ---

app.mount('#app'); // Gắn vào DOM