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
        // TODO neo4j
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

    private function is_between($start, $end, $time)
    {
        $first_diff = date_diff($start, $time)->format('%R');
        $second_diff = date_diff($time, $end)->format('%R');
        if ($first_diff == '+' and $second_diff == '+'){
            return true;
        }
        return false;
    }

    private function is_before($start, $end, $time)
    {
        $first_diff = date_diff($start, $time)->format('%R');
        $second_diff = date_diff($time, $end)->format('%R');
        if ($first_diff == '+' and $second_diff == '-'){
            return true;
        }
        return false;
    }

    private function is_after($start, $end, $time)
    {
        $first_diff = date_diff($start, $time)->format('%R');
        $second_diff = date_diff($time, $end)->format('%R');
        if ($first_diff == '-' and $second_diff == '+'){
            return true;
        }
        return false;
    }
}
