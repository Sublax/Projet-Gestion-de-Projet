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

        // Obtener las columnas de la tabla especificada
        $columnsQuery = $bdd->query("SHOW COLUMNS FROM $tableName");
        $columns = $columnsQuery->fetchAll(PDO::FETCH_COLUMN);

        // Detectar la columna del identificador del país
        $countryIdColumn = null;
        foreach (['id_pays', 'id_country'] as $possibleColumn) {
            if (in_array($possibleColumn, $columns)) {
                $countryIdColumn = $possibleColumn;
                break;
            }
        }

        if (!$countryIdColumn) {
            throw new Exception("No se encontró una columna de identificación del país en la tabla $tableName.");
        }

        // Detectar la columna del año
        $yearColumn = null;
        foreach (['annee', 'Year'] as $possibleYearColumn) {
            if (in_array($possibleYearColumn, $columns)) {
                $yearColumn = $possibleYearColumn;
                break;
            }
        }

        if (!$yearColumn) {
            throw new Exception("No se encontró una columna de año en la tabla $tableName.");
        }

        // Obtener los datos de la tabla especificada
        $stmt = $bdd->query("SELECT * FROM $tableName");
        $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Reemplazar el identificador del país por el nombre del país
        $result = [];
        foreach ($tableData as $row) {
            if (isset($row[$countryIdColumn]) && isset($pays[$row[$countryIdColumn]])) {
                $row['nom_pays'] = $pays[$row[$countryIdColumn]]; // Agregar el nombre del país
                unset($row[$countryIdColumn]); // Opcional: eliminar la columna del identificador del país
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
