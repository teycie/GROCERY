document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('nav');

    if (navbar && !navbar.classList.contains('sticky')) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                if (navbar.classList.contains('absolute') || navbar.classList.contains('relative')) {
                    navbar.classList.add('scrolled', 'fixed', 'w-full', 'top-0');
                    navbar.classList.remove('relative');
                }
            } else if (navbar.classList.contains('fixed')) {
                navbar.classList.remove('scrolled', 'fixed', 'w-full', 'top-0');
                navbar.classList.add('relative');
            }
        });
    }

    document.querySelectorAll('.grid > div.group').forEach(card => {
        card.classList.add('category-card');
    });

    document.querySelectorAll('a, button').forEach(btn => {
        if (btn.classList.contains('bg-green-500') || btn.classList.contains('btn-primary')) {
            btn.classList.add('btn-animate');
        }
    });

    const featureCards = document.querySelectorAll('.grid > div.bg-white');
    featureCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
    });

    const revealCards = () => {
        featureCards.forEach(card => {
            if (card.getBoundingClientRect().top < window.innerHeight - 50) {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }
        });
    };

    window.addEventListener('scroll', revealCards);
    revealCards();

    const alertBoxes = document.querySelectorAll('.alert, [role="alert"]');
    if (alertBoxes.length > 0) {
        setTimeout(() => {
            alertBoxes.forEach(box => {
                box.style.transition = 'opacity 0.5s ease';
                box.style.opacity = '0';
                setTimeout(() => box.remove(), 500);
            });
        }, 4000);
    }

    document.querySelectorAll('form.delete-form, form[method="POST"] input[name="_method"][value="DELETE"]').forEach(form => {
        const formElement = form.tagName === 'FORM' ? form : form.closest('form');
        if (!formElement) {
            return;
        }

        formElement.addEventListener('submit', event => {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                event.preventDefault();
            }
        });
    });

    const openSystemSettingsBtn = document.getElementById('open-system-settings');
    const appearanceSection = document.getElementById('appearance-section');

    if (openSystemSettingsBtn && appearanceSection) {
        openSystemSettingsBtn.setAttribute('aria-expanded', 'false');

        openSystemSettingsBtn.addEventListener('click', () => {
            const isHidden = appearanceSection.classList.toggle('hidden');
            openSystemSettingsBtn.setAttribute('aria-expanded', String(!isHidden));

            if (!isHidden) {
                appearanceSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    }

    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleLabel = document.getElementById('theme-toggle-label');
    const themeToggleKnob = document.getElementById('theme-toggle-knob');

    if (themeToggleBtn && themeToggleLabel && themeToggleKnob) {
        const currentTheme = localStorage.getItem('color-theme');
        if (currentTheme === 'dark' || (!currentTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        const syncThemeUI = () => {
            const isDark = document.documentElement.classList.contains('dark');
            themeToggleLabel.textContent = isDark ? 'Dark Mode' : 'Light Mode';
            themeToggleKnob.classList.toggle('translate-x-5', isDark);
            themeToggleKnob.classList.toggle('translate-x-1', !isDark);
            themeToggleBtn.setAttribute('aria-pressed', String(isDark));
        };

        syncThemeUI();

        themeToggleBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            syncThemeUI();
        });
    }
});
