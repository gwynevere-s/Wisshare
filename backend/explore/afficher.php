<?php
/**
 * WisShare — backend/explore/afficher.php
 * Retourne les projets filtrés par catégorie et/ou recherche (JSON).
 *
 * Paramètres GET acceptés :
 *   categorie  = web | ia | mobile | design | data   (optionnel)
 *   q          = texte libre (optionnel, cherche dans titre + description)
 *   sort       = date_desc | date_asc | title_asc | title_desc  (défaut : date_desc)
 *   page       = entier >= 1  (défaut : 1)
 *   per_page   = 1–50          (défaut : 12)
 */
 
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
 
// ── Connexion BDD ────────────────────────────────────
$host    = 'localhost';
$db      = 'wisshare';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';
 
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=$charset",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur BDD : ' . $e->getMessage()]);
    exit;
}
 
// ── Paramètres ───────────────────────────────────────
$allowedCats  = ['web', 'ia', 'mobile', 'design', 'data'];
$allowedSorts = ['date_desc', 'date_asc', 'title_asc', 'title_desc'];
 
$categorie = isset($_GET['categorie']) && in_array($_GET['categorie'], $allowedCats)
    ? $_GET['categorie'] : null;
 
$query    = isset($_GET['q'])  ? trim($_GET['q'])  : '';
$sort     = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSorts) ? $_GET['sort'] : 'date_desc';
$page     = max(1, intval($_GET['page']     ?? 1));
$perPage  = min(50, max(1, intval($_GET['per_page'] ?? 12)));
 
// ── Construction de la requête ───────────────────────
$conditions = [];
$params     = [];
 
if ($categorie) {
    $conditions[] = 'p.categorie = :categorie';
    $params[':categorie'] = $categorie;
}
 
if ($query !== '') {
    $conditions[] = '(p.titre LIKE :q OR p.description LIKE :q2)';
    $like = '%' . $query . '%';
    $params[':q']  = $like;
    $params[':q2'] = $like;
}
 
$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
 
// Tri
$orderBy = match($sort) {
    'date_asc'   => 'p.date_depot ASC',
    'title_asc'  => 'p.titre ASC',
    'title_desc' => 'p.titre DESC',
    default      => 'p.date_depot DESC',
};
 
// ── Compte total ─────────────────────────────────────
$countSql  = "SELECT COUNT(*) FROM projets p $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
 
// ── Projets paginés ───────────────────────────────────
$offset = ($page - 1) * $perPage;
 
$sql = "
    SELECT
        p.id,
        p.titre,
        p.description,
        p.categorie,
        p.type_fichier,
        p.fichier,
        p.date_depot,
        u.nom AS auteur
    FROM projets p
    LEFT JOIN utilisateurs u ON p.utilisateur_id = u.id
    $where
    ORDER BY $orderBy
    LIMIT :limit OFFSET :offset
";
 
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
$stmt->execute();
 
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
// ── Réponse ───────────────────────────────────────────
echo json_encode([
    'success'      => true,
    'total'        => $total,
    'page'         => $page,
    'per_page'     => $perPage,
    'total_pages'  => (int) ceil($total / $perPage),
    'projets'      => $projets,
]);