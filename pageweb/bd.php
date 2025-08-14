<?php 
function getBD(): PDO {
  $url = getenv('DATABASE_URL');
  if (!$url) {
    throw new RuntimeException('DATABASE_URL not set');
  }
  $p = parse_url($url);
  if (!isset($p['host'], $p['user'], $p['pass'], $p['path'])) {
    throw new RuntimeException('Invalid DATABASE_URL');
  }

  $host = $p['host'];
  $port = $p['port'] ?? 3306;
  $user = $p['user'];
  $pass = $p['pass'];
  $db   = ltrim($p['path'], '/');

  $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
  return new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]); 
}

function getData($bdd, $table){
        if ($table == 'economie'){
                $stmt = $bdd->prepare("SELECT * 
                                FROM $table, pays
                                WHERE $table.id_country = pays.id_pays");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }elseif ($table == 'pays'){
                $stmt = $bdd->prepare("SELECT * 
                                FROM $table");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
                $stmt = $bdd->prepare("SELECT * 
                                FROM $table, pays
                                WHERE $table.id_pays = pays.id_pays");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
?>
