<?php

require_once 'Database.php';


class AppartiendDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAppartiendByAdaptationId($adaptationId) {
        $sql = "SELECT * FROM appartiend WHERE ID_Adaptation = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $adaptationId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getAppartiendByTagId($tagId) {
        $sql = "SELECT * FROM appartiend WHERE ID_Tag = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $tagId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function createAppartiend($adaptationId, $tagId) {
        $sql = "INSERT INTO appartiend (ID_Adaptation, ID_Tag) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $adaptationId, $tagId);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteAppartiend($adaptationId, $tagId) {
        $sql = "DELETE FROM appartiend WHERE ID_Adaptation = ? AND ID_Tag = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $adaptationId, $tagId);
        return $stmt->execute();

    }

    // Add other methods as needed
}