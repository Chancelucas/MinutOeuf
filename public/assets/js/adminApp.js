const adminApp = {
    init() {
        this.initEventListeners();
        this.loadDashboard();
    },

    initEventListeners() {
        const addEggButton = document.getElementById('addEgg');
        if (addEggButton) {
            addEggButton.addEventListener('click', () => {
                const eggForm = document.getElementById('eggForm');
                if (eggForm) {
                    eggForm.reset();
                    delete eggForm.dataset.eggId;
                }
                document.getElementById('eggModal').style.display = 'block';
            });
        }
    },

    async loadDashboard() {
        try {
            const response = await fetch('/admin/eggs');
            const data = await response.json();
            
            if (data.success) {
                this.renderEggsTable(data.eggs);
            } else {
                this.showNotification('Erreur lors du chargement des données', 'error');
            }
        } catch (error) {
            this.showNotification('Erreur lors du chargement des données', 'error');
        }
    },

    renderEggsTable(eggs) {
        const table = document.querySelector('.table');
        if (!table) return;

        const headers = ['Nom', 'Temps (min)', 'Instructions', 'Image', 'Actions'];
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        
        headers.forEach(header => {
            const th = document.createElement('th');
            th.textContent = header;
            headerRow.appendChild(th);
        });
        
        thead.appendChild(headerRow);
        
        const tbody = document.createElement('tbody');
        eggs.forEach(egg => {
            const row = document.createElement('tr');
            
            // Cellules de données
            row.innerHTML = `
                <td>${egg.name}</td>
                <td>${egg.minutes}</td>
                <td>${egg.instructions}</td>
                <td>${egg.image ? '<img src="' + egg.image + '" alt="' + egg.name + '" class="thumbnail">' : 'Aucune image'}</td>
                <td class="actions">
                    <button class="btn btn-edit" onclick="adminApp.editEgg('${egg._id}')">Modifier</button>
                    <button class="btn btn-delete" onclick="adminApp.deleteEgg('${egg._id}')">Supprimer</button>
                </td>
            `;
            
            tbody.appendChild(row);
        });

        table.innerHTML = '';
        table.appendChild(thead);
        table.appendChild(tbody);
    },

    async editEgg(eggId) {
        try {
            const response = await fetch(`/admin/eggs/${eggId}`);
            const data = await response.json();
            
            if (data.success) {
                const form = document.getElementById('eggForm');
                form.dataset.eggId = eggId;
                
                // Remplir le formulaire
                form.elements.name.value = data.egg.name;
                form.elements.minutes.value = data.egg.minutes;
                form.elements.instructions.value = data.egg.instructions;
                form.elements.image.value = data.egg.image || '';
                
                document.getElementById('eggModal').style.display = 'block';
            } else {
                this.showNotification('Erreur lors du chargement de l\'œuf', 'error');
            }
        } catch (error) {
            this.showNotification('Erreur lors du chargement de l\'œuf', 'error');
        }
    },

    async deleteEgg(eggId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet œuf ?')) return;

        try {
            const response = await fetch(`/admin/eggs/${eggId}`, {
                method: 'DELETE'
            });
            
            const data = await response.json();
            if (data.success) {
                this.showNotification('Œuf supprimé avec succès');
                this.loadDashboard();
            } else {
                this.showNotification('Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            this.showNotification('Erreur lors de la suppression', 'error');
        }
    },

    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    adminApp.init();
});
