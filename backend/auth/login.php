<?php
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', 'localhost');
session_name('PHPSESSID');
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

$email        = trim($data['email'] ?? '');
$mot_de_passe = $data['mot_de_passe'] ?? '';

// Validation
if (empty($email) || empty($mot_de_passe)) {
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    exit;
}

// Chercher l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
$stmt->execute([':email' => $email]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur || !password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
    echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect.']);
    exit;
}

// Créer la session
$_SESSION['utilisateur_id']  = $utilisateur['id'];
$_SESSION['utilisateur_nom'] = $utilisateur['nom'];

echo json_encode([
    'success' => true,
    'message' => 'Connexion réussie !',
    'utilisateur' => [
        'id'  => $utilisateur['id'],
        'nom' => $utilisateur['nom']
    ]
]);
?>
