// assets/js/app.js

document.addEventListener('DOMContentLoaded', () => {
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    let isSidebarOpen = false;

    const applySidebarState = () => {
        if (!sidebar) {
            return;
        }

        const isMobile = window.innerWidth <= 991;
        if (isMobile) {
            sidebar.classList.remove('collapsed');
            sidebar.classList.toggle('show', isSidebarOpen);
            document.body.classList.toggle('sidebar-open', isSidebarOpen);
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('active', isSidebarOpen);
            }
        } else {
            const savedState = localStorage.getItem('sidebarCollapsed') === 'true';
            sidebar.classList.toggle('collapsed', savedState);
            sidebar.classList.remove('show');
            document.body.classList.remove('sidebar-open');
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            isSidebarOpen = false;
        }
    };

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;
            if (isMobile) {
                isSidebarOpen = !isSidebarOpen;
            } else {
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
            applySidebarState();
        });

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                isSidebarOpen = false;
                applySidebarState();
            });
        }

        window.addEventListener('resize', applySidebarState);
        applySidebarState();
    }

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            navLinks.classList.toggle('open');
            const isExpanded = navLinks.classList.contains('open');
            navToggle.setAttribute('aria-expanded', String(isExpanded));
        });

        document.addEventListener('click', (event) => {
            if (!navLinks.contains(event.target) && !navToggle.contains(event.target)) {
                navLinks.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // Generic Fetch Wrapper
    window.apiCall = async (url, method = 'GET', data = null) => {
        // Prepend BASE_URL to absolute URLs within the application if defined and not already prepended
        if (url.startsWith('/') && typeof BASE_URL !== 'undefined' && !url.startsWith(BASE_URL + '/')) {
            url = BASE_URL + url;
        }

        const options = {
            method,
            headers: {
                'Content-Type': 'application/json'
            }
        };

        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            return await response.json();
        } catch (error) {
            console.error('API Call Error:', error);
            return { success: false, message: 'Network error or server unavailable' };
        }
    };

    // Generic Form Validation
    const forms = document.querySelectorAll('.validate-form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            let isValid = true;
            const inputs = form.querySelectorAll('[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                    // Add error message if not present
                    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-msg')) {
                        const msg = document.createElement('span');
                        msg.className = 'error-msg';
                        msg.style.color = 'red';
                        msg.style.fontSize = '0.8rem';
                        msg.innerText = 'This field is required';
                        input.after(msg);
                    }
                } else {
                    input.classList.remove('error');
                    if (input.nextElementSibling && input.nextElementSibling.classList.contains('error-msg')) {
                        input.nextElementSibling.remove();
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
});
