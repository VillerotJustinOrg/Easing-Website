<?php

require_once 'Database.php';

class AdaptationsDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAdaptationsByLogementId($logementId) {
        $sql = "SELECT * FROM Adaptations WHERE ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $logementId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function createAdaptation($logementId, $adaptationId) {
        $sql = "INSERT INTO Adaptations (ID_Logement, ID_Adaptation) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $logementId, $adaptationId);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteAdaptation($logementId, $adaptationId) {
        $sql = "DELETE FROM Adaptations WHERE ID_Logement = ? AND ID_Adaptation = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $logementId, $adaptationId);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}