<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Credentials: true');

$host = 'localhost';
$db = 'wisshare';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT p.*, u.nom as auteur_nom 
            FROM projets p 
            LEFT JOIN utilisateurs u ON p.utilisateur_id = u.id 
            ORDER BY p.date_depot DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les données pour le frontend
    $formatted = [];
    foreach ($projets as $projet) {
        $formatted[] = [
            'id' => $projet['id'],
            'titre' => $projet['titre'],
            'description' => $projet['description'],
            'type_fichier' => $projet['type_fichier'],
            'categorie' => $projet['categorie'],
            'date_depot' => $projet['date_depot'],
            'auteur' => $projet['auteur_nom'] ?? 'Anonyme'
        ];
    }
    
    echo json_encode(['success' => true, 'projets' => $formatted]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}
?>