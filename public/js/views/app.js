class Navigation {
    constructor() {
        this.init();
    }

    init() {
        this.setupMobileMenu();
        this.setupDropdowns();
        this.setupSidebar();
        this.setupBackToTop();
    }

    setupMobileMenu() {
        const navbarToggle = document.getElementById('navbarToggle');
        const navbarNav = document.querySelector('.navbar-nav');

        if (navbarToggle && navbarNav) {
            navbarToggle.addEventListener('click', () => {
                navbarNav.classList.toggle('open');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!navbarToggle.contains(e.target) && !navbarNav.contains(e.target)) {
                    navbarNav.classList.remove('open');
                }
            });
        }
    }

    setupDropdowns() {
        // Nav dropdowns
        const navDropdowns = document.querySelectorAll('.nav-dropdown');
        
        navDropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.nav-dropdown-toggle');
            
            if (toggle) {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    dropdown.classList.toggle('open');
                    
                    // Close other dropdowns
                    navDropdowns.forEach(other => {
                        if (other !== dropdown) {
                            other.classList.remove('open');
                        }
                    });
                });
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.nav-dropdown')) {
                navDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
            }
        });
    }

    setupSidebar() {
        // Sidebar collapsible items
        const collapsibleItems = document.querySelectorAll('.sidebar-collapsible');
        
        collapsibleItems.forEach(item => {
            const link = item.querySelector('.sidebar-link');
            
            if (link) {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    item.classList.toggle('open');
                });
            }
        });

        // Mobile sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
    }

    setupBackToTop() {
        const backToTop = document.getElementById('backToTop');
        
        if (backToTop) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });

            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }
}

// Initialize navigation when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Navigation();
});