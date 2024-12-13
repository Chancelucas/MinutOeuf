/**
 * Gestion des fonctionnalités d'administration
 */

const addEggModal = document.getElementById('addEggModal');
const addEggForm = document.getElementById('addEggForm');

function showAddEggModal() {
    addEggModal.style.display = 'block';
}

function closeAddEggModal() {
    addEggModal.style.display = 'none';
    addEggForm.reset();
}

window.addEventListener('click', (e) => {
    if (e.target === addEggModal) {
        closeAddEggModal();
    }
});

addEggForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(addEggForm);
    
    try {
        const response = await fetch('/admin/eggs/add', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeAddEggModal();
            loadEggs(); // Recharger la liste des œufs
        } else {
            alert(data.error || 'Erreur lors de l\'ajout de l\'œuf');
        }
    } catch (error) {
        alert('Erreur lors de l\'ajout de l\'œuf');
    }
});
