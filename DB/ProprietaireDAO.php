<?php

require_once 'Database.php';

class ProprietaireDAO {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    function getAllProprietaires() {
        $sql = "SELECT * FROM Proprietaire";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        // TODO neo4j
    }

    function getProprietaireById($id) {
        $sql = "SELECT * FROM Proprietaire WHERE ID_Proprietaire = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        // TODO neo4j
    }

    function createProprietaire($nom, $prenom, $telephone, $mail) {
        $sql = "INSERT INTO Proprietaire (Nom, Prenom, Telephone, mail) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $nom, $prenom, $telephone, $mail);
        return $stmt->execute();
        // TODO neo4j
    }

    function updateProprietaire($id, $nom, $prenom, $telephone, $mail) {
        $sql = "UPDATE Proprietaire SET Nom = ?, Prenom = ?, Telephone = ?, mail = ? WHERE ID_Proprietaire = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $nom, $prenom, $telephone, $mail, $id);
        return $stmt->execute();
        // TODO neo4j
    }

    function deleteProprietaire($id) {
        $sql = "DELETE FROM Proprietaire WHERE ID_Proprietaire = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // TODO neo4j
    }

    // Add other methods as needed
}
