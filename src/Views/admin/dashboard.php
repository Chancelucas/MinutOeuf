<div class="admin-dashboard">
    <header class="dashboard-header">
        <h1>Tableau de bord</h1>
        <button id="addEgg" class="btn btn-primary">Ajouter un œuf</button>
    </header>

    <div class="dashboard-stats">
        <div class="card">
            <h3>Total des œufs</h3>
            <div class="stat-number"><?= $totalEggs ?></div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Gestion des œufs</h2>
        <div class="table-responsive">
            <table class="table">
                <!-- Le contenu du tableau sera injecté par JavaScript -->
            </table>
        </div>
    </div>

    <!-- Modal pour ajouter/modifier un œuf -->
    <div id="eggModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Gérer un œuf</h2>
            <form id="eggForm">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="minutes">Temps de cuisson (minutes)</label>
                    <input type="number" id="minutes" name="minutes" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea id="instructions" name="instructions" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image (URL)</label>
                    <input type="url" id="image" name="image" class="form-control">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary modal-close">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editEgg(egg) {
    // Remplir le formulaire avec les données de l'œuf
    document.getElementById('name').value = egg.name;
    document.getElementById('minutes').value = egg.time;
    document.getElementById('instructions').value = egg.instructions;
    document.getElementById('image').value = egg.image;
    
    // Afficher le modal
    document.getElementById('eggModal').style.display = 'block';
}

async function deleteEgg(eggName) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet œuf ?')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/eggs/${encodeURIComponent(eggName)}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Erreur lors de la suppression');
        }
    } catch (error) {
        alert('Erreur lors de la suppression');
    }
}
</script>
