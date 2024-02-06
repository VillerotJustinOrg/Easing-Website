<?php

require_once 'Database.php';

class LocationDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllLocations() {
        $sql = "SELECT * FROM Location";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getLocationById($id) {
        $sql = "SELECT * FROM Location WHERE ID_Location = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createLocation($debut, $fin, $id_logement) {
        $sql = "INSERT INTO Location (Debut, Fin, ID_Logement) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $debut, $fin, $id_logement);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateLocation($id, $debut, $fin, $id_logement) {
        $sql = "UPDATE Location SET Debut = ?, Fin = ?, ID_Logement = ? WHERE ID_Location = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $debut, $fin, $id_logement, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteLocation($id) {
        $sql = "DELETE FROM Location WHERE ID_Location = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}