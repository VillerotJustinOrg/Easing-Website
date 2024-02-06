<?php

require_once 'Database.php';

class EquipementsDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getEquipementsByLogementId($logementId) {
        $sql = "SELECT * FROM Equipements WHERE ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $logementId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function createEquipement($logementId, $equipementId) {
        $sql = "INSERT INTO Equipements (ID_Logement, ID_Equipement) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $logementId, $equipementId);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteEquipement($logementId, $equipementId) {
        $sql = "DELETE FROM Equipements WHERE ID_Logement = ? AND ID_Equipement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $logementId, $equipementId);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}