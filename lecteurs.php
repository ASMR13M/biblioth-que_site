<?php
require_once "db.php";
$pageTitle = "Gestion des lecteurs";

if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("INSERT INTO lecteurs (nom, prenom, email) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email]);

    header("Location: lecteurs.php");
    exit;
}

if (isset($_GET['supprimer'])) {
    $id = (int) $_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM lecteurs WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: lecteurs.php");
    exit;
}

if (isset($_POST['modifier'])) {
    $id = (int) $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE lecteurs SET nom=?, prenom=?, email=? WHERE id=?");
    $stmt->execute([$nom, $prenom, $email, $id]);

    header("Location: lecteurs.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM lecteurs ORDER BY id DESC");
$lecteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclure le header
include "header.php";
?>

<!-- Formulaire d'ajout -->
<h2>➕ Ajouter un lecteur</h2>
<form method="post">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit" name="ajouter">Ajouter</button>
</form>

<hr>

<!-- Liste des lecteurs -->
<h2>📋 Liste des lecteurs</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($lecteurs as $lecteur): ?>
    <tr>
        <td><?= $lecteur['id'] ?></td>
        <td><?= htmlspecialchars($lecteur['nom']) ?></td>
        <td><?= htmlspecialchars($lecteur['prenom']) ?></td>
        <td><?= htmlspecialchars($lecteur['email']) ?></td>
        <td>
            <!-- Supprimer -->
            <a href="lecteurs.php?supprimer=<?= $lecteur['id'] ?>" onclick="return confirm('Supprimer ce lecteur ?')">🗑️</a>

            <!-- Modifier (inline form) -->
            <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $lecteur['id'] ?>">
                <input type="text" name="nom" value="<?= htmlspecialchars($lecteur['nom']) ?>" required>
                <input type="text" name="prenom" value="<?= htmlspecialchars($lecteur['prenom']) ?>" required>
                <input type="email" name="email" value="<?= htmlspecialchars($lecteur['email']) ?>" required>
                <button type="submit" name="modifier">💾 Enregistrer</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<p><a href="index.php">⬅️ Retour à l’accueil</a></p>

<?php include "footer.php"; ?>
