CREATE DATABASE tp_flight CHARACTER SET utf8mb4;

USE tp_flight;


CREATE TABLE EF_Fond_Financier (
    idFond INT PRIMARY KEY AUTO_INCREMENT,
    solde_initiale DECIMAL(12,2) NOT NULL DEFAULT 0,
    solde_final DECIMAL(12,2) DEFAULT 0,
    solde_en_cours DECIMAL(12,2) DEFAULT 0,
    date_creation DATE DEFAULT CURRENT_DATE
);

-- TABLE : EF_TypePret
CREATE TABLE EF_TypePret (
    idType INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(100) NOT NULL,
    montant DECIMAL(12,2) NOT NULL,
    taux DECIMAL(5,2) NOT NULL, -- taux d’intérêt en pourcentage
    duree_mois_max INT NOT NULL
);

-- TABLE : EF_Client
CREATE TABLE EF_Client (
    idClient INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    mail VARCHAR(100),
    datenaissance DATE,
    profession VARCHAR(100),
    salaire_actuel DECIMAL(12,2) DEFAULT 0,
    adresse VARCHAR(255),
    photo_client VARCHAR(255),
    telephone VARCHAR(20),
    solde DECIMAL(12,2) DEFAULT 0
);

-- TABLE : Prevision_Client
CREATE TABLE Prevision_Client (
    idClient INT PRIMARY KEY,
    montant DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (idClient) REFERENCES EF_Client(idClient)
);

-- TABLE : EF_Pret_Client
CREATE TABLE EF_Pret_Client (
    idPret INT PRIMARY KEY AUTO_INCREMENT,
    idTypePret INT NOT NULL,
    idClient INT NOT NULL,
    status INT DEFAULT 0 CHECK (status IN (0, 1, 2, 3, 4)),
    date_debut_pret DATE DEFAULT CURRENT_DATE,
    montant_paye DECIMAL(12,2) DEFAULT 0,
    montant_restant DECIMAL(12,2) DEFAULT 0,
    date_maj DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (idTypePret) REFERENCES EF_TypePret(idType),
    FOREIGN KEY (idClient) REFERENCES EF_Client(idClient)
);

-- TABLE : EF_Admin
CREATE TABLE EF_Admin (
    idAdmin INT PRIMARY KEY AUTO_INCREMENT,
    mail VARCHAR(100) NOT NULL,
    motdepasse VARCHAR(255) NOT NULL
);

