<?php

require_once 'Database.php';

class LogementDAO {
    private $conn;

    // Constructor
    public function __construct(Database $db) {
        $this->conn = $db->getConnection();
    }

    // Example method to fetch data
    function getAllLogements(): array {
        $sql = "SELECT * FROM Logement";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getLogementById($id): array
    {
        $sql = "SELECT * FROM Logement WHERE ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createLogement($nom, $adresse, $latitude, $longitude, $description, $reglement, $frais, $max, $arriveeMin, $arriveeMax, $depart, $prix, $proprioId, $categoryId, $typeId): bool
    {
        $sql = "INSERT INTO Logement (Nom, Adresse, Lattitude, Longitude, Description, Reglement_interieur, Frais_additionnels, Nombre_Max, Arriver_MIN, Arriver_MAX, Depart, Prix, ID_Proprietaire, ID_Category, ID_Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssddssssssssiii", $nom, $adresse, $latitude, $longitude, $description, $reglement, $frais, $max, $arriveeMin, $arriveeMax, $depart, $prix, $proprioId, $categoryId, $typeId);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateLogement($id, $nom, $adresse, $latitude, $longitude, $description, $reglement, $frais, $max, $arriveeMin, $arriveeMax, $depart, $prix, $proprioId, $categoryId, $typeId): bool
    {
        $sql = "UPDATE Logement SET Nom = ?, Adresse = ?, Lattitude = ?, Longitude = ?, Description = ?, Reglement_interieur = ?, Frais_additionnels = ?, Nombre_Max = ?, Arriver_MIN = ?, Arriver_MAX = ?, Depart = ?, Prix = ?, ID_Proprietaire = ?, ID_Category = ?, ID_Type = ? WHERE ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssddssssssssii", $nom, $adresse, $latitude, $longitude, $description, $reglement, $frais, $max, $arriveeMin, $arriveeMax, $depart, $prix, $proprioId, $categoryId, $typeId, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteLogement($id): bool
    {
        $sql = "DELETE FROM Logement WHERE ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function getAllLogementsComplete(): array
    {
        $sql = "SELECT l.*, p.Nom AS Proprietaire_Nom, p.Prenom AS Proprietaire_Prenom, c.Label AS Category_Label, t.Label AS Type_Label 
            FROM Logement l 
            INNER JOIN Proprietaire p ON l.ID_Proprietaire = p.ID_Proprietaire 
            INNER JOIN Category c ON l.ID_Category = c.ID_Category 
            INNER JOIN Type t ON l.ID_Type = t.ID_Type";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getLogementCompleteByID($id): array
    {
        $sql = "SELECT l.*, p.Nom AS Proprietaire_Nom, p.Prenom AS Proprietaire_Prenom, c.Label AS Category_Label, t.Label AS Type_Label 
            FROM Logement l
            INNER JOIN Proprietaire p ON l.ID_Proprietaire = p.ID_Proprietaire 
            INNER JOIN Category c ON l.ID_Category = c.ID_Category 
            INNER JOIN Type t ON l.ID_Type = t.ID_Type
            WHERE l.ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    function nextDisponibility($logementId)
    {
        $currentDate = date("Y-m-d");

        // Get the end date of the latest booking for the given logement
        $sql = "SELECT MAX(Fin) AS LatestEnd FROM Location WHERE ID_Logement = ? AND Fin >= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $logementId, $currentDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // If there are no bookings after the current date, return the current date
        if ($row['LatestEnd'] === null) {
            return $currentDate;
        }

        // Otherwise, add one day to the end date of the latest booking
        $latestEnd = date("Y-m-d", strtotime($row['LatestEnd'] . "+1 day"));
        return $latestEnd;
    }

    function SimpleFilter($destination, $debut, $fin, $nbrPlace, $tags): array
    {
        $sql="SELECT
                    l.*,
                    p.Nom AS Proprietaire_Nom,
                    p.Prenom AS Proprietaire_Prenom,
                    c.Label AS Category_Label,
                    t.Label AS Type_Label 
            FROM Logement l
                INNER JOIN Proprietaire p ON l.ID_Proprietaire = p.ID_Proprietaire
                INNER JOIN Category c ON l.ID_Category = c.ID_Category
                INNER JOIN Type t ON l.ID_Type = t.ID_Type
                WHERE l.ID_Logement IN (
                SELECT DISTINCT l.ID_Logement
                FROM Logement l 
                INNER JOIN Proprietaire p ON l.ID_Proprietaire = p.ID_Proprietaire 
                INNER JOIN Category c ON l.ID_Category = c.ID_Category 
                INNER JOIN Type t ON l.ID_Type = t.ID_Type 
                LEFT JOIN Location loc ON l.ID_Logement = loc.ID_Logement 
                                          AND loc.Debut <= ? AND loc.Fin >= ? 
                LEFT JOIN Adaptations a ON l.ID_Logement = a.ID_Logement 
                LEFT JOIN appartiend ap ON a.ID_Adaptation = ap.ID_Adaptation
                WHERE 
                    loc.ID_Logement IS NULL AND
                    l.Adresse LIKE ? AND
                    l.Nombre_Max >= ?";

        $destination = "%".$destination."%";
        $list = [$debut, $fin, $destination , intval($nbrPlace)];


        if ($tags == null){
            echo "HAHA";
            $sql.=");";
            $types = "sssi";
        } else {
            echo "tags<br>";
            $sql.=" AND ap.ID_Tag IN (";
            for ($i = 1; $i <= count($tags); $i++){
                $sql .= "?";
                if ($i < count($tags)){
                    $sql .= ", ";
                }
            }
            $sql.="));";
            foreach ($tags as $tag) {$list[] = intval($tag);}
            $types = "sssi".str_repeat('s', count($tags));
            echo "tags done<br>";
        }


        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param($types,...$list);

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all();
    }



    function Filter($destination, $debut, $fin, $nbrPlace, $tags, $visite, $type, $category, $price_min, $price_max): array
    {
        $sql="SELECT
                    l.*,
                    p.Nom AS Proprietaire_Nom,
                    p.Prenom AS Proprietaire_Prenom,
                    c.Label AS Category_Label,
                    t.Label AS Type_Label 
            FROM Logement l
                INNER JOIN Proprietaire p ON l.ID_Proprietaire = p.ID_Proprietaire
                INNER JOIN Category c ON l.ID_Category = c.ID_Category
                INNER JOIN Type t ON l.ID_Type = t.ID_Type
                WHERE l.ID_Logement IN (
                SELECT DISTINCT l.ID_Logement
                FROM Logement l 
                INNER JOIN Proprietaire p ON l.ID_Proprietaire = p.ID_Proprietaire 
                INNER JOIN Category c ON l.ID_Category = c.ID_Category 
                INNER JOIN Type t ON l.ID_Type = t.ID_Type 
                LEFT JOIN Location loc ON l.ID_Logement = loc.ID_Logement 
                                          AND loc.Debut <= ? AND loc.Fin >= ? 
                LEFT JOIN Adaptations a ON l.ID_Logement = a.ID_Logement 
                LEFT JOIN appartiend ap ON a.ID_Adaptation = ap.ID_Adaptation
                LEFT JOIN Visite v ON l.ID_Logement = v.ID_Logement
                WHERE 
                    loc.ID_Logement IS NULL AND
                    l.Adresse LIKE ? AND
                    l.Nombre_Max >= ?";

        $destination = "%".$destination."%";
        $list = [$debut, $fin, $destination , intval($nbrPlace)];
        $types = "sssi";

        if ($tags != null) {
            $sql .= " AND ap.ID_Tag IN (";
            for ($i = 1; $i <= count($tags); $i++) {
                $sql .= "?";
                if ($i < count($tags)) {
                    $sql .= ", ";
                }
            }
            $sql .= ")";
            foreach ($tags as $tag) {
                $list[] = intval($tag);
            }
            $types = "sssi" . str_repeat('s', count($tags));
            echo "tags done<br>";
        }

        if ($visite == "on"){
            $sql.= " AND v.ID_Visite IS NOT NULL ";
        }

        if ($type != null){
            $sql.= " AND t.ID_Type = ? ";
            $list[] = $type;
            $types.= "i";
        }

        if ($category != null){
            $sql.= " AND c.ID_Category = ? ";
            $list[] = $category;
            $types.= "i";
        }

        if ($price_min != null and $price_max != null){
            $sql.= " AND (l.Prix BETWEEN ? AND ?)";
            $list[] = $price_min;
            $list[] = $price_max;
            $types.= "ii";
        }

        $sql.=");";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param($types,...$list);

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all();
    }
}
