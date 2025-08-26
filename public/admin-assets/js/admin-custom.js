// Custom Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const wrapper = document.querySelector('.wrapper');
    const sidebar = document.querySelector('.app-sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            wrapper.classList.toggle('nav-collapsed');
        });
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.add('hide-sidebar');
        });
    }
    
    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 991.98) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show-sidebar');
            }
        }
    });
    
    // Show sidebar on mobile when toggle is clicked
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 991.98) {
                e.preventDefault();
                sidebar.classList.toggle('show-sidebar');
            }
        });
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991.98) {
            sidebar.classList.remove('show-sidebar');
        }
    });
    
    // Initialize perfect scrollbar for sidebar if available
    if (typeof PerfectScrollbar !== 'undefined') {
        const sidebarContent = document.querySelector('.sidebar-content');
        if (sidebarContent) {
            new PerfectScrollbar(sidebarContent);
        }
    }
    
    // Initialize match height for cards
    if (typeof $.fn.matchHeight !== 'undefined') {
        setTimeout(function() {
            $('.row.match-height').each(function() {
                $(this).find('.card').not('.card .card').matchHeight();
            });
        }, 500);
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.parentNode) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
    
    // Initialize tooltips if Bootstrap is available
    if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Initialize popovers if Bootstrap is available
    if (typeof $ !== 'undefined' && $.fn.popover) {
        $('[data-toggle="popover"]').popover();
    }
    
    // Handle form submissions with loading states
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="icon-spinner icon-spin"></i> در حال پردازش...';
            }
        });
    });
    
    // Handle delete confirmations
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Initialize DataTables if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.datatable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Persian.json'
            }
        });
    }
    
    // Handle chart initialization errors gracefully
    window.addEventListener('error', function(e) {
        if (e.message && e.message.includes('chartist')) {
            console.warn('Chartist error suppressed - using Chart.js instead');
            e.preventDefault();
        }
    });
});
