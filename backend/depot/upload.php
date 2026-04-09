<?php
// On définit le dossier de destination (remonte de depot et backend)
$target_dir = "../../uploads/";

// Si le dossier n'existe pas, on le crée
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
        $fileName = basename($_FILES["fichier"]["name"]);
        // On sécurise le nom du fichier (supprime les espaces)
        $fileName = str_replace(' ', '_', $fileName);
        $finalPath = $target_dir . time() . "_" . $fileName;
        
        $fileType = strtolower(pathinfo($finalPath, PATHINFO_EXTENSION));
        $allowed = array('pdf', 'zip');

        if (in_array($fileType, $allowed)) {
            if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $finalPath)) {
                echo "<script>
                        alert('Succès ! Le projet \"$titre\" est en ligne.');
                        window.location.href='../../frontend/depot/depot.html';
                      </script>";
            } else {
                echo "Erreur lors de l'enregistrement du fichier.";
            }
        } else {
            echo "Format non autorisé (Uniquement PDF et ZIP).";
        }
    } else {
        echo "Veuillez sélectionner un fichier valide.";
    }
}
?>