<?php

require_once 'Database.php';

class ImageDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllImages() {
        $sql = "SELECT * FROM Image";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getImageById($id) {
        $sql = "SELECT * FROM Image WHERE ID_Image = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createImage($label) {
        $sql = "INSERT INTO Image (Label) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $label);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateImage($id, $label) {
        $sql = "UPDATE Image SET Label = ? WHERE ID_Image = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $label, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteImage($id) {
        $sql = "DELETE FROM Image WHERE ID_Image = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}