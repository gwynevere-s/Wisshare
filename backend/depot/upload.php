<?php
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre = $_POST['titre'];
    $description = $_POST['description'];

    if (isset($_FILES['fichier'])) {

        $fileName = $_FILES['fichier']['name'];
        $tmpName = $_FILES['fichier']['tmp_name'];

        $uploadPath = "../../uploads/" . $fileName;

        // déplacer fichier
        move_uploaded_file($tmpName, $uploadPath);

        // enregistrer dans la base
        $sql = "INSERT INTO projects (title, description, file_path)
                VALUES ('$titre', '$description', '$uploadPath')";

        if ($conn->query($sql)) {
            echo "Upload réussi ✔";
        } else {
            echo "Erreur: " . $conn->error;
        }

    } else {
        echo "Aucun fichier reçu.";
    }

} else {
    echo "Formulaire non soumis.";
}
?>