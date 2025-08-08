// Application JavaScript
class GamingPlatform {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeApp();
    }

    bindEvents() {
        // Login form submission
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', this.handleLogin.bind(this));
        }

        // Register form submission
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', this.handleRegister.bind(this));
        }

        // Game card interactions
        document.addEventListener('click', this.handleGameClick.bind(this));

        // Category filtering
        document.addEventListener('click', this.handleCategoryClick.bind(this));

        // Language selection
        document.addEventListener('click', this.handleLanguageChange.bind(this));

        // Favorite toggles
        document.addEventListener('click', this.handleFavoriteToggle.bind(this));

        // Window resize handling
        window.addEventListener('resize', this.handleResize.bind(this));
    }

    initializeApp() {
        // Initialize any app-specific functionality
        this.loadUserPreferences();
        this.initializeGameCards();
        this.setupIntersectionObserver();
    }

    async handleLogin(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="loading"></div>';
        submitBtn.disabled = true;

        try {
            const response = await fetch('/api/login.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Login successful!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                this.showNotification(result.message, 'error');
            }
        } catch (error) {
            this.showNotification('Login failed. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    async handleRegister(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');

        // Client-side validation
        const password = formData.get('password');
        const confirmPassword = formData.get('confirm_password');

        if (password !== confirmPassword) {
            this.showNotification('Passwords do not match!', 'error');
            return;
        }

        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="loading"></div>';
        submitBtn.disabled = true;

        try {
            const response = await fetch('/api/register.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Registration successful!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                this.showNotification(result.message, 'error');
            }
        } catch (error) {
            this.showNotification('Registration failed. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    handleGameClick(e) {
        const gameCard = e.target.closest('.game-card');
        if (!gameCard) return;

        // Handle play button
        if (e.target.closest('.btn-play')) {
            e.stopPropagation();
            const gameId = gameCard.dataset.gameId;
            this.playGame(gameId);
            return;
        }

        // Handle favorite button
        if (e.target.closest('.btn-favorite')) {
            e.stopPropagation();
            return; // Handled by handleFavoriteToggle
        }

        // Handle game card click
        const gameId = gameCard.dataset.gameId;
        this.showGameDetails(gameId);
    }

    handleCategoryClick(e) {
        const categoryCard = e.target.closest('.category-card');
        if (!categoryCard) return;

        const categoryId = categoryCard.dataset.category;
        this.filterGamesByCategory(categoryId);

        // Update active category
        document.querySelectorAll('.category-card').forEach(card => {
            card.classList.remove('active');
        });
        categoryCard.classList.add('active');
    }

    async handleFavoriteToggle(e) {
        if (!e.target.closest('.btn-favorite')) return;
        
        e.stopPropagation();
        const gameCard = e.target.closest('.game-card');
        const gameId = gameCard.dataset.gameId;
        const favoriteBtn = e.target.closest('.btn-favorite');
        const icon = favoriteBtn.querySelector('i');

        // Check if user is logged in
        if (!this.isUserLoggedIn()) {
            this.showNotification('Please login to add favorites', 'warning');
            return;
        }

        const isFavorited = icon.classList.contains('fas');
        
        try {
            const response = await fetch('/api/toggle-favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    game_id: gameId,
                    action: isFavorited ? 'remove' : 'add'
                })
            });

            const result = await response.json();

            if (result.success) {
                if (isFavorited) {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.showNotification('Removed from favorites', 'info');
                } else {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.showNotification('Added to favorites', 'success');
                }
            }
        } catch (error) {
            this.showNotification('Failed to update favorites', 'error');
        }
    }

    handleLanguageChange(e) {
        const langBtn = e.target.closest('.lang-btn');
        if (!langBtn) return;

        const language = langBtn.dataset.lang;
        
        // Update active language
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        langBtn.classList.add('active');

        // Store preference
        localStorage.setItem('preferred_language', language);
        
        // Here you would implement actual language switching
        this.switchLanguage(language);
    }

    playGame(gameId) {
        if (!this.isUserLoggedIn()) {
            this.showNotification('Please login to play games', 'warning');
            return;
        }

        this.showNotification('Launching game...', 'info');
        
        // Here you would implement actual game launching
        setTimeout(() => {
            window.open(`/game/${gameId}`, '_blank');
        }, 1000);
    }

    showGameDetails(gameId) {
        // Here you would show game details modal or navigate to game page
        console.log(`Showing details for game ${gameId}`);
    }

    filterGamesByCategory(categoryId) {
        const gameCards = document.querySelectorAll('.game-card');
        
        gameCards.forEach(card => {
            // Here you would implement actual filtering logic
            // For now, we'll just show all games
            card.style.display = 'block';
        });

        this.showNotification(`Showing games for category ${categoryId}`, 'info');
    }

    switchLanguage(language) {
        // Here you would implement actual language switching
        console.log(`Switching to language: ${language}`);
    }

    initializeGameCards() {
        const gameCards = document.querySelectorAll('.game-card');
        
        gameCards.forEach((card, index) => {
            // Add entrance animation delay
            card.style.animationDelay = `${index * 0.1}s`;
            
            // Add hover effects
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    setupIntersectionObserver() {
        const options = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, options);

        // Observe game cards for lazy loading effects
        document.querySelectorAll('.game-card').forEach(card => {
            observer.observe(card);
        });
    }

    loadUserPreferences() {
        // Load saved language preference
        const savedLanguage = localStorage.getItem('preferred_language');
        if (savedLanguage) {
            const langBtn = document.querySelector(`.lang-btn[data-lang="${savedLanguage}"]`);
            if (langBtn) {
                langBtn.classList.add('active');
            }
        }

        // Load other preferences
        const theme = localStorage.getItem('theme');
        if (theme) {
            document.body.classList.add(`theme-${theme}`);
        }
    }

    handleResize() {
        // Handle responsive behavior
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 768 && sidebar && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
        }
    }

    isUserLoggedIn() {
        // Check if user is logged in (you might want to use a more robust method)
        return document.querySelector('.user-menu') !== null;
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span>${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Add styles if not already present
        if (!document.querySelector('#notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 10000;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    color: white;
                    min-width: 300px;
                    animation: slideInRight 0.3s ease;
                }
                .notification-info { background: #3b82f6; }
                .notification-success { background: #22c55e; }
                .notification-warning { background: #f59e0b; }
                .notification-error { background: #ef4444; }
                .notification-content {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .notification-close {
                    background: none;
                    border: none;
                    color: white;
                    font-size: 1.2rem;
                    cursor: pointer;
                    margin-left: 1rem;
                }
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(styles);
        }

        // Handle close button
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        });

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
}

// Sidebar functions (global scope for onclick handlers)
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('open');
    }
}

async function logout() {
    try {
        const response = await fetch('/api/logout.php', {
            method: 'POST'
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Logout failed:', error);
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new GamingPlatform();
});

// Service Worker registration for PWA capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
