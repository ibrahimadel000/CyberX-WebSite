/**
 * CyberX Main JavaScript
 */

// ========== Lenis Smooth Scroll ==========
// Initialize Lenis for butter-smooth momentum-based scrolling
try {
    if (typeof Lenis !== 'undefined') {
        const lenis = new Lenis({
            duration: 1.0, // Faster response for less perceived lag
            easing: (t) => 1 - Math.pow(1 - t, 3), // Smooth easeOutCubic
            orientation: 'vertical',
            gestureOrientation: 'vertical',
            smoothWheel: true,
            wheelMultiplier: 1.0, // More responsive wheel
            touchMultiplier: 2,
            infinite: false,
        });

        // Use more efficient RAF loop
        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        // Make lenis globally available for scroll-to functionality
        window.lenis = lenis;
    }
} catch (e) {
    console.warn('Lenis smooth scroll not available:', e);
}

document.addEventListener('DOMContentLoaded', function () {

    // ========== Navigation Active State ==========
    // Set active state based on current page
    const currentPath = window.location.pathname.toLowerCase();
    const navLinks = document.querySelectorAll('.nav-link');

    // Check if currently on home page
    const isHomePage = currentPath.endsWith('/') || currentPath.endsWith('/index.php') || currentPath.endsWith('/cyberx') || currentPath.endsWith('/cyberx/') || currentPath.includes('/CyberX');

    navLinks.forEach(link => {
        const dataPage = link.getAttribute('data-page');
        let isActive = false;

        // Only match if the current path ends with the specific page name
        if (dataPage === 'home' && isHomePage) {
            isActive = true;
        } else if (dataPage === 'about' && currentPath.includes('/about.php')) {
            isActive = true;
        } else if (dataPage === 'services' && currentPath.includes('/services.php')) {
            isActive = true;
        } else if (dataPage === 'courses' && currentPath.includes('/courses.php')) {
            isActive = true;
        } else if (dataPage === 'contact' && currentPath.includes('/contact.php')) {
            isActive = true;
        }

        if (isActive) {
            link.classList.add('active');
        }

        // Add click handler to update active state
        link.addEventListener('click', function (e) {
            // If clicking Home while on home page, scroll to top instead of refreshing
            if (dataPage === 'home' && isHomePage) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            // Remove active from all links
            navLinks.forEach(l => l.classList.remove('active'));
            // Add active to clicked link
            this.classList.add('active');
        });
    });

    // Logo click - scroll to top if on home page
    const logoLink = document.querySelector('.logo-link');
    if (logoLink) {
        logoLink.addEventListener('click', function (e) {
            if (isHomePage) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }

    // Mobile Navigation Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // AJAX Search
    const searchInput = document.getElementById('nav-search');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 1) {
                searchResults.classList.remove('active');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetchSearchResults(query);
            }, 300);
        });

        searchInput.addEventListener('focus', function () {
            if (this.value.trim().length >= 1) {
                searchResults.classList.add('active');
            }
        });

        // Close search results when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.remove('active');
            }
        });
    }

    async function fetchSearchResults(query) {
        try {
            const response = await fetch(`/api/search-courses.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.results && data.results.length > 0) {
                let html = '';
                data.results.forEach(course => {
                    html += `
                        <a href="${course.url}" class="search-result-item">
                            <img src="${course.image}" alt="${course.title}">
                            <div class="result-info">
                                <h5>${highlightMatch(course.title, query)}</h5>
                                <span>${course.type.charAt(0).toUpperCase() + course.type.slice(1)} • ${course.price}</span>
                            </div>
                        </a>
                    `;
                });
                searchResults.innerHTML = html;
                searchResults.classList.add('active');
            } else {
                searchResults.innerHTML = '<div class="search-result-item"><span style="color: var(--text-muted);">No courses found</span></div>';
                searchResults.classList.add('active');
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    function highlightMatch(text, query) {
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return text.replace(regex, '<strong style="color: var(--accent);">$1</strong>');
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Flash Message Auto-hide
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.opacity = '0';
            flashMessage.style.transform = 'translateY(-20px)';
            setTimeout(() => flashMessage.remove(), 300);
        }, 5000);
    }

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form Validation Helpers
    window.validateEmail = function (email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    };

    window.validatePhone = function (phone) {
        return /^[\+]?[0-9\s\-\(\)]{8,20}$/.test(phone);
    };

    // Add loading state to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !this.dataset.noLoading) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    });

    // ========== OPTIMIZED SCROLL ANIMATIONS ==========
    // Use a single, efficient intersection observer for reveal animations
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Simple class toggle - CSS handles the animation
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Only observe elements with reveal-on-scroll class (opt-in)
    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
        revealObserver.observe(el);
    });

    // Add reveal animation styles
    const style = document.createElement('style');
    style.textContent = `
        .revealed {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);



    // Smooth scroll for all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add smooth ripple effect to buttons
    document.querySelectorAll('button, .btn, a[class*="btn"], .glow-btn-primary, .glow-btn-outline').forEach(button => {
        button.addEventListener('click', function (e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;

            this.style.position = this.style.position || 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add ripple animation keyframes
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(rippleStyle);

    // AJAX Course Filtering
    const filterForm = document.getElementById('filterForm');
    const coursesContent = document.querySelector('.courses-content');

    if (filterForm && coursesContent) {
        // Prevent default form submission and use AJAX instead
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            filterCourses(1);
        });

        // Auto-filter on radio button change
        filterForm.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function () {
                filterCourses(1);
            });
        });

        // Debounce search input
        let searchTimeout;
        const searchInput = filterForm.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterCourses(1);
                }, 500);
            });
        }
    }

    // Filter courses via AJAX
    window.filterCourses = async function (page = 1) {
        const filterForm = document.getElementById('filterForm');
        const coursesContent = document.querySelector('.courses-content');

        if (!filterForm || !coursesContent) return;

        // Get form data
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        for (let [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        params.append('page', page);

        // Update URL without reload
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);

        // Show loading state
        const resultsInfo = coursesContent.querySelector('div:first-child');
        if (resultsInfo) {
            resultsInfo.innerHTML = '<p style="color: var(--text-muted);"><i class="fas fa-spinner fa-spin"></i> Loading courses...</p>';
        }

        try {
            const response = await fetch(`/api/filter-courses.php?${params.toString()}`);
            const data = await response.json();

            if (data.success) {
                // Update results info
                const searchVal = formData.get('search') || '';
                let infoHtml = `<p style="color: var(--text-muted);">Showing ${data.showing} of ${data.total} courses`;
                if (searchVal) {
                    infoHtml += ` for "<strong>${escapeHtml(searchVal)}</strong>"`;
                }
                infoHtml += '</p>';

                // Build new content
                const newContent = `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
                        ${infoHtml}
                    </div>
                    ${data.html}
                `;

                coursesContent.innerHTML = newContent;

                // Re-observe cards for animation
                document.querySelectorAll('.course-card').forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                });
            }
        } catch (error) {
            console.error('Filter error:', error);
            coursesContent.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error Loading Courses</h3>
                    <p>Please try again or refresh the page.</p>
                </div>
            `;
        }
    };

    // Load page function for pagination
    window.loadPage = function (page) {
        filterCourses(page);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    // Escape HTML helper
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

});
