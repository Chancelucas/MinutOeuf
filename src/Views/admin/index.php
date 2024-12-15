<?php
$title = 'Administration des œufs';
ob_start();
?>

<div class="container">
    <div class="admin-header">
        <h1>Administration des œufs</h1>
        <a href="/admin/create" class="btn btn-primary">Ajouter un œuf</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Temps de cuisson</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eggs as $egg): ?>
                <tr>
                    <td><?= htmlspecialchars($egg['type']) ?></td>
                    <td><?= htmlspecialchars($egg['cookingTime']) ?> minutes</td>
                    <td><?= htmlspecialchars($egg['description']) ?></td>
                    <td class="actions">
                        <a href="/admin/edit/<?= (string)$egg['_id'] ?>" class="btn btn-small btn-edit">Modifier</a>
                        <form action="/admin/delete/<?= (string)$egg['_id'] ?>" method="POST" class="inline-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet œuf ?');">
                            <button type="submit" class="btn btn-small btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
