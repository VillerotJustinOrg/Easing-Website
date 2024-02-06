<?php

require_once 'Database.php';

class AdaptationDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllAdaptations() {
        $sql = "SELECT * FROM Adaptation";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getAdaptationById($id) {
        $sql = "SELECT * FROM Adaptation WHERE ID_Adaptation = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createAdaptation($label, $description) {
        $sql = "INSERT INTO Adaptation (Label, Description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $label, $description);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateAdaptation($id, $label, $description) {
        $sql = "UPDATE Adaptation SET Label = ?, Description = ? WHERE ID_Adaptation = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $label, $description, $id);
        return $stmt->execute();

    }

    function deleteAdaptation($id) {
        $sql = "DELETE FROM Adaptation WHERE ID_Adaptation = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();

    }

    // Add other methods as needed
}
