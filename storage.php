<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

require __DIR__.'/config.php';

function table_init() {
  $sql = "CREATE TABLE IF NOT EXISTS kv_store (
    k VARCHAR(128) PRIMARY KEY,
    v MEDIUMTEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
  pdo_conn()->exec($sql);
}

$action = $_GET['a'] ?? $_POST['a'] ?? 'get';
$key    = $_GET['key'] ?? $_POST['key'] ?? 'KN_SHARED_V1';

try {
  table_init();

  if ($action === 'set') {
    $raw = file_get_contents('php://input');
    if (isset($_POST['v'])) { $raw = $_POST['v']; }
    $stmt = pdo_conn()->prepare("REPLACE INTO kv_store (k,v) VALUES (?,?)");
    $stmt->execute([$key, $raw]);
    echo json_encode(['ok'=>true]); exit;
  }

  $stmt = pdo_conn()->prepare("SELECT v FROM kv_store WHERE k=?");
  $stmt->execute([$key]);
  $row = $stmt->fetch();
  echo json_encode(['ok'=>true,'v'=>$row['v'] ?? null]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
