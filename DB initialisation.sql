CREATE TABLE Adaptation(
   ID_Adaptation INT AUTO_INCREMENT PRIMARY KEY,
   Label VARCHAR(50),
   Description TEXT
);

CREATE TABLE Tag(
   ID_Tag INT AUTO_INCREMENT PRIMARY KEY,
   Label VARCHAR(50)
);

CREATE TABLE Image(
   ID_Image INT AUTO_INCREMENT PRIMARY KEY,
   Label VARCHAR(50)
);

CREATE TABLE Equipement(
   ID_Equipement INT AUTO_INCREMENT PRIMARY KEY,
   Label VARCHAR(50),
   Description TEXT
);

CREATE TABLE Type(
   ID_Type INT AUTO_INCREMENT PRIMARY KEY,
   Label VARCHAR(50)
);

CREATE TABLE Category(
   ID_Category INT AUTO_INCREMENT PRIMARY KEY,
   Label VARCHAR(50)
);

CREATE TABLE Proprietaire(
   ID_Proprietaire INT AUTO_INCREMENT PRIMARY KEY,
   Nom VARCHAR(50),
   Prenom VARCHAR(50),
   Telephone VARCHAR(12),
   mail VARCHAR(50)
);

CREATE TABLE Logement(
  ID_Logement INT AUTO_INCREMENT PRIMARY KEY,
  Nom VARCHAR(50),
  Adresse VARCHAR(50),
  Lattitude DOUBLE,
  Longitude DOUBLE,
  Description TEXT,
  Reglement_interieur TEXT,
  Frais_additionnels TEXT,
  Nombre_Max INT,
  Arriver_MIN TIME,
  Arriver_MAX TIME,
  Depart TIME,
  Prix DECIMAL(15,2),
  ID_Proprietaire INT NOT NULL,
  ID_Category INT NOT NULL,
  ID_Type INT NOT NULL,
  FOREIGN KEY(ID_Proprietaire) REFERENCES Proprietaire(ID_Proprietaire),
  FOREIGN KEY(ID_Category) REFERENCES Category(ID_Category),
  FOREIGN KEY(ID_Type) REFERENCES Type(ID_Type)
);

CREATE TABLE Location(
   ID_Location INT AUTO_INCREMENT PRIMARY KEY,
   Debut DATE,
   Fin DATE,
   ID_Logement INT NOT NULL,
   FOREIGN KEY(ID_Logement) REFERENCES Logement(ID_Logement)
);

CREATE TABLE Visite(
    ID_Visite INT AUTO_INCREMENT PRIMARY KEY,
    Label VARCHAR(50),
    ID_Logement INT NOT NULL,
    UNIQUE(ID_Logement),
    FOREIGN KEY(ID_Logement) REFERENCES Logement(ID_Logement)
);

CREATE TABLE appartiend(
   ID_Adaptation INT,
   ID_Tag INT,
   PRIMARY KEY(ID_Adaptation, ID_Tag),
   FOREIGN KEY(ID_Adaptation) REFERENCES Adaptation(ID_Adaptation),
   FOREIGN KEY(ID_Tag) REFERENCES Tag(ID_Tag)
);

CREATE TABLE Adaptations(
   ID_Logement INT,
   ID_Adaptation INT,
   PRIMARY KEY(ID_Logement, ID_Adaptation),
   FOREIGN KEY(ID_Logement) REFERENCES Logement(ID_Logement),
   FOREIGN KEY(ID_Adaptation) REFERENCES Adaptation(ID_Adaptation)
);

CREATE TABLE images_logement(
   ID_Logement INT,
   ID_Image INT,
   PRIMARY KEY(ID_Logement, ID_Image),
   FOREIGN KEY(ID_Logement) REFERENCES Logement(ID_Logement),
   FOREIGN KEY(ID_Image) REFERENCES Image(ID_Image)
);

CREATE TABLE images_adaptation(
   ID_Adaptation INT,
   ID_Image INT,
   PRIMARY KEY(ID_Adaptation, ID_Image),
   FOREIGN KEY(ID_Adaptation) REFERENCES Adaptation(ID_Adaptation),
   FOREIGN KEY(ID_Image) REFERENCES Image(ID_Image)
);

CREATE TABLE Equipements(
   ID_Logement INT,
   ID_Equipement INT,
   PRIMARY KEY(ID_Logement, ID_Equipement),
   FOREIGN KEY(ID_Logement) REFERENCES Logement(ID_Logement),
   FOREIGN KEY(ID_Equipement) REFERENCES Equipement(ID_Equipement)
);

CREATE TABLE images_equipement(
   ID_Image INT,
   ID_Equipement INT,
   PRIMARY KEY(ID_Image, ID_Equipement),
   FOREIGN KEY(ID_Image) REFERENCES Image(ID_Image),
   FOREIGN KEY(ID_Equipement) REFERENCES Equipement(ID_Equipement)
);

-- ==============================================================================
--
--                                    INSERT
--
-- ==============================================================================

INSERT INTO `Category` (`Label`) VALUES
('Appartement'),
('Boutique-hôtel'),
('Camping'),
('Chambre d''hôte'),
('Logement mitoyen ou proche'),
('Logement unique'),
('Maison');

INSERT INTO `Type` (`Label`) VALUES
('Logement entier'),
('Chambre privée'),
('Chambre partagée');

