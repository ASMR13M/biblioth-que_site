<?php
// header.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- Menu responsive -->
    <nav class="navbar">
        <div class="nav-brand"> Bibliothèque</div>
        <button class="nav-toggle" onclick="toggleMenu()">☰</button>
        <ul class="nav-links" id="navMenu">
            <li><a href="index.php"> Accueil</a></li>
            <li><a href="livres.php"> Livres</a></li>
            <li><a href="lecteurs.php"> Lecteurs</a></li>
            <li><a href="liste.php"> Liste de lecture</a></li>
        </ul>
    </nav>
    <hr>
    <script>
        function toggleMenu() {
            document.getElementById("navMenu").classList.toggle("active");
        }
    </script>
