<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

if (isset($_SESSION['utilisateur_id']) && isset($_SESSION['utilisateur_nom'])) {
    echo json_encode([
        'connecte' => true,
        'id' => $_SESSION['utilisateur_id'],
        'nom' => $_SESSION['utilisateur_nom'],
        'email' => $_SESSION['utilisateur_email'] ?? ''
    ]);
} else {
    echo json_encode([
        'connecte' => false
    ]);
}
?>