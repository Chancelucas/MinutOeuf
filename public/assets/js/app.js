// État de l'application
let currentEgg = null;
let timerInterval = null;
let timeLeft = 0;
let isPaused = false;

// Fonctions pour la partie publique
const publicApp = {
    // Charger les œufs depuis l'API
    async loadEggs() {
        try {
            const response = await fetch('/api/eggs');
            const data = await response.json();
            if (data.success) {
                this.renderEggList(data.data);
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    },

    // Afficher la liste des œufs
    renderEggList(eggs) {
        const eggList = document.getElementById('eggList');
        if (!eggList) return;

        eggList.innerHTML = eggs.map(egg => `
            <button class="egg-item" data-egg-name="${egg.name}" data-minutes="${egg.minutes}">
                ${egg.name}
            </button>
        `).join('');

        // Ajouter les écouteurs d'événements pour les œufs
        document.querySelectorAll('.egg-item').forEach(button => {
            button.addEventListener('click', () => {
                // Retirer la classe active de tous les œufs
                document.querySelectorAll('.egg-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Ajouter la classe active à l'œuf sélectionné
                button.classList.add('active');
                
                // Mettre à jour le contenu
                this.showEggDetails(button.dataset.eggName);
            });
        });
    },

    // Afficher les détails d'un œuf
    async showEggDetails(eggName) {
        try {
            const response = await fetch(`/api/eggs/${eggName}`);
            const data = await response.json();
            
            if (data.success) {
                const egg = data.data;
                document.getElementById('eggTitle').textContent = egg.name;
                document.getElementById('eggInstructions').innerHTML = egg.instructions
                    .split('\n')
                    .map(instruction => `<li>${instruction}</li>`)
                    .join('');
                
                // Masquer le message de bienvenue et afficher le contenu
                document.getElementById('welcomeMessage').classList.add('hidden');
                document.getElementById('eggContent').classList.remove('hidden');
                
                // Mettre à jour le minuteur
                timeLeft = egg.minutes * 60;
                this.updateTimerDisplay();
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    },

    // Démarrer le minuteur
    startTimer() {
        const minutesElement = document.getElementById('minutes');
        if (!minutesElement) return;

        // Si le minuteur est en pause, reprendre là où on s'est arrêté
        if (!isPaused) {
            const minutes = parseInt(minutesElement.textContent);
            timeLeft = minutes * 60;
        }

        // Réinitialiser l'état de pause
        isPaused = false;

        // Activer/désactiver les boutons appropriés
        document.getElementById('startTimer').disabled = true;
        document.getElementById('pauseTimer').disabled = false;
        document.getElementById('resetTimer').disabled = false;

        // Démarrer le minuteur
        timerInterval = setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                this.updateTimerDisplay();
            } else {
                this.timerEnd();
            }
        }, 1000);
    },

    // Mettre en pause le minuteur
    pauseTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            isPaused = true;

            // Activer/désactiver les boutons appropriés
            document.getElementById('startTimer').disabled = false;
            document.getElementById('pauseTimer').disabled = true;
        }
    },

    // Réinitialiser le minuteur
    resetTimer() {
        // Arrêter le minuteur
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        // Réinitialiser l'état
        isPaused = false;

        // Réinitialiser l'affichage
        const minutesElement = document.getElementById('minutes');
        if (minutesElement) {
            const initialMinutes = parseInt(minutesElement.textContent);
            timeLeft = initialMinutes * 60;
            this.updateTimerDisplay();
        }

        // Réinitialiser les boutons
        document.getElementById('startTimer').disabled = false;
        document.getElementById('pauseTimer').disabled = true;
        document.getElementById('resetTimer').disabled = true;
    },

    // Mettre à jour l'affichage du minuteur
    updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    },

    // Gérer la fin du minuteur
    timerEnd() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        // Réinitialiser les boutons
        document.getElementById('startTimer').disabled = false;
        document.getElementById('pauseTimer').disabled = true;
        document.getElementById('resetTimer').disabled = true;

        // Afficher une notification
        this.showNotification("Temps écoulé ! Votre œuf est prêt !");
    },

    // Afficher une notification
    showNotification(message) {
        const notification = document.getElementById('notification');
        if (!notification) return;

        notification.textContent = message;
        notification.classList.add('show');

        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
};

// Fonctions pour la partie admin
const adminApp = {
    // Charger le tableau de bord admin
    async loadDashboard() {
        try {
            const response = await fetch('/api/eggs');
            const data = await response.json();
            if (data.success) {
                this.renderDashboard(data.data);
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    },

    // Afficher le tableau de bord
    renderDashboard(eggs) {
        const table = document.querySelector('.admin-table tbody');
        if (!table) return;

        table.innerHTML = eggs.map(egg => `
            <tr>
                <td>${egg.name}</td>
                <td>${egg.minutes} minutes</td>
                <td>${egg.instructions}</td>
                <td>
                    <button onclick="adminApp.editEgg('${egg.name}')" class="btn btn-primary">Modifier</button>
                    <button onclick="adminApp.deleteEgg('${egg.name}')" class="btn btn-danger">Supprimer</button>
                </td>
            </tr>
        `).join('');
    },

    // Initialiser les écouteurs d'événements
    initializeEventListeners() {
        const addEggForm = document.getElementById('addEggForm');
        if (addEggForm) {
            addEggForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(addEggForm);
                const eggData = Object.fromEntries(formData.entries());
                
                try {
                    const response = await fetch('/admin/eggs', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(eggData)
                    });
                    
                    if (response.ok) {
                        this.loadDashboard();
                        addEggForm.reset();
                        document.getElementById('addEggModal').style.display = 'none';
                        this.showNotification('Œuf ajouté avec succès !');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                }
            });
        }
    },

    // Afficher le modal d'ajout d'œuf
    showAddEggModal() {
        const modal = document.getElementById('addEggModal');
        if (modal) {
            modal.style.display = 'block';
        }
    },

    // Modifier un œuf
    editEgg(eggName) {
        // À implémenter
    },

    // Supprimer un œuf
    async deleteEgg(eggName) {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer l'œuf "${eggName}" ?`)) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/eggs/${eggName}`, {
                method: 'DELETE'
            });
            
            if (response.ok) {
                this.loadDashboard();
                this.showNotification('Œuf supprimé avec succès !');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    },

    // Afficher une notification
    showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-success';
        notification.textContent = message;
        
        document.querySelector('.admin-content').prepend(notification);
        
        setTimeout(() => notification.remove(), 5000);
    }
};

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    // Détecter si nous sommes sur la page admin
    const isAdminPage = window.location.pathname.startsWith('/admin');
    
    if (isAdminPage) {
        adminApp.loadDashboard();
        adminApp.initializeEventListeners();
    } else {
        publicApp.loadEggs();
    }
});
