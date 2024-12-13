<div class="admin-container">
    <header class="admin-header">
        <h1>Administration des œufs</h1>
        <div class="admin-actions">
            <button id="addEggBtn" class="button primary">Ajouter un œuf</button>
            <a href="/auth/logout" class="button secondary">Déconnexion</a>
        </div>
    </header>

    <div class="eggs-list">
        <?php foreach ($eggs as $egg): ?>
            <div class="egg-card" data-egg-name="<?= htmlspecialchars($egg['name']) ?>">
                <div class="egg-card-header">
                    <h3><?= htmlspecialchars($egg['name']) ?></h3>
                    <div class="egg-card-actions">
                        <button class="edit-egg button secondary">Modifier</button>
                        <button class="delete-egg button danger">Supprimer</button>
                    </div>
                </div>
                <div class="egg-card-content">
                    <p><strong>Temps de cuisson:</strong> <?= $egg['cookingTime'] ?> minutes</p>
                    
                    <div class="egg-instructions">
                        <h4>Instructions</h4>
                        <ul>
                            <?php foreach ($egg['instructions'] as $instruction): ?>
                                <li><?= htmlspecialchars($instruction) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="egg-tips">
                        <h4>Conseils</h4>
                        <ul>
                            <?php foreach ($egg['tips'] as $tip): ?>
                                <li><?= htmlspecialchars($tip) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal pour ajouter/modifier un œuf -->
<div id="eggModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Ajouter un œuf</h2>
        <form id="eggForm">
            <div class="form-group">
                <label for="eggName">Nom</label>
                <input type="text" id="eggName" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="cookingTime">Temps de cuisson (minutes)</label>
                <input type="number" id="cookingTime" name="cookingTime" required min="1">
            </div>
            
            <div class="form-group">
                <label for="instructions">Instructions (une par ligne)</label>
                <textarea id="instructions" name="instructions" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="tips">Conseils (un par ligne)</label>
                <textarea id="tips" name="tips" required></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="submit" class="button primary">Enregistrer</button>
                <button type="button" class="button secondary" onclick="closeModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
// État
let currentEgg = null;

// Éléments du DOM
const modal = document.getElementById('eggModal');
const modalTitle = document.getElementById('modalTitle');
const eggForm = document.getElementById('eggForm');

// Ouvrir le modal pour ajouter un œuf
document.getElementById('addEggBtn').addEventListener('click', () => {
    currentEgg = null;
    modalTitle.textContent = 'Ajouter un œuf';
    eggForm.reset();
    modal.style.display = 'block';
});

// Gérer les actions sur les œufs existants
document.querySelectorAll('.egg-card').forEach(card => {
    const eggName = card.dataset.eggName;
    
    // Modifier
    card.querySelector('.edit-egg').addEventListener('click', async () => {
        const response = await fetch(`/api/eggs/${encodeURIComponent(eggName)}`);
        const data = await response.json();
        
        if (data.success) {
            currentEgg = data.data;
            modalTitle.textContent = 'Modifier un œuf';
            
            // Remplir le formulaire
            document.getElementById('eggName').value = currentEgg.name;
            document.getElementById('cookingTime').value = currentEgg.cookingTime;
            document.getElementById('instructions').value = currentEgg.instructions.join('\n');
            document.getElementById('tips').value = currentEgg.tips.join('\n');
            
            modal.style.display = 'block';
        }
    });
    
    // Supprimer
    card.querySelector('.delete-egg').addEventListener('click', async () => {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet œuf ?')) {
            const response = await fetch(`/admin/eggs/${encodeURIComponent(eggName)}`, {
                method: 'DELETE'
            });
            
            const data = await response.json();
            if (data.success) {
                card.remove();
            }
        }
    });
});

// Gérer la soumission du formulaire
eggForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('eggName').value,
        cookingTime: parseInt(document.getElementById('cookingTime').value),
        instructions: document.getElementById('instructions').value.split('\n').filter(line => line.trim()),
        tips: document.getElementById('tips').value.split('\n').filter(line => line.trim())
    };
    
    const url = currentEgg 
        ? `/admin/eggs/${encodeURIComponent(currentEgg.name)}`
        : '/admin/eggs';
        
    const method = currentEgg ? 'PUT' : 'POST';
    
    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    });
    
    const data = await response.json();
    if (data.success) {
        window.location.reload();
    }
});

// Fermer le modal
function closeModal() {
    modal.style.display = 'none';
}

// Fermer le modal si on clique en dehors
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});
