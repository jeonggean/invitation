// ===== Fungsi untuk membuka undangan =====
function bukaUndangan() {
    const landing = document.getElementById('landing');
    const mainContent = document.getElementById('main-content');
    
    // Fade out landing page dengan animasi
    landing.style.opacity = '0';
    landing.style.transform = 'scale(0.9)';
    landing.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    
    setTimeout(() => {
        landing.classList.remove('active');
        landing.style.display = 'none';
        
        // Show main content dengan delay
        mainContent.classList.add('show');
        
        // Scroll to top
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        
        // Trigger reveal animations setelah main content muncul
        setTimeout(() => {
            revealOnScroll();
        }, 100);
    }, 600);
}

// ===== Reveal Animation on Scroll =====
function revealOnScroll() {
    const reveals = document.querySelectorAll('.reveal');
    
    reveals.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementBottom = element.getBoundingClientRect().bottom;
        const windowHeight = window.innerHeight;
        
        // Reveal jika element terlihat di viewport
        if (elementTop < windowHeight - 100 && elementBottom > 0) {
            element.classList.add('active');
        }
    });
}

// ===== Smooth Scroll untuk link internal =====
function smoothScrollTo(target) {
    const element = document.querySelector(target);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// ===== Form Validation dengan Feedback Visual =====
function validateForm(form) {
    const nama = form.querySelector('input[name="nama"]');
    const pesan = form.querySelector('textarea[name="pesan"]');
    let isValid = true;
    
    // Reset previous error states
    [nama, pesan].forEach(field => {
        field.style.borderColor = '';
        field.style.background = '';
    });
    
    // Validate nama
    if (!nama.value.trim()) {
        nama.style.borderColor = '#f44336';
        nama.style.background = '#ffebee';
        isValid = false;
        
        // Shake animation
        nama.style.animation = 'shake 0.5s';
        setTimeout(() => {
            nama.style.animation = '';
        }, 500);
    }
    
    // Validate pesan
    if (!pesan.value.trim()) {
        pesan.style.borderColor = '#f44336';
        pesan.style.background = '#ffebee';
        isValid = false;
        
        // Shake animation
        pesan.style.animation = 'shake 0.5s';
        setTimeout(() => {
            pesan.style.animation = '';
        }, 500);
    }
    
    return isValid;
}

// ===== Show Success Message =====
function showSuccessMessage() {
    const message = document.createElement('div');
    message.className = 'success-message';
    message.innerHTML = `
        <span class="success-icon">✓</span>
        <span>Ucapan Anda berhasil dikirim!</span>
    `;
    message.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-100px);
        background: linear-gradient(135deg, #2e7d32, #43a047);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        box-shadow: 0 10px 40px rgba(46,125,50,0.4);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    `;
    
    document.body.appendChild(message);
    
    // Animate in
    setTimeout(() => {
        message.style.opacity = '1';
        message.style.transform = 'translateX(-50%) translateY(0)';
    }, 10);
    
    // Animate out
    setTimeout(() => {
        message.style.opacity = '0';
        message.style.transform = 'translateX(-50%) translateY(-100px)';
        
        setTimeout(() => {
            document.body.removeChild(message);
        }, 500);
    }, 3000);
}

// ===== Parallax Effect untuk Landing Page =====
function handleParallax(e) {
    const landing = document.getElementById('landing');
    if (!landing || !landing.classList.contains('active')) return;
    
    const particles = document.querySelectorAll('.particle');
    const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
    const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
    
    particles.forEach((particle, index) => {
        const speed = (index + 1) * 0.5;
        particle.style.transform = `translate(${moveX * speed}px, ${moveY * speed}px)`;
    });
}

// ===== Add shake animation to CSS =====
function addShakeAnimation() {
    if (!document.getElementById('shake-animation')) {
        const style = document.createElement('style');
        style.id = 'shake-animation';
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
    }
}

// ===== Lazy Loading untuk Images =====
function lazyLoadImages() {
    const images = document.querySelectorAll('img[src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    img.style.opacity = '1';
                }, 50);
                
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// ===== Counter Animation untuk Wishes =====
function animateWishCount() {
    const wishItems = document.querySelectorAll('.wish-item');
    
    wishItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-30px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 100);
    });
}

// ===== Enhanced Hover Effects =====
function addEnhancedHoverEffects() {
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn-buka, .btn-map, .btn-submit');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                left: ${x}px;
                top: ${y}px;
                pointer-events: none;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
            `;
            
            if (!document.getElementById('ripple-animation')) {
                const style = document.createElement('style');
                style.id = 'ripple-animation';
                style.textContent = `
                    @keyframes ripple {
                        to {
                            transform: scale(2);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            }
            
            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// ===== Smooth Background Scroll Effect =====
function handleBackgroundScroll() {
    const scrolled = window.pageYOffset;
    const sections = document.querySelectorAll('section');
    
    sections.forEach((section, index) => {
        if (index % 2 === 0) {
            section.style.transform = `translateY(${scrolled * 0.05}px)`;
        }
    });
}

// ===== Initialize Everything =====
document.addEventListener('DOMContentLoaded', function() {
    // Add shake animation CSS
    addShakeAnimation();
    
    // Auto scroll ke section wishes jika ada hash
    if (window.location.hash === '#wishes') {
        setTimeout(() => {
            const wishesSection = document.getElementById('wishes');
            if (wishesSection) {
                smoothScrollTo('#wishes');
            }
        }, 700);
    }
    
    // Form validation
    const form = document.querySelector('.wish-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
                return false;
            }
        });
        
        // Real-time validation feedback
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.style.borderColor = '#2e7d32';
                    this.style.background = 'white';
                } else {
                    this.style.borderColor = '';
                    this.style.background = '';
                }
            });
        });
    }
    
    // Scroll reveal animation
    window.addEventListener('scroll', () => {
        revealOnScroll();
        // Uncomment if you want parallax scroll effect
        // handleBackgroundScroll();
    });
    
    // Initial reveal check
    revealOnScroll();
    
    // Lazy load images
    lazyLoadImages();
    
    // Animate wish items if they exist
    setTimeout(() => {
        animateWishCount();
    }, 500);
    
    // Enhanced hover effects
    addEnhancedHoverEffects();
    
    // Parallax effect on landing page
    document.addEventListener('mousemove', handleParallax);
    
    // Add smooth scroll to all internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('href');
            if (target !== '#') {
                smoothScrollTo(target);
            }
        });
    });
    
    // Show success message if redirected from form submission
    if (window.location.hash === '#wishes' && document.referrer.includes(window.location.pathname)) {
        setTimeout(() => {
            showSuccessMessage();
        }, 500);
    }
    
    // Add entrance animation to cards
    const cards = document.querySelectorAll('.card-minimalis, .acara-card-new, .ayat-box');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 200 + (index * 150));
    });
});

// ===== Add loading animation =====
window.addEventListener('load', function() {
    document.body.style.opacity = '0';
    
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
});