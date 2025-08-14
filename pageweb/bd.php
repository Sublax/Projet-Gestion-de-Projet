<?php
function getBD(): PDO {
  // Prefer a single DATABASE_URL if present (e.g., mysql://user:pass@host:port/db)
  if ($url = getenv('DATABASE_URL')) {
    $p = parse_url($url);
    $host = $p['host'] ?? '127.0.0.1';
    $port = $p['port'] ?? 3306;
    $user = $p['user'] ?? '';
    $pass = $p['pass'] ?? '';
    $db   = ltrim($p['path'] ?? '', '/');
  } else {
    // Fallback to discrete Railway vars (MySQL service exposes these)
    $host = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: '3306';
    $user = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: 'root';
    $pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS') ?: 'root';
    $db   = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: 'bdprojet';
  }

  $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  return new PDO($dsn, $user, $pass, $options);
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
