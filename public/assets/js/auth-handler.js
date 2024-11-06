// public/assets/js/auth-handler.js

export function initializeAuth() {
    const token = sessionStorage.getItem('jwt');
    if (token) {
        setupAuthToken();
        checkTokenAndUpdateUI();
    }
}

export function setupAuthToken() {
    const token = sessionStorage.getItem('jwt');
    if (!token) return;

    // Add JWT token to all fetch requests
    const originalFetch = window.fetch;
    window.fetch = function() {
        let [resource, config] = arguments;
        if (config === undefined) {
            config = {};
        }
        if (!config.headers) {
            config.headers = {};
        }
        config.headers['Authorization'] = `Bearer ${token}`;
        return originalFetch(resource, config);
    };
}

export function handleLogout(event) {
    event.preventDefault();
    sessionStorage.removeItem('jwt');
    window.location.href = event.target.href;
}

function parseJwt(token) {
    try {
        const base64Url = token.split('.')[1];
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        const payload = decodeURIComponent(atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
        return JSON.parse(payload);
    } catch(e) {
        return null;
    }
}

function checkTokenAndUpdateUI() {
    const token = sessionStorage.getItem('jwt');
    if (!token) return;

    const payload = parseJwt(token);
    if (payload && payload.exp) {
        // Check token expiration
        if (Date.now() >= payload.exp * 1000) {
            sessionStorage.removeItem('jwt');
            window.location.reload();
            return;
        }

        // Fetch user data and update UI
        fetch('/api/users', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.user) {
                    updateAuthUI(data.user);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

// Start periodic token check
setInterval(() => {
    const token = sessionStorage.getItem('jwt');
    if (token) {
        const payload = parseJwt(token);
        if (payload && payload.exp && Date.now() >= payload.exp * 1000) {
            sessionStorage.removeItem('jwt');
            window.location.reload();
        }
    }
}, 60000); // Check every minute
