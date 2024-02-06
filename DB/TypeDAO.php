<?php

require_once 'Database.php';

class TypeDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllTypes() {
        $sql = "SELECT * FROM Type";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getTypeById($id) {
        $sql = "SELECT * FROM Type WHERE ID_Type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createType($label) {
        $sql = "INSERT INTO Type (Label) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $label);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateType($id, $label) {
        $sql = "UPDATE Type SET Label = ? WHERE ID_Type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $label, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteType($id) {
        $sql = "DELETE FROM Type WHERE ID_Type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}