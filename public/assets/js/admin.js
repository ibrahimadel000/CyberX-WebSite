/**
 * CyberX Admin JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }

    // Flash message auto-hide
    const alerts = document.querySelectorAll('.admin-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Form loading state
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            // Find the button that was actually clicked (the submitter)
            const clickedBtn = e.submitter;

            if (clickedBtn && clickedBtn.matches('button[type="submit"]')) {
                // If the button has a name and value, preserve them in hidden inputs
                // This is needed because disabling the button removes it from form data
                if (clickedBtn.name && clickedBtn.value) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = clickedBtn.name;
                    hiddenInput.value = clickedBtn.value;
                    this.appendChild(hiddenInput);
                }

                clickedBtn.disabled = true;
                const originalContent = clickedBtn.innerHTML;
                clickedBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    clickedBtn.disabled = false;
                    clickedBtn.innerHTML = originalContent;
                }, 10000);
            }
        });
    });

    // Confirm dialogs for delete actions
    document.querySelectorAll('a[href*="delete"]').forEach(link => {
        if (!link.hasAttribute('onclick')) {
            link.addEventListener('click', function (e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        }
    });

});

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.opacity = '1';
        modal.style.visibility = 'visible';
        modal.querySelector('.modal').style.transform = 'scale(1)';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.opacity = '0';
        modal.style.visibility = 'hidden';
        modal.querySelector('.modal').style.transform = 'scale(0.9)';
        document.body.style.overflow = '';

        // If in edit mode, redirect to clear URL
        if (window.location.search.includes('edit=')) {
            window.location.href = window.location.pathname;
        }
    }
}

// Close modal on escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active, .modal-overlay[style*="visible"]').forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});
