<?php
require_once "db.php"; // Connexion à la base

// --- AJOUTER un livre ---
if (isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'];
    $maison = $_POST['maison'];
    $exemplaire = (int) $_POST['exemplaire'];

    // Récupération du nom de l'image ou utilisation de default.jpg
    $image = $_POST['image'] ?: 'default.jpg';

    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire, image) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $auteur, $description, $maison, $exemplaire, $image]);

    header("Location: livres.php");
    exit;
}

// --- SUPPRIMER un livre ---
if (isset($_GET['supprimer'])) {
    $id = (int) $_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: livres.php");
    exit;
}

// --- MODIFIER un livre ---
if (isset($_POST['modifier'])) {
    $id = (int) $_POST['id'];
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'];
    $maison = $_POST['maison'];
    $exemplaire = (int) $_POST['exemplaire'];
    $image = $_POST['image'] ?: 'default.jpg';

    $stmt = $pdo->prepare("UPDATE livres SET titre=?, auteur=?, description=?, maison_edition=?, nombre_exemplaire=?, image=? 
                           WHERE id=?");
    $stmt->execute([$titre, $auteur, $description, $maison, $exemplaire, $image, $id]);

    header("Location: livres.php");
    exit;
}

// --- LISTE DES LIVRES ---
$stmt = $pdo->query("SELECT * FROM livres ORDER BY id DESC");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "header.php"; ?>

<div class="container">
    <h1> Gestion des livres</h1>

    <!-- Formulaire d'ajout -->
    <h2>➕ Ajouter un livre</h2>
    <form method="post">
        <input type="text" name="titre" placeholder="Titre" required>
        <input type="text" name="auteur" placeholder="Auteur" required>
        <input type="text" name="maison" placeholder="Maison d'édition">
        <input type="number" name="exemplaire" placeholder="Exemplaires" required>
        <input type="text" name="image" placeholder="Nom de l'image (ex: livre1.jpg)">
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <hr>

    <!-- Liste des livres -->
    <h2> Liste des livres</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Maison</th>
            <th>Exemplaires</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($livres as $livre): ?>
        <tr>
            <td><?= $livre['id'] ?></td>
            <td>
                <img src="images/livres/<?= htmlspecialchars($livre['image']) ?>" 
                     alt="<?= htmlspecialchars($livre['titre']) ?>" style="width:50px; height:auto;">
            </td>
            <td><?= htmlspecialchars($livre['titre']) ?></td>
            <td><?= htmlspecialchars($livre['auteur']) ?></td>
            <td><?= htmlspecialchars($livre['maison_edition']) ?></td>
            <td><?= $livre['nombre_exemplaire'] ?></td>
            <td>
                <!-- Supprimer -->
                <a href="livres.php?supprimer=<?= $livre['id'] ?>" 
                   onclick="return confirm('Supprimer ce livre ?')">🗑️</a>

                <!-- Modifier (inline form) -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $livre['id'] ?>">
                    <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required>
                    <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
                    <input type="text" name="maison" value="<?= htmlspecialchars($livre['maison_edition']) ?>">
                    <input type="number" name="exemplaire" value="<?= $livre['nombre_exemplaire'] ?>" required>
                    <input type="text" name="image" value="<?= htmlspecialchars($livre['image']) ?>">
                    <textarea name="description"><?= htmlspecialchars($livre['description']) ?></textarea>
                    <button type="submit" name="modifier">💾 Enregistrer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="index.php">⬅️ Retour à l’accueil</a></p>
</div>

<?php include "footer.php"; ?>
