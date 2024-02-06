<?php

require_once 'Database.php';

class TagDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllTags() {
        $sql = "SELECT * FROM Tag";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getTagById($id) {
        $sql = "SELECT * FROM Tag WHERE ID_Tag = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createTag($label) {
        $sql = "INSERT INTO Tag (Label) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $label);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateTag($id, $label) {
        $sql = "UPDATE Tag SET Label = ? WHERE ID_Tag = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $label, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteTag($id) {
        $sql = "DELETE FROM Tag WHERE ID_Tag = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}