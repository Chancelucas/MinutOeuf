/**
 * Gestion de l'authentification
 */

// Gestion du modal de connexion
const loginBtn = document.getElementById('loginBtn');
const loginModal = document.getElementById('loginModal');
const loginForm = document.getElementById('loginForm');
const loginError = document.getElementById('loginError');

if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        loginModal.style.display = 'block';
    });
}

function closeLoginModal() {
    loginModal.style.display = 'none';
    loginError.style.display = 'none';
    loginForm.reset();
}

window.addEventListener('click', (e) => {
    if (e.target === loginModal) {
        closeLoginModal();
    }
});

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(loginForm);
    
    try {
        const response = await fetch('/auth/login', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            loginError.textContent = data.error || 'Erreur de connexion';
            loginError.style.display = 'block';
        }
    } catch (error) {
        loginError.textContent = 'Erreur de connexion';
        loginError.style.display = 'block';
    }
});
