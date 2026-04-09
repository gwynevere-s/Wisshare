<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

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

// ── Paramètres de filtre (GET) ──────────────────────────
$search    = trim($_GET['search']    ?? '');
$type      = trim($_GET['type']      ?? 'all');
$categorie = trim($_GET['categorie'] ?? 'all');

// ── Construction de la requête dynamique ────────────────
$conditions = [];
$params     = [];

if ($search !== '') {
    $conditions[] = "(p.titre LIKE :search OR p.description LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

// Note: La table projets n'a pas les colonnes categorie et type_fichier
// Pour l'instant, on ignore ces filtres ou on les ajoute plus tard
if ($type !== 'all' && $type !== '') {
    // Si vous ajoutez la colonne type_fichier plus tard
    // $conditions[] = "p.type_fichier = :type";
    // $params[':type'] = $type;
}

if ($categorie !== 'all' && $categorie !== '') {
    // Si vous ajoutez la colonne categorie plus tard
    // $conditions[] = "p.categorie = :categorie";
    // $params[':categorie'] = $categorie;
}

$where = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

// ── Requête principale avec JOIN utilisateurs ───────────
$sql = "
    SELECT 
        p.id,
        p.titre,
        p.description,
        p.fichier,
        p.date_depot,
        u.nom AS auteur,
        u.email,
        u.date_inscription
    FROM projets p
    LEFT JOIN utilisateurs u ON p.utilisateur_id = u.id
    $where
    ORDER BY p.date_depot DESC
    LIMIT 50
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formater les dates et ajouter des informations par défaut
    foreach ($projets as &$p) {
        $p['date'] = date('Y-m-d', strtotime($p['date_depot']));
        // Valeurs par défaut pour le frontend
        $p['categorie'] = 'web'; // Valeur par défaut
        $p['type_fichier'] = getFileExtension($p['fichier']);
        $p['auteur'] = $p['auteur'] ?? 'Anonyme';
    }
    unset($p);

    echo json_encode([
        'success' => true,
        'projets' => $projets,
        'total'   => count($projets)
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur SQL: ' . $e->getMessage()
    ]);
}

// Fonction pour extraire l'extension du fichier
function getFileExtension($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ['pdf', 'zip']) ? $ext : 'pdf';
}
?>