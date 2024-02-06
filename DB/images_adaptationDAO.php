<?php

require_once 'Database.php';

class ImagesAdaptationDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getImagesByAdaptationId($adaptationId) {
        $sql = "SELECT * FROM images_adaptation WHERE ID_Adaptation = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $adaptationId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function createImageAdaptation($adaptationId, $imageId) {
        $sql = "INSERT INTO images_adaptation (ID_Adaptation, ID_Image) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $adaptationId, $imageId);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteImageAdaptation($adaptationId, $imageId) {
        $sql = "DELETE FROM images_adaptation WHERE ID_Adaptation = ? AND ID_Image = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $adaptationId, $imageId);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}
