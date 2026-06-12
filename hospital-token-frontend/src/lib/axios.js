import axios from 'axios';

const api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// ── Request Interceptor ──────────────────────────────────────────────────────
// Automatically attaches the auth token to every request from localStorage.
// This ensures protected API calls work correctly even after a page refresh.
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// ── Response Interceptor ─────────────────────────────────────────────────────
// Handles 401 Unauthorized responses globally.
// If the server rejects the token (expired or invalid), the user is
// automatically logged out and redirected to the login page.
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Clear stale auth data
            localStorage.removeItem('token');
            localStorage.removeItem('hospital');

            // Only redirect if not already on the login page
            if (!window.location.pathname.includes('/login')) {
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

export default api;
