<?php

require_once 'Database.php';

class CategoryDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllCategories() {
        $sql = "SELECT * FROM Category";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getCategoryById($id) {
        $sql = "SELECT * FROM Category WHERE ID_Category = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createCategory($label) {
        $sql = "INSERT INTO Category (Label) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $label);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateCategory($id, $label) {
        $sql = "UPDATE Category SET Label = ? WHERE ID_Category = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $label, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteCategory($id) {
        $sql = "DELETE FROM Category WHERE ID_Category = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}
