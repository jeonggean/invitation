// Fungsi untuk membuka undangan
function bukaUndangan() {
    const landing = document.getElementById('landing');
    const mainContent = document.getElementById('main-content');
    
    // Fade out landing page
    landing.style.opacity = '0';
    landing.style.transition = 'opacity 0.5s';
    
    setTimeout(() => {
        landing.classList.remove('active');
        landing.style.display = 'none';
        
        // Show main content
        mainContent.classList.add('show');
        
        // Scroll to top
        window.scrollTo(0, 0);
    }, 500);
}

// Smooth scroll untuk link internal
document.addEventListener('DOMContentLoaded', function() {
    // Auto scroll ke section wishes jika ada hash
    if (window.location.hash === '#wishes') {
        setTimeout(() => {
            const wishesSection = document.getElementById('wishes');
            if (wishesSection) {
                wishesSection.scrollIntoView({ behavior: 'smooth' });
            }
        }, 600);
    }
    
    // Validasi form
    const form = document.querySelector('.wish-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nama = form.querySelector('input[name="nama"]').value.trim();
            const pesan = form.querySelector('textarea[name="pesan"]').value.trim();
            
            if (!nama || !pesan) {
                e.preventDefault();
                alert('Mohon isi nama dan pesan Anda');
                return false;
            }
        });
    }
});