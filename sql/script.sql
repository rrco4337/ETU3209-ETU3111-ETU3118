CREATE DATABASE tp_flight CHARACTER SET utf8mb4;

USE tp_flight;


-- CREATE TABLE etudiant (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nom VARCHAR(100),
--     prenom VARCHAR(100),
--     email VARCHAR(100),
--     age INT
-- );

-- ============================
-- SCHEMA : Gestion de prêts
-- ============================

-- TABLE : EF_Fond_Financier
-- TABLE : EF_Fond_Financier
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
    password VARCHAR(50),
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

CREATE TABLE EF_Payement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    id_type_pret INT NOT NULL,
    mois VARCHAR(20) NOT NULL, -- Ex: '2025-07' ou 'Juillet 2025'
    montant DECIMAL(10,2) NOT NULL,
    date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_client) REFERENCES EF_Client(idClient),
    FOREIGN KEY (id_type_pret) REFERENCES EF_TypePret(idType)
);


INSERT INTO EF_Client (nom, mail, password, datenaissance, profession, salaire_actuel, adresse, telephone, solde)
VALUES 
('Martin', 'elise.martin@email.com',
 '123',
 '1995-08-14', 'Graphiste', 38000.00, '25 Rue des Arts, 75003 Paris', '+33611223344', 1250.50);
INSERT INTO EF_TypePret (libelle, montant, taux, duree_mois_max)
VALUES 
('Prêt Personnel', 5000.00, 5.0, 12),
('Prêt Étudiant', 3000.00, 2.5, 24);
INSERT INTO EF_Fond_Financier (solde_initiale, solde_final, solde_en_cours)
VALUES (100000.00, 95000.00, 95000.00);
INSERT INTO EF_Pret_Client (idTypePret, idClient, status, date_debut_pret, montant_paye, montant_restant)
VALUES 
(1, 1, 1, '2025-06-01', 1000.00, 4000.00);
INSERT INTO EF_Payement (id_client, id_type_pret, mois, montant, date_paiement)
VALUES 
(1, 1, '2025-06', 500.00, '2025-06-15 10:00:00'),
(1, 1, '2025-07', 500.00, '2025-07-05 09:30:00');
