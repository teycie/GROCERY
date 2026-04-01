document.addEventListener('DOMContentLoaded', () => {
    // 1. Sticky Navbar Effect on Scroll
    const navbar = document.querySelector('nav');
    
    if (navbar && !navbar.classList.contains('sticky')) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                if(navbar.classList.contains('absolute') || navbar.classList.contains('relative')){
                    navbar.classList.add('scrolled', 'fixed', 'w-full', 'top-0');
                    navbar.classList.remove('relative');
                }
            } else {
                if(navbar.classList.contains('fixed')){
                    navbar.classList.remove('scrolled', 'fixed', 'w-full', 'top-0');
                    navbar.classList.add('relative');
                }
            }
        });
    }

    // 2. Add dynamic 'category-card' classes to category sections
    const categories = document.querySelectorAll('.grid > div.group');
    categories.forEach(card => {
        card.classList.add('category-card');
    });

    // 3. Button Click Ripple effect
    const buttons = document.querySelectorAll('a, button');
    buttons.forEach(btn => {
        if (btn.classList.contains('bg-green-500') || btn.classList.contains('btn-primary')) {
            btn.classList.add('btn-animate');
        }
    });

    // 4. Smooth reveal animation for feature elements on scroll
    const featureCards = document.querySelectorAll('.grid > div.bg-white');
    featureCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = ll 0.6s ease-out "$"{"index * 0.1}s;
    });

    const revealCards = () => {
        featureCards.forEach(card => {
            const cardTop = card.getBoundingClientRect().top;
            if (cardTop < window.innerHeight - 50) {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }
        });
    };

    window.addEventListener('scroll', revealCards);
    revealCards(); // Trigger once on load

    // 5. Automatic fade out for Flash Alerts (System Interaction)
    const alertBoxes = document.querySelectorAll('.alert, [role=""alert""]');
    if (alertBoxes.length > 0) {
        setTimeout(() => {
            alertBoxes.forEach(box => {
                box.style.transition = 'opacity 0.5s ease';
                box.style.opacity = '0';
                setTimeout(() => box.remove(), 500); // Remove from DOM after fade
            });
        }, 4000); // Wait 4 seconds before hiding
    }
    
    // 6. Delete Confirmation check
    const deleteForms = document.querySelectorAll('form.delete-form, form[method=""POST""] input[name=""_method""][value=""DELETE""]');
    deleteForms.forEach(form => {
        const formElement = form.tagName === 'FORM' ? form : form.closest('form');
        formElement.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
