<?php
session_start();
header('Content-Type: application/json');

// Connexion BDD
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
    echo json_encode(['success' => false, 'message' => 'Erreur BDD.']);
    exit;
}

// Lire les données JSON
$data = json_decode(file_get_contents('php://input'), true);

$nom          = trim($data['nom'] ?? '');
$email        = trim($data['email'] ?? '');
$mot_de_passe = $data['mot_de_passe'] ?? '';

// Validation
if (empty($nom) || empty($email) || empty($mot_de_passe)) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email invalide.']);
    exit;
}

if (strlen($mot_de_passe) < 6) {
    echo json_encode(['success' => false, 'message' => 'Mot de passe trop court (min 6 caractères).']);
    exit;
}

// Vérifier si l'email existe déjà
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
$stmt->execute([':email' => $email]);

if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
    exit;
}

// Hasher le mot de passe et insérer
$hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (nom, email, mot_de_passe)
        VALUES (:nom, :email, :mot_de_passe)
    ");
    $stmt->execute([
        ':nom'          => $nom,
        ':email'        => $email,
        ':mot_de_passe' => $hash
    ]);

    echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription.']);
}
?>
