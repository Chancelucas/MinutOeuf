const forms = {
    // Gérer la soumission du formulaire de connexion
    initLoginForm() {
        const loginForm = document.getElementById('loginForm');
        if (!loginForm) return;

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
                    this.showError(loginForm, data.error || 'Erreur de connexion');
                }
            } catch (error) {
                this.showError(loginForm, 'Erreur de connexion');
            }
        });
    },

    // Gérer la soumission du formulaire d'œuf
    initEggForm() {
        const eggForm = document.getElementById('eggForm');
        if (!eggForm) return;

        eggForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(eggForm);
            const isEdit = eggForm.dataset.eggId;
            const url = isEdit ? `/admin/eggs/${eggForm.dataset.eggId}` : '/admin/eggs';

            try {
                const response = await fetch(url, {
                    method: isEdit ? 'POST' : 'POST',
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    this.closeModal('eggModal');
                    adminApp.loadDashboard();
                    adminApp.showNotification(isEdit ? 'Œuf modifié avec succès' : 'Œuf ajouté avec succès');
                } else {
                    this.showError(eggForm, data.error || 'Erreur lors de l\'enregistrement');
                }
            } catch (error) {
                this.showError(eggForm, 'Erreur lors de l\'enregistrement');
            }
        });
    },

    // Afficher une erreur dans un formulaire
    showError(form, message) {
        let errorDiv = form.querySelector('.form-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'form-error alert alert-error';
            form.prepend(errorDiv);
        }
        errorDiv.textContent = message;
    },

    // Fermer un modal
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'none';
    },

    // Initialiser les gestionnaires de modal
    initModalHandlers() {
        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', () => {
                const modal = button.closest('.modal');
                if (modal) modal.style.display = 'none';
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        });
    }
};

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    forms.initLoginForm();
    forms.initEggForm();
    forms.initModalHandlers();
});
