<?php
require_once "db.php"; // connexion à la base

$search = "";
$resultats = [];

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    if ($search !== "") {
        $stmt = $pdo->prepare("SELECT * FROM livres 
                               WHERE titre LIKE :search OR auteur LIKE :search");
        $stmt->execute(['search' => "%$search%"]);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

include "header.php";
?>

<div class="container">

    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="images/logo.jpg" alt="Logo Bibliothèque" style="max-width:200px;">
    </div>

    <!-- Formulaire de recherche -->
    <form method="get" action="index.php" class="text-center mb-4">
        <input type="text" name="search" placeholder="Rechercher par titre ou auteur"
               value="<?= htmlspecialchars($search) ?>" style="padding:8px; width:60%;">
        <button type="submit" style="padding:8px 16px;">Rechercher</button>
    </form>

    <hr>

    <?php if ($search !== ""): ?>
        <h2>Résultats de recherche :</h2>
        <?php if (count($resultats) > 0): ?>
            <div class="cards-container" style="display:flex; flex-wrap:wrap; gap:20px;">
                <?php foreach ($resultats as $livre): ?>
                    <div class="card" style="border:1px solid #ccc; padding:10px; width:200px; text-align:center;">
                       <img src="images/livres/<?= htmlspecialchars($livre['image']) ?>" 
     alt="<?= htmlspecialchars($livre['titre']) ?>" style="width:100%; height:auto;">

                        <h4><?= htmlspecialchars($livre['titre']) ?></h4>
                        <p><?= htmlspecialchars($livre['auteur']) ?></p>
                        <p>📖 <?= (int)$livre['nombre_exemplaire'] ?> exemplaires</p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun livre trouvé.</p>
        <?php endif; ?>
    <?php else: ?>
        <!-- Page d'accueil normale avec tes images -->
        <div class="cards-container" style="display:flex; justify-content:space-around; flex-wrap:wrap; gap:20px; text-align:center;">
            <div class="card" style="border:1px solid #ccc; padding:10px; width:250px;">
                <img src="images/biblio.jpg" alt="Bibliothèque" style="width:100%; height:auto;">
                <h3> Nos Livres</h3>
                <p>Découvrez notre collection complète de livres.</p>
                <a href="livres.php">Voir les livres</a>
            </div>

            <div class="card" style="border:1px solid #ccc; padding:10px; width:250px;">
                <img src="images/lecteurs.jpg" alt="Lecteurs" style="width:100%; height:auto;">
                <h3>👤 Nos Lecteurs</h3>
                <p>Gérez vos lecteurs et inscrivez-en de nouveaux.</p>
                <a href="lecteurs.php">Voir les lecteurs</a>
            </div>

            <div class="card" style="border:1px solid #ccc; padding:10px; width:250px;">
                <img src="images/biblio.jpg" alt="Liste de lecture" style="width:100%; height:auto;">
                <h3>Liste de lecture</h3>
                <p>Suivez les livres empruntés par chaque lecteur.</p>
                <a href="liste.php">Voir la liste</a>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include "footer.php"; ?>
