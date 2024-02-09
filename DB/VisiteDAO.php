<?php

require_once 'Database.php';

class VisiteDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function createVisite($label, $logementId) {
        $sql = "INSERT INTO Visite (Label, ID_Logement) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $label, $logementId);
        return $stmt->execute();
    }

    function getVisiteById($visiteId) {
        $sql = "SELECT * FROM Visite WHERE ID_Visite = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $visiteId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    function getVisiteByLogement($ID_Logement) {
        $sql = "SELECT * FROM Visite WHERE ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ID_Logement);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    function deleteVisite($visiteId) {
        $sql = "DELETE FROM Visite WHERE ID_Visite = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $visiteId);
        return $stmt->execute();
    }

    // Add other methods as needed
}