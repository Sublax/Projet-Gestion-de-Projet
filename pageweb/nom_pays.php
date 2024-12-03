<?php
// Inclure la connexion à la base de données
require_once 'bd.php';

function getTableData($tableName) {
    try {
        // Connexion à la base de données
        $bdd = getBD();

        // Obtenir les données de la table `pays` (id_pays et nom_pays)
        $queryPays = $bdd->query("SELECT id_pays, nom_pays FROM pays");
        $pays = $queryPays->fetchAll(PDO::FETCH_KEY_PAIR); // Associer id_pays avec nom_pays

        // Vérifier si la table existe
        $query = $bdd->query("SHOW TABLES LIKE '$tableName'");
        if ($query->rowCount() == 0) {
            throw new Exception("La table spécifiée n'existe pas.");
        }

        // Obtenir les colonnes de la table spécifiée
        $columnsQuery = $bdd->query("SHOW COLUMNS FROM $tableName");
        $columns = $columnsQuery->fetchAll(PDO::FETCH_COLUMN);

        // Détecter la colonne identifiant le pays
        $countryIdColumn = null;
        foreach (['id_pays', 'id_country'] as $possibleColumn) {
            if (in_array($possibleColumn, $columns)) {
                $countryIdColumn = $possibleColumn;
                break;
            }
        }

        if (!$countryIdColumn) {
            throw new Exception("Aucune colonne d'identification du pays n'a été trouvée dans la table $tableName.");
        }

        // Détecter la colonne de l'année
        $yearColumn = null;
        foreach (['annee', 'Year'] as $possibleYearColumn) {
            if (in_array($possibleYearColumn, $columns)) {
                $yearColumn = $possibleYearColumn;
                break;
            }
        }

        if (!$yearColumn) {
            throw new Exception("Aucune colonne d'année n'a été trouvée dans la table $tableName.");
        }

        // Obtenir les données de la table spécifiée
        $stmt = $bdd->query("SELECT * FROM $tableName");
        $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Remplacer l'identifiant du pays par le nom du pays
        $result = [];
        foreach ($tableData as $row) {
            if (isset($row[$countryIdColumn]) && isset($pays[$row[$countryIdColumn]])) {
                $row['nom_pays'] = $pays[$row[$countryIdColumn]]; // Ajouter le nom du pays
                unset($row[$countryIdColumn]); // Optionnel : supprimer la colonne identifiant le pays
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

// Vérifier si le script a été directement accédé
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    // Si le script a été accédé directement depuis le navigateur
    if (isset($_GET['table'])) {
        header('Content-Type: application/json');
        echo json_encode(getTableData($_GET['table']));
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Aucune table spécifiée."]);
    }
}
