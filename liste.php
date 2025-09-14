<?php
require_once "db.php";
$pageTitle = "Liste de lecture";

if (isset($_POST['ajouter'])) {
    $id_lecteur = (int) $_POST['id_lecteur'];
    $id_livre   = (int) $_POST['id_livre'];
    $date_emprunt = date("Y-m-d");

    $stmt = $pdo->prepare("INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (?, ?, ?)");
    $stmt->execute([$id_livre, $id_lecteur, $date_emprunt]);

    header("Location: liste.php?id_lecteur=$id_lecteur");
    exit;
}
if (isset($_GET['supprimer'])) {
    $id_livre   = (int) $_GET['supprimer'];
    $id_lecteur = (int) $_GET['lecteur'];
    
    $stmt = $pdo->prepare("DELETE FROM liste_lecture WHERE id_livre=? AND id_lecteur=?");
    $stmt->execute([$id_livre, $id_lecteur]);

    header("Location: liste.php?id_lecteur=$id_lecteur");
    exit;
}

$stmt = $pdo->query("SELECT * FROM lecteurs ORDER BY nom ASC");
$lecteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

//RÉCUPÉRER les livres disponibles 
$stmt = $pdo->query("SELECT * FROM livres ORDER BY titre ASC");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
$emprunts = [];
$lecteur_actuel = null;
if (isset($_GET['id_lecteur'])) {
    $id_lecteur = (int) $_GET['id_lecteur'];
    $stmt = $pdo->prepare("SELECT l.*, ll.date_emprunt 
                           FROM liste_lecture ll 
                           JOIN livres l ON ll.id_livre = l.id
                           WHERE ll.id_lecteur = ?");
    $stmt->execute([$id_lecteur]);
    $emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Infos du lecteur
    $stmt = $pdo->prepare("SELECT * FROM lecteurs WHERE id=?");
    $stmt->execute([$id_lecteur]);
    $lecteur_actuel = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Inclure le header
include "header.php";
?>

<!-- Sélection du lecteur -->
<form method="get" action="liste.php">
    <label>Choisir un lecteur :</label>
    <select name="id_lecteur" required>
        <option value="">-- Sélectionner --</option>
        <?php foreach ($lecteurs as $lecteur): ?>
            <option value="<?= $lecteur['id'] ?>" 
                <?= (isset($lecteur_actuel) && $lecteur['id'] == $lecteur_actuel['id']) ? "selected" : "" ?>>
                <?= htmlspecialchars($lecteur['prenom'] . " " . $lecteur['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Voir</button>
</form>

<hr>

<?php if ($lecteur_actuel): ?>
    <h2>👤 Liste de lecture de <?= htmlspecialchars($lecteur_actuel['prenom'] . " " . $lecteur_actuel['nom']) ?></h2>

    <!-- Ajouter un emprunt -->
    <h3>➕ Ajouter un emprunt</h3>
    <form method="post">
        <input type="hidden" name="id_lecteur" value="<?= $lecteur_actuel['id'] ?>">
        <select name="id_livre" required>
            <option value="">-- Sélectionner un livre --</option>
            <?php foreach ($livres as $livre): ?>
                <option value="<?= $livre['id'] ?>">
                    <?= htmlspecialchars($livre['titre'] . " - " . $livre['auteur']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <!-- Liste des emprunts -->
    <h3>📚 Livres empruntés</h3>
    <?php if (count($emprunts) > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date emprunt</th>
                <th>Action</th>
            </tr>
            <?php foreach ($emprunts as $livre): ?>
            <tr>
                <td><?= htmlspecialchars($livre['titre']) ?></td>
                <td><?= htmlspecialchars($livre['auteur']) ?></td>
                <td><?= htmlspecialchars($livre['date_emprunt']) ?></td>
                <td>
                    <a href="liste.php?supprimer=<?= $livre['id'] ?>&lecteur=<?= $lecteur_actuel['id'] ?>" 
                       onclick="return confirm('Marquer comme rendu ?')">✅ Retour</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun emprunt pour ce lecteur.</p>
    <?php endif; ?>
<?php endif; ?>

<p><a href="index.php">⬅️ Retour à l’accueil</a></p>

<?php include "footer.php"; ?>
