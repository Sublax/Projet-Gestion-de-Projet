<?php
// Incluir la conexión a la base de datos
require_once 'bd.php';

function getTableData($tableName) {
    try {
        // Conexión a la base de datos
        $bdd = getBD();

        // Obtener los datos de la tabla `pays` (id_pays y nom_pays)
        $queryPays = $bdd->query("SELECT id_pays, nom_pays FROM pays");
        $pays = $queryPays->fetchAll(PDO::FETCH_KEY_PAIR); // Asociar id_pays con nom_pays

        // Verificar que la tabla existe
        $query = $bdd->query("SHOW TABLES LIKE '$tableName'");
        if ($query->rowCount() == 0) {
            throw new Exception("La tabla especificada no existe.");
        }

        // Obtener los datos de la tabla especificada
        $stmt = $bdd->query("SELECT * FROM $tableName");
        $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Reemplazar id_pays por nom_pays en los datos
        $result = [];
        foreach ($tableData as $row) {
            if (isset($row['id_pays']) && isset($pays[$row['id_pays']])) {
                $row['nom_pays'] = $pays[$row['id_pays']]; // Agregar el nombre del país
                unset($row['id_pays']); // Opcional: eliminar id_pays si ya no es necesario
            }
            $result[] = $row;
        }

        return $result;

    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    } catch (Exception $e) {
        return ["error" => $e->getMessage()];
    }
}

// Verificar si el script fue accedido directamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    // Si fue accedido directamente desde el navegador
    if (isset($_GET['table'])) {
        header('Content-Type: application/json');
        echo json_encode(getTableData($_GET['table']));
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "No se especificó una tabla."]);
    }
}
