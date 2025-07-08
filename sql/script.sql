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
    idFond INT NOT NULL AUTO_INCREMENT,
    date_creation DATE DEFAULT CURRENT_DATE,
    annee INT GENERATED ALWAYS AS (YEAR(date_creation)) STORED, -- calculé à partir de la date
    solde_initiale DECIMAL(12,2) NOT NULL DEFAULT 0,
    solde_final DECIMAL(12,2) DEFAULT 0,
    solde_en_cours DECIMAL(12,2) GENERATED ALWAYS AS (solde_initiale - solde_final) STORED, -- calculé
    PRIMARY KEY (idFond),
    INDEX idx_annee (annee) -- facultatif mais utile
);
-- TABLE : EF_TypePret
CREATE TABLE EF_TypePret (
    idType INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(100) NOT NULL,
    montant DECIMAL(12,2) NOT NULL,
    taux DECIMAL(5,2) NOT NULL, -- taux d’intérêt en pourcentage
    duree_mois_max INT NOT NULL
);
ALTER TABLE EF_TypePret ADD COLUMN taux_assurance DECIMAL(5,2) DEFAULT 0;
ALTER TABLE EF_TypePret ADD COLUMN taux_assurance DECIMAL(5,2) DEFAULT 0;
Update EF_TypePret set taux_assurance=10;
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
    telephone VARCHAR(20)
);
ALTER TABLE EF_Client ADD COLUMN password VARCHAR(50) DEFAULT ' ';


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
    montant_total DECIMAL(12,2) DEFAULT 0,
    date_maj DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (idTypePret) REFERENCES EF_TypePret(idType),
    FOREIGN KEY (idClient) REFERENCES EF_Client(idClient)
);
ALTER TABLE EF_Pret_Client
ADD COLUMN isApproved BOOLEAN DEFAULT FALSE;
ALTER TABLE EF_Pret_Client ADD COLUMN interet_total DECIMAL(12,2) DEFAULT 0;
ALTER TABLE  EF_Pret_Client ADD COLUMN assurance_total DECIMAL(12,2) DEFAULT 0;


CREATE TABLE EF_Departement (
    idDepartement INT PRIMARY KEY AUTO_INCREMENT,  -- identifiant unique pour le département
    NomDepartement VARCHAR(255) NOT NULL  -- nom du département (ex: Finance)
);
-- TABLE : EF_Admin
CREATE TABLE EF_Admin (
    idAdmin INT PRIMARY KEY AUTO_INCREMENT,
    mail VARCHAR(100) NOT NULL,
    motdepasse VARCHAR(255) NOT NULL
);
ALTER TABLE EF_Admin
ADD COLUMN idDepartement INT,
ADD CONSTRAINT fk_departement_admin FOREIGN KEY (idDepartement) REFERENCES EF_Departement(idDepartement);


INSERT INTO EF_Departement (NomDepartement) VALUES
('Finance'),
('Commercial');




insert into EF_Admin(mail,motdepasse) values('admin@gmail.com','admin');

CREATE TABLE EF_SuiviPret (
    idSuivi INT AUTO_INCREMENT PRIMARY KEY,
    idPret INT NOT NULL,
    idClient INT NOT NULL,
    montant_attendu DECIMAL(12,2) NOT NULL, -- montant dû à cette échéance (avec intérêt)
    montant_paye DECIMAL(12,2) DEFAULT 0,   -- montant payé à cette échéance
    interet_paye DECIMAL(12,2) DEFAULT 0,   -- part d'intérêt remboursée
    date_debut_pret DATE NOT NULL,
    date_prevu_payement DATE NOT NULL,
    FOREIGN KEY (idPret) REFERENCES EF_Pret_Client(idPret),
    FOREIGN KEY (idClient) REFERENCES EF_Client(idClient)
);
ALTER TABLE EF_SuiviPret
ADD COLUMN annuite DECIMAL(12,2) DEFAULT 0,       -- mensualité constante
ADD COLUMN amortissement DECIMAL(12,2) DEFAULT 0,-- part du capital remboursé
ADD COLUMN interet_a_payer DECIMAL(12,2) DEFAULT 0,
ADD COLUMN assurance_a_payer DECIMAL(12,2) DEFAULT 0;

INSERT INTO EF_Client (idClient, nom, mail, datenaissance, profession, salaire_actuel, adresse, photo_client, telephone, password) VALUES
(1, 'Ando Rabe', 'ando@example.com', '1995-04-12', 'Ingénieur', 1500000.00, 'Antananarivo', 'ando.jpg', '0321234567', '123'),
(2, 'Miora Rakoto', 'miora@example.com', '1992-07-22', 'Comptable', 1200000.00, 'Fianarantsoa', 'miora.jpg', '0322345678', '123'),
(3, 'Tiana Randrian', 'tiana@example.com', '1990-01-15', 'Développeur', 2000000.00, 'Toamasina', 'tiana.jpg', '0323456789', '123'),
(4, 'Hery Razafy', 'hery@example.com', '1998-09-05', 'Enseignant', 800000.00, 'Mahajanga', 'hery.jpg', '0324567890', '123'),
(5, 'Sitraka Andrian', 'sitraka@example.com', '1997-06-30', 'Étudiant', 500000.00, 'Tuléar', 'sitraka.jpg', '0325678901', '123'),
(6, 'stan', 'stan@example.com', '2000-01-01', 'Étudiant', 0.00, 'Tana', NULL, '0321234567', '123');


INSERT INTO EF_TypePret (idType, libelle, montant, taux, duree_mois_max) VALUES
(6, 'Prêt personnel', 10000.00, 5.50, 20),
(7, 'Prêt immobilier', 150000.00, 3.75, 240),
(8, 'Prêt auto', 20000.00, 4.25, 48),
(9, 'Microcrédit', 2000.00, 6.00, 12),
(10, 'Prêt étudiant', 80.00, 2.00, 6);
