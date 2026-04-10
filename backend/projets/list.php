<?php
ini_set('session.cookie_path', '/');
session_start();
header('Content-Type: application/json');

$host = 'localhost'; $db = 'wisshare';
$user = 'root';      $pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo json_encode(['success'=>false,'message'=>'Erreur BDD: '.$e->getMessage()]);
    exit;
}

$search    = trim($_GET['search']    ?? '');
$type      = trim($_GET['type']      ?? 'all');
$categorie = trim($_GET['categorie'] ?? 'all');

$conditions = [];
$params     = [];

if ($search !== '') {
    $conditions[] = "(p.titre LIKE :search OR p.description LIKE :search)";
    $params[':search'] = '%'.$search.'%';
}
if ($type !== 'all' && in_array($type, ['pdf','zip'])) {
    $conditions[] = "p.type_fichier = :type";
    $params[':type'] = $type;
}
if ($categorie !== 'all' && in_array($categorie, ['web','ia','mobile','design','data'])) {
    $conditions[] = "p.categorie = :categorie";
    $params[':categorie'] = $categorie;
}

$where = count($conditions) ? 'WHERE '.implode(' AND ', $conditions) : '';

try {
    $stmt = $pdo->prepare("
        SELECT p.id, p.titre, p.description, p.fichier,
               p.categorie, p.type_fichier,
               DATE_FORMAT(p.date_depot, '%Y-%m-%d') AS date_depot,
               u.nom AS auteur
        FROM projets p
        JOIN utilisateurs u ON p.utilisateur_id = u.id
        $where
        ORDER BY p.date_depot DESC
        LIMIT 100
    ");
    $stmt->execute($params);
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success'=>true, 'projets'=>$projets]);
} catch (PDOException $e) {
    echo json_encode(['success'=>false,'message'=>'Erreur requête: '.$e->getMessage()]);
}
?>