<?php

require_once 'Database.php';

class ImagesLogementDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getImagesByLogementId($logementId) {
        $sql = "SELECT * FROM images_logement WHERE ID_Logement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $logementId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function createImageLogement($logementId, $imageId) {
        $sql = "INSERT INTO images_logement (ID_Logement, ID_Image) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $logementId, $imageId);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteImageLogement($logementId, $imageId) {
        $sql = "DELETE FROM images_logement WHERE ID_Logement = ? AND ID_Image = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $logementId, $imageId);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}