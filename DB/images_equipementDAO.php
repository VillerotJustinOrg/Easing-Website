<?php

require_once 'Database.php';

class ImagesEquipementDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getImagesByEquipementId($equipementId) {
        $sql = "SELECT * FROM images_equipement WHERE ID_Equipement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $equipementId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function createImageEquipement($imageId, $equipementId) {
        $sql = "INSERT INTO images_equipement (ID_Image, ID_Equipement) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $imageId, $equipementId);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteImageEquipement($imageId, $equipementId) {
        $sql = "DELETE FROM images_equipement WHERE ID_Image = ? AND ID_Equipement = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $imageId, $equipementId);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}