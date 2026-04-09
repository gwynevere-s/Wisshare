<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

// Pour les tests - À SUPPRIMER EN PRODUCTION
if (!isset($_SESSION['utilisateur_id'])) {
    // Créer un utilisateur de test si la table est vide
    $_SESSION['utilisateur_id'] = 1;
    $_SESSION['utilisateur_nom'] = 'Test User';
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Vous devez être connecté pour déposer un projet.'
    ]);
    exit;
}

// ── Connexion BDD ───────────────────────────────────────
$host    = 'localhost';
$db      = 'wisshare';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=$charset",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur BDD: ' . $e->getMessage()]);
    exit;
}

// Vérifier la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// Récupérer et valider les champs
$titre       = trim($_POST['titre'] ?? '');
$description = trim($_POST['description'] ?? '');
$categorie   = trim($_POST['categorie'] ?? 'web');

if (empty($titre) || empty($description)) {
    echo json_encode([
        'success' => false,
        'message' => 'Le titre et la description sont obligatoires.'
    ]);
    exit;
}

// Vérifier le fichier
if (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => 'Aucun fichier reçu ou erreur lors de l\'upload.'
    ]);
    exit;
}

$fichier = $_FILES['fichier'];
$ext = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
$allowedExt = ['pdf', 'zip'];

if (!in_array($ext, $allowedExt)) {
    echo json_encode([
        'success' => false,
        'message' => 'Format non autorisé. Utilisez PDF ou ZIP.'
    ]);
    exit;
}

$maxSize = 10 * 1024 * 1024; // 10 Mo
if ($fichier['size'] > $maxSize) {
    echo json_encode([
        'success' => false,
        'message' => 'Fichier trop volumineux (max 10 Mo).'
    ]);
    exit;
}

// Sauvegarder le fichier
$uploadDir = __DIR__ . '/../uploads/projets/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$newFileName = uniqid('projet_', true) . '.' . $ext;
$destination = $uploadDir . $newFileName;

if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
    echo json_encode([
        'success' => false,
        'message' => 'Impossible de sauvegarder le fichier.'
    ]);
    exit;
}

// Insérer en base de données
try {
    $stmt = $pdo->prepare("
        INSERT INTO projets (titre, description, fichier, categorie, type_fichier, utilisateur_id, date_depot)
        VALUES (:titre, :description, :fichier, :categorie, :type_fichier, :utilisateur_id, NOW())
    ");

    $stmt->execute([
        ':titre'          => $titre,
        ':description'    => $description,
        ':fichier'        => $newFileName,
        ':categorie'      => $categorie,
        ':type_fichier'   => $ext,
        ':utilisateur_id' => $_SESSION['utilisateur_id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Projet déposé avec succès !',
        'projet_id' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    // Supprimer le fichier si l'insertion échoue
    if (file_exists($destination)) {
        unlink($destination);
    }
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
    ]);
}
?>