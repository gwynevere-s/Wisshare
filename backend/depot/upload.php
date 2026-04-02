<?php
// Vérifie si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Données reçues :</h2>";
    echo "Titre : " . $_POST['titre'] . "<br>";
    echo "Description : " . $_POST['description'] . "<br>";

    if (isset($_FILES['fichier'])) {
        echo "Nom du fichier : " . $_FILES['fichier']['name'] . "<br>";
        echo "Type : " . $_FILES['fichier']['type'] . "<br>";
        echo "Taille : " . $_FILES['fichier']['size'] . " octets<br>";
    } else {
        echo "Aucun fichier reçu.";
    }
} else {
    echo "Formulaire non soumis.";
}
?>