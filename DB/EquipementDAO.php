<?php

require_once 'Database.php';

class EquipementDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllEquipements() {
        $sql = "SELECT * FROM Equipement";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getEquipementById($id) {
        $sql = "SELECT * FROM Equipement WHERE ID_Equipement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createEquipement($label, $description) {
        $sql = "INSERT INTO Equipement (Label, Description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $label, $description);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateEquipement($id, $label, $description) {
        $sql = "UPDATE Equipement SET Label = ?, Description = ? WHERE ID_Equipement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $label, $description, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteEquipement($id) {
        $sql = "DELETE FROM Equipement WHERE ID_Equipement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}
