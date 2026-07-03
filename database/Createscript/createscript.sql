DROP DATABASE IF EXISTS `Kniploket-Tiko-Mazin`;
CREATE DATABASE `Kniploket-Tiko-Mazin`;
USE `Kniploket-Tiko-Mazin`;

-- ==========================================
-- 2. CREATE TABLES
-- ==========================================

CREATE TABLE users (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,name VARCHAR(150) NOT NULL
    ,email VARCHAR(255) NOT NULL UNIQUE
    ,email_verified_at DATETIME NULL
    ,password VARCHAR(255) NOT NULL
    ,role VARCHAR(30) NOT NULL
    ,remember_token VARCHAR(100) NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,created_at DATETIME(6) NOT NULL
    ,updated_at DATETIME(6) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Klant (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,UserId INT UNSIGNED NOT NULL
    ,Voornaam VARCHAR(100) NOT NULL
    ,Tussenvoegsel VARCHAR(30) NULL
    ,Achternaam VARCHAR(100) NOT NULL
    ,Relatienummer VARCHAR(20) NOT NULL
    ,Bijzonderheden VARCHAR(15) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,FOREIGN KEY (UserId) REFERENCES users(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Medewerker (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,UserId INT UNSIGNED NOT NULL
    ,Voornaam VARCHAR(100) NOT NULL
    ,Tussenvoegsel VARCHAR(30) NULL
    ,Achternaam VARCHAR(100) NOT NULL
    ,Specialisatie VARCHAR(50) NOT NULL
    ,Geboortedatum DATE NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,FOREIGN KEY (UserId) REFERENCES users(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Contact (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Straatnaam VARCHAR(100) NOT NULL
    ,Huisnummer SMALLINT NOT NULL
    ,Toevoeging VARCHAR(10) NULL
    ,Postcode VARCHAR(10) NOT NULL
    ,Plaats VARCHAR(100) NOT NULL
    ,Email VARCHAR(255) NOT NULL
    ,Mobiel VARCHAR(15) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE KlantPerContact (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,KlantId INT UNSIGNED NOT NULL
    ,ContactId INT UNSIGNED NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,FOREIGN KEY (KlantId) REFERENCES Klant(Id)
    ,FOREIGN KEY (ContactId) REFERENCES Contact(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE MedewerkerPerContact (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,MedewerkerId INT UNSIGNED NOT NULL
    ,ContactId INT UNSIGNED NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,FOREIGN KEY (MedewerkerId) REFERENCES Medewerker(Id)
    ,FOREIGN KEY (ContactId) REFERENCES Contact(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Behandeling (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam VARCHAR(100) NOT NULL
    ,Omschrijving VARCHAR(255) NOT NULL
    ,DuurMinuten SMALLINT NOT NULL
    ,Prijs DECIMAL(6,2) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Beschikbaarheid (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,MedewerkerId INT UNSIGNED NOT NULL
    ,Dagnaam VARCHAR(10) NOT NULL
    ,Datum DATE NOT NULL
    ,Starttijd TIME NOT NULL
    ,Eindtijd TIME NOT NULL
    ,BeschStatus VARCHAR(20) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (MedewerkerId) REFERENCES Medewerker(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE MedewerkerPerBehandeling (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,MedewerkerId INT UNSIGNED NOT NULL
    ,BehandelingId INT UNSIGNED NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (MedewerkerId) REFERENCES Medewerker(Id)
    ,FOREIGN KEY (BehandelingId) REFERENCES Behandeling(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Afspraak (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,KlantId INT UNSIGNED NOT NULL
    ,MedewerkerPerBehandelingId INT UNSIGNED NOT NULL
    ,BeschikbaarheidId INT UNSIGNED NOT NULL
    ,Datum DATE NOT NULL
    ,Starttijd TIME NOT NULL
    ,Afspraakstatus VARCHAR(20) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (KlantId) REFERENCES Klant(Id)
    ,FOREIGN KEY (MedewerkerPerBehandelingId) REFERENCES MedewerkerPerBehandeling(Id)
    ,FOREIGN KEY (BeschikbaarheidId) REFERENCES Beschikbaarheid(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Feedback (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,KlantId INT UNSIGNED NOT NULL
    ,AfspraakId INT UNSIGNED NOT NULL
    ,Soort VARCHAR(20) NOT NULL
    ,Bericht VARCHAR(255) NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (KlantId) REFERENCES Klant(Id)
    ,FOREIGN KEY (AfspraakId) REFERENCES Afspraak(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Bestelling (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,KlantId INT UNSIGNED NOT NULL
    ,BestelNummer INT NOT NULL UNIQUE
    ,Omschrijving VARCHAR(255) NOT NULL
    ,Datum DATE NOT NULL
    ,Tijd TIME NOT NULL
    ,Bestelstatus VARCHAR(30) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (KlantId) REFERENCES Klant(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Categorie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam VARCHAR(150) NOT NULL UNIQUE
    ,Omschrijving VARCHAR(255) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Product (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,CategorieId INT UNSIGNED NOT NULL
    ,Naam VARCHAR(100) NOT NULL
    ,Omschrijving VARCHAR(255) NOT NULL
    ,Merk VARCHAR(50) NOT NULL
    ,EANcode VARCHAR(20) NOT NULL UNIQUE
    ,Houdbaarheidsdatum DATE NOT NULL
    ,InkoopPrijs DECIMAL(6,2) NOT NULL
    ,VerkoopPrijs DECIMAL(6,2) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (CategorieId) REFERENCES Categorie(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ProductPerBestelling (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,ProductId INT UNSIGNED NOT NULL
    ,BestellingId INT UNSIGNED NOT NULL
    ,Aantal SMALLINT NOT NULL
    ,UnitPrijs DECIMAL(6,2) NOT NULL
    ,BTWPercentage DECIMAL(4,2) NOT NULL
    ,Korting DECIMAL(4,2) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (ProductId) REFERENCES Product(Id)
    ,FOREIGN KEY (BestellingId) REFERENCES Bestelling(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Voorraad (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,ProductId INT UNSIGNED NOT NULL
    ,AantalOpVoorraad SMALLINT NOT NULL
    ,Aantaluitgegeven SMALLINT NOT NULL
    ,Aantalbijgekomen SMALLINT NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (ProductId) REFERENCES Product(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE BehandelingPerVoorraad (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,BehandelingId INT UNSIGNED NOT NULL
    ,VoorraadId INT UNSIGNED NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (BehandelingId) REFERENCES Behandeling(Id)
    ,FOREIGN KEY (VoorraadId) REFERENCES Voorraad(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Leverancier (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam VARCHAR(100) NOT NULL
    ,Straatnaam VARCHAR(100) NOT NULL
    ,Huisnummer SMALLINT NOT NULL
    ,Toevoeging VARCHAR(10) NULL
    ,Postcode VARCHAR(10) NOT NULL
    ,Plaats VARCHAR(100) NOT NULL
    ,Email VARCHAR(255) NOT NULL
    ,Mobiel VARCHAR(20) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE LeverancierOrder (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Ordernummer VARCHAR(20) NOT NULL UNIQUE
    ,ProductId INT UNSIGNED NOT NULL
    ,LeverancierId INT UNSIGNED NOT NULL
    ,Aantal INT NOT NULL
    ,Orderdatum DATE NOT NULL
    ,Leverdatum DATE NULL
    ,Leverstatus VARCHAR(20) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(255) NULL
    ,DatumAangemaakt DATETIME NOT NULL
    ,DatumGewijzigd DATETIME NOT NULL
    ,FOREIGN KEY (ProductId) REFERENCES Product(Id)
    ,FOREIGN KEY (LeverancierId) REFERENCES Leverancier(Id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- 3. INSERT TESTDATA
-- ==========================================

INSERT INTO users (Id, name, email, email_verified_at, password, role, remember_token, IsActief, Opmerking, created_at, updated_at) VALUES
(1, 'Salon Eigenaar', 'eigenaar@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'eigenaar', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(2, 'Fatima El Amrani', 'fatima@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(3, 'Sanne de Vries', 'sanne.devries@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(4, 'Mohamed El Idrissi', 'mohamed.elidrissi@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(5, 'Lisa van Dijk', 'lisa.vandijk@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(6, 'Youssef Benali', 'youssef.benali@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(7, 'Noor Bakker', 'noor.bakker@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(8, 'Kevin Smit', 'kevin.smit@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(9, 'Aylin Demir', 'aylin.demir@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(10, 'Tom Verhoeven', 'tom.verhoeven@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(11, 'Romy Jacobs', 'romy.jacobs@kniplokettiko.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'medewerker', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(12, 'Piet van Loenen', 'piet.van.loenen@gmail.com', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'klant', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(13, 'Jan Jansen', 'jan.jansen@outlook.com', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'klant', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(14, 'Saskia de Boer', 'saskia.deboer@yahoo.com', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'klant', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(15, 'Ahmed Mansouri', 'ahmed.mansouri@icloud.com', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'klant', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(16, 'Marieke van den Berg', 'marieke.vandenberg@ziggo.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'klant', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30'),
(17, 'Daan Visser', 'daan.visser@live.nl', NULL, '$2y$10$1S7dpZxfyl4IcQAtIzUklulMSor3EADTAPktFHNcFsg87geQVgrMu', 'klant', NULL, 1, NULL, '2026-07-02 09:09:30', '2026-07-02 09:09:30');

INSERT INTO Klant (Id, UserId, Voornaam, Tussenvoegsel, Achternaam, Relatienummer, Bijzonderheden, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 12, 'Piet', 'van', 'Loenen', 'KL-2026-001', 'Voorkeur voor ochtendafspraken.', 1, NULL, NOW(6), NOW(6)),
(2, 13, 'Jan', NULL, 'Jansen', 'KL-2026-002', 'Allergie voor sterk geparfumeerde producten.', 1, NULL, NOW(6), NOW(6)),
(3, 14, 'Saskia', 'de', 'Boer', 'KL-2026-003', 'Komt elke zes weken.', 1, NULL, NOW(6), NOW(6)),
(4, 15, 'Ahmed', NULL, 'Mansouri', 'KL-2026-004', 'Wil strakke fade.', 1, NULL, NOW(6), NOW(6)),
(5, 16, 'Marieke', 'van den', 'Berg', 'KL-2026-005', 'Gevoelige hoofdhuid.', 1, NULL, NOW(6), NOW(6)),
(6, 17, 'Daan', NULL, 'Visser', 'KL-2026-006', 'Liefst einde middag.', 1, NULL, NOW(6), NOW(6));

INSERT INTO Medewerker (Id, UserId, Voornaam, Tussenvoegsel, Achternaam, Specialisatie, Geboortedatum, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 2, 'Fatima', NULL, 'El Amrani', 'Knippen', '1988-04-12', 1, NULL, NOW(6), NOW(6)),
(2, 3, 'Sanne', 'de', 'Vries', 'Kleuren', '1996-09-25', 1, NULL, NOW(6), NOW(6)),
(3, 4, 'Mohamed', NULL, 'El Idrissi', 'Extensions', '1992-02-14', 1, NULL, NOW(6), NOW(6)),
(4, 5, 'Lisa', 'van', 'Dijk', 'Stylen', '1998-07-08', 1, NULL, NOW(6), NOW(6)),
(5, 6, 'Youssef', NULL, 'Benali', 'Knippen', '1990-11-30', 1, NULL, NOW(6), NOW(6)),
(6, 7, 'Noor', NULL, 'Bakker', 'Kleuren', '1997-05-21', 1, NULL, NOW(6), NOW(6)),
(7, 8, 'Kevin', NULL, 'Smit', 'Extensions', '2001-03-17', 1, NULL, NOW(6), NOW(6)),
(8, 9, 'Aylin', NULL, 'Demir', 'Stylen', '1999-12-04', 1, NULL, NOW(6), NOW(6)),
(9, 10, 'Tom', NULL, 'Verhoeven', 'Knippen', '1995-08-19', 1, NULL, NOW(6), NOW(6)),
(10, 11, 'Romy', NULL, 'Jacobs', 'Knippen', '2010-01-15', 1, NULL, NOW(6), NOW(6));

INSERT INTO Contact (Id, Straatnaam, Huisnummer, Toevoeging, Postcode, Plaats, Email, Mobiel, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'Kanaalstraat', 12, NULL, '3511AB', 'Utrecht', 'fatima@kniplokettiko.nl', '0612345678', 1, NULL, NOW(), NOW()),
(2, 'Croeselaan', 101, NULL, '3521BJ', 'Utrecht', 'sanne.devries@kniplokettiko.nl', '0611111111', 1, NULL, NOW(), NOW()),
(3, 'Amsterdamsestraatweg', 223, NULL, '3551CG', 'Utrecht', 'mohamed.elidrissi@kniplokettiko.nl', '0611111112', 1, NULL, NOW(), NOW()),
(4, 'Maliebaan', 17, NULL, '3581CC', 'Utrecht', 'lisa.vandijk@kniplokettiko.nl', '0611111113', 1, NULL, NOW(), NOW()),
(5, 'Balijelaan', 63, NULL, '3521GM', 'Utrecht', 'youssef.benali@kniplokettiko.nl', '0611111114', 1, NULL, NOW(), NOW()),
(6, 'Nachtegaalstraat', 95, NULL, '3581AE', 'Utrecht', 'noor.bakker@kniplokettiko.nl', '0611111115', 1, NULL, NOW(), NOW()),
(7, 'Bernardlaan', 7, NULL, '3527GA', 'Utrecht', 'kevin.smit@kniplokettiko.nl', '0611111116', 1, NULL, NOW(), NOW()),
(8, 'Laan van Nieuw-Guinea', 141, NULL, '3531JE', 'Utrecht', 'aylin.demir@kniplokettiko.nl', '0611111117', 1, NULL, NOW(), NOW()),
(9, 'Marnixlaan', 205, NULL, '3552HD', 'Utrecht', 'tom.verhoeven@kniplokettiko.nl', '0611111118', 1, NULL, NOW(), NOW()),
(10, 'Haroekoeplein', 29, NULL, '3531WK', 'Utrecht', 'romy.jacobs@kniplokettiko.nl', '0611111119', 1, NULL, NOW(), NOW()),
(11, 'Oudegracht', 88, 'A', '3512AB', 'Utrecht', 'piet.van.loenen@gmail.com', '+31 6 1234 61 71', 1, NULL, NOW(), NOW()),
(12, 'Biltstraat', 44, NULL, '3572BC', 'Utrecht', 'jan.jansen@outlook.com', '+31 6 1234 61 72', 1, NULL, NOW(), NOW()),
(13, 'Merelstraat', 12, NULL, '3514CN', 'Utrecht', 'saskia.deboer@yahoo.com', '+31 6 1234 61 73', 1, NULL, NOW(), NOW()),
(14, 'Winkel van Sinkelstraat', 4, NULL, '3511KV', 'Utrecht', 'ahmed.mansouri@icloud.com', '+31 6 1234 61 74', 1, NULL, NOW(), NOW()),
(15, 'Adelaarstraat', 50, NULL, '3514CH', 'Utrecht', 'marieke.vandenberg@ziggo.nl', '+31 6 1234 61 75', 1, NULL, NOW(), NOW()),
(16, 'Vleutenseweg', 73, NULL, '3532HA', 'Utrecht', 'daan.visser@live.nl', '+31 6 1234 61 76', 1, NULL, NOW(), NOW());

INSERT INTO KlantPerContact (Id, KlantId, ContactId, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 11, 1, NULL, NOW(6), NOW(6)),
(2, 2, 12, 1, NULL, NOW(6), NOW(6)),
(3, 3, 13, 1, NULL, NOW(6), NOW(6)),
(4, 4, 14, 1, NULL, NOW(6), NOW(6)),
(5, 5, 15, 1, NULL, NOW(6), NOW(6)),
(6, 6, 16, 1, NULL, NOW(6), NOW(6));

INSERT INTO MedewerkerPerContact (Id, MedewerkerId, ContactId, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 1, 1, NULL, NOW(6), NOW(6)),
(2, 2, 2, 1, NULL, NOW(6), NOW(6)),
(3, 3, 3, 1, NULL, NOW(6), NOW(6)),
(4, 4, 4, 1, NULL, NOW(6), NOW(6)),
(5, 5, 5, 1, NULL, NOW(6), NOW(6)),
(6, 6, 6, 1, NULL, NOW(6), NOW(6)),
(7, 7, 7, 1, NULL, NOW(6), NOW(6)),
(8, 8, 8, 1, NULL, NOW(6), NOW(6)),
(9, 9, 9, 1, NULL, NOW(6), NOW(6)),
(10, 10, 10, 1, NULL, NOW(6), NOW(6));

INSERT INTO Behandeling (Id, Naam, Omschrijving, DuurMinuten, Prijs, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'Knippen', 'Haar knippen en eventueel stylen.', 30, 30.00, 1, NULL, NOW(), NOW()),
(2, 'Combi behandelingen', 'Combinatie van knippen, kleuren en stylen.', 90, 90.00, 1, NULL, NOW(), NOW()),
(3, 'Kleuren', 'Haar kleuren (diverse technieken).', 60, 60.00, 1, NULL, NOW(), NOW()),
(4, 'Permanent', 'Permanente omvorming van het haar.', 120, 110.00, 1, NULL, NOW(), NOW()),
(5, 'Extensions', 'Plaatsen en verzorgen van extensions.', 180, 250.00, 1, NULL, NOW(), NOW());

INSERT INTO Beschikbaarheid (Id, MedewerkerId, Dagnaam, Datum, Starttijd, Eindtijd, BeschStatus, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(2, 1, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(3, 2, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(4, 2, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(5, 3, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(6, 3, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(7, 4, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(8, 4, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(9, 5, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(10, 5, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(11, 6, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(12, 6, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(13, 7, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(14, 7, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(15, 8, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(16, 8, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(17, 9, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(18, 9, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(19, 10, 'Woensdag', '2026-07-15', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW()),
(20, 10, 'Vrijdag', '2026-07-10', '09:00:00', '17:00:00', 'Beschikbaar', 1, NULL, NOW(), NOW());

INSERT INTO MedewerkerPerBehandeling (Id, MedewerkerId, BehandelingId, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 1, 1, NULL, NOW(), NOW()),
(2, 1, 3, 1, NULL, NOW(), NOW()),
(3, 1, 2, 1, NULL, NOW(), NOW()),
(4, 2, 1, 1, NULL, NOW(), NOW()),
(5, 2, 3, 1, NULL, NOW(), NOW()),
(6, 3, 1, 1, NULL, NOW(), NOW()),
(7, 3, 3, 1, NULL, NOW(), NOW()),
(8, 4, 1, 1, NULL, NOW(), NOW()),
(9, 4, 3, 1, NULL, NOW(), NOW()),
(10, 4, 2, 1, NULL, NOW(), NOW()),
(11, 5, 4, 1, NULL, NOW(), NOW());

INSERT INTO Afspraak (Id, KlantId, MedewerkerPerBehandelingId, BeschikbaarheidId, Datum, Starttijd, Afspraakstatus, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 1, 2, '2026-07-10', '10:00:00', 'Inbehandeling', 1, NULL, NOW(), NOW()),
(2, 2, 2, 7, '2026-07-15', '11:30:00', 'Behandeld', 1, NULL, NOW(), NOW()),
(3, 5, 8, 8, '2026-07-10', '14:00:00', 'Geannuleerd', 1, NULL, NOW(), NOW()),
(4, 6, 11, 4, '2026-07-10', '13:30:00', 'Behandeld', 1, NULL, NOW(), NOW()),
(5, 3, 6, 6, '2026-07-10', '15:00:00', 'Inbehandeling', 1, NULL, NOW(), NOW()),
(6, 4, 10, 9, '2026-07-15', '12:30:00', 'Geannuleerd', 1, NULL, NOW(), NOW());

INSERT INTO Feedback (Id, KlantId, AfspraakId, Soort, Bericht, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 1, 'Review', NULL, 1, NULL, NOW(), NOW()),
(2, 2, 2, 'Klacht', NULL, 1, NULL, NOW(), NOW()),
(3, 3, 3, 'Review', NULL, 1, NULL, NOW(), NOW()),
(4, 4, 4, 'Review', NULL, 1, NULL, NOW(), NOW()),
(5, 5, 5, 'Klacht', NULL, 1, NULL, NOW(), NOW());

INSERT INTO Bestelling (Id, KlantId, BestelNummer, Omschrijving, Datum, Tijd, Bestelstatus, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 600101, 'Salonproducten besteld na knipafspraak.', '2026-06-20', '09:15:00', 'Ontvangen', 1, NULL, NOW(), NOW()),
(2, 2, 600102, 'Aanvulling op thuisverzorging na kleuradvies.', '2026-06-23', '11:40:00', 'Bevestigd', 1, NULL, NOW(), NOW()),
(3, 3, 600103, 'Stylingproducten besteld na behandeling.', '2026-06-25', '14:20:00', 'Inverwerking', 1, NULL, NOW(), NOW()),
(4, 4, 600104, 'Baardverzorging besteld voor verzending.', '2026-06-28', '16:05:00', 'Verzonden', 1, NULL, NOW(), NOW()),
(5, 5, 600105, 'Stylingproducten afgerond en afgeleverd.', '2026-06-30', '10:30:00', 'Afgeleverd', 1, NULL, NOW(), NOW()),
(6, 1, 600106, 'Salonproducten status-test (Ontvangen)', '2026-06-10', '08:20:00', 'Ontvangen', 1, NULL, NOW(), NOW()),
(7, 2, 600107, 'Haarverzorging status-test (Ontvangen)', '2026-06-14', '11:35:00', 'Ontvangen', 1, NULL, NOW(), NOW()),
(8, 3, 600108, 'Stylingproducten status-test (Ontvangen)', '2026-06-15', '13:22:00', 'Ontvangen', 1, NULL, NOW(), NOW()),
(9, 4, 600109, 'Bevestigde bestelling Wax Gel', '2026-06-11', '09:15:00', 'Bevestigd', 1, NULL, NOW(), NOW()),
(10, 5, 600110, 'Bevestigde bestelling Color Creme', '2026-06-13', '15:50:00', 'Bevestigd', 1, NULL, NOW(), NOW()),
(11, 6, 600111, 'Bevestigde bestelling Conditioner', '2026-06-17', '10:44:00', 'Bevestigd', 1, NULL, NOW(), NOW()),
(12, 1, 600112, 'Order in verwerking Masker', '2026-06-18', '11:10:00', 'Inverwerking', 1, NULL, NOW(), NOW()),
(13, 2, 600113, 'Order in verwerking Baardolie', '2026-06-21', '16:25:00', 'Inverwerking', 1, NULL, NOW(), NOW()),
(14, 3, 600114, 'Order in verwerking Styling Clay', '2026-06-22', '14:45:00', 'Inverwerking', 1, NULL, NOW(), NOW()),
(15, 4, 600115, 'Verzonden testorder Shampoo', '2026-06-23', '13:05:00', 'Verzonden', 1, NULL, NOW(), NOW()),
(16, 5, 600116, 'Verzonden testorder Heat Protect', '2026-06-24', '15:30:00', 'Verzonden', 1, NULL, NOW(), NOW()),
(17, 6, 600117, 'Verzonden testorder Strong Hold Gel', '2026-06-27', '16:18:00', 'Verzonden', 1, NULL, NOW(), NOW()),
(18, 1, 600118, 'Afgeleverde kleurcreme and conditioner', '2026-06-28', '10:12:00', 'Afgeleverd', 1, NULL, NOW(), NOW()),
(19, 2, 600119, 'Afgeleverde haarverzorgingsset', '2026-06-29', '15:43:00', 'Afgeleverd', 1, NULL, NOW(), NOW()),
(20, 3, 600120, 'Afgeleverde stylingproducten', '2026-06-30', '09:58:00', 'Afgeleverd', 1, NULL, NOW(), NOW());

INSERT INTO Categorie (Id, Naam, Omschrijving, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'Haarverzorging', 'Producten voor wassen en verzorgen.', 1, NULL, NOW(), NOW()),
(2, 'Kleurproducten', 'Producten voor kleurbehandelingen.', 1, NULL, NOW(), NOW()),
(3, 'Styling', 'Producten voor afwerking en styling.', 1, NULL, NOW(), NOW()),
(4, 'Accessoires', 'Accessoires voor verkoop in de salon.', 1, NULL, NOW(), NOW());

INSERT INTO Product (Id, CategorieId, Naam, Omschrijving, Merk, EANcode, Houdbaarheidsdatum, InkoopPrijs, VerkoopPrijs, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 'Hydrating Shampoo', 'Milde salonshampoo voor dagelijks gebruik.', 'Tiko Care', '0871234500001', '2027-07-01', 6.50, 14.95, 1, NULL, NOW(), NOW()),
(2, 1, 'Repair Conditioner', 'Voedende conditioner voor beschadigd haar.', 'Tiko Care', '0871234500002', '2027-10-15', 7.25, 16.95, 1, NULL, NOW(), NOW()),
(3, 1, 'Scalp Balance Masker', 'Kalmerend haarmasker voor gevoelige hoofdhuid.', 'Tiko Care', '0871234500003', '2027-05-20', 8.75, 19.95, 1, NULL, NOW(), NOW()),
(4, 1, 'Baardolie Cedar', 'Verzorgende olie voor baardbehandelingen.', 'Tiko Beard', '0871234500004', '2027-09-30', 5.75, 12.95, 1, NULL, NOW(), NOW()),
(5, 2, 'Color Creme 6.1', 'Professionele asdonkerblonde kleurcreme.', 'Tiko Color', '0871234500005', '2026-12-31', 12.50, 24.95, 1, NULL, NOW(), NOW()),
(6, 2, 'Color Creme 7.43', 'Koperblonde salonkleur met warme ondertoon.', 'Tiko Color', '0871234500006', '2027-01-31', 12.75, 25.95, 1, NULL, NOW(), NOW()),
(7, 2, 'Developer 6 Procent', 'Oxidatiecreme voor kleurbehandelingen.', 'Tiko Color', '0871234500007', '2027-03-31', 5.95, 11.95, 1, NULL, NOW(), NOW()),
(8, 3, 'Matte Styling Clay', 'Matte clay met flexibele hold.', 'Tiko Style', '0871234500008', '2027-08-31', 4.95, 12.95, 1, NULL, NOW(), NOW()),
(9, 3, 'Strong Hold Gel', 'Sterke hold styling gel.', 'Tiko Style', '0871234500009', '2027-03-31', 4.25, 9.95, 1, NULL, NOW(), NOW()),
(10, 3, 'Heat Protect Spray', 'Beschermende spray voor föhnen en stylen.', 'Tiko Style', '0871234500010', '2027-11-30', 6.10, 15.95, 1, NULL, NOW(), NOW());

INSERT INTO ProductPerBestelling (Id, ProductId, BestellingId, Aantal, UnitPrijs, BTWPercentage, Korting, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 1, 2, 14.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(2, 2, 1, 1, 16.95, 21.00, 10.00, 1, NULL, NOW(), NOW()),
(3, 2, 2, 3, 16.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(4, 5, 2, 2, 24.95, 21.00, 5.00, 1, NULL, NOW(), NOW()),
(5, 3, 3, 1, 19.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(6, 8, 3, 2, 12.95, 21.00, 15.00, 1, NULL, NOW(), NOW()),
(7, 4, 4, 1, 12.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(8, 9, 5, 1, 9.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(9, 10, 5, 1, 15.95, 21.00, 7.50, 1, NULL, NOW(), NOW()),
(10, 1, 6, 2, 14.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(11, 2, 6, 1, 16.95, 21.00, 30.00, 1, NULL, NOW(), NOW()),
(12, 3, 7, 1, 19.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(13, 4, 8, 1, 12.95, 21.00, 10.00, 1, NULL, NOW(), NOW()),
(14, 5, 9, 1, 24.95, 21.00, 15.00, 1, NULL, NOW(), NOW()),
(15, 2, 10, 2, 16.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(16, 8, 11, 1, 12.95, 21.00, 25.00, 1, NULL, NOW(), NOW()),
(17, 1, 12, 1, 14.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(18, 3, 13, 1, 19.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(19, 8, 14, 2, 12.95, 21.00, 50.00, 1, NULL, NOW(), NOW()),
(20, 1, 15, 1, 14.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(21, 10, 16, 1, 15.95, 21.00, 40.00, 1, NULL, NOW(), NOW()),
(22, 9, 17, 1, 9.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(23, 5, 18, 1, 24.95, 21.00, 20.00, 1, NULL, NOW(), NOW()),
(24, 2, 19, 1, 16.95, 21.00, 0.00, 1, NULL, NOW(), NOW()),
(25, 8, 20, 1, 12.95, 21.00, 10.00, 1, NULL, NOW(), NOW());

INSERT INTO Voorraad (Id, ProductId, AantalOpVoorraad, Aantaluitgegeven, Aantalbijgekomen, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 40, 0, 40, 1, NULL, NOW(), NOW()),
(2, 2, 28, 2, 30, 1, NULL, NOW(), NOW()),
(3, 3, 18, 0, 18, 1, NULL, NOW(), NOW()),
(4, 4, 20, 0, 20, 1, NULL, NOW(), NOW()),
(5, 5, 25, 0, 25, 1, NULL, NOW(), NOW()),
(6, 6, 16, 1, 17, 1, NULL, NOW(), NOW()),
(7, 7, 32, 3, 35, 1, NULL, NOW(), NOW()),
(8, 8, 22, 0, 22, 1, NULL, NOW(), NOW()),
(9, 9, 35, 0, 35, 1, NULL, NOW(), NOW()),
(10, 10, 24, 1, 25, 1, NULL, NOW(), NOW());

INSERT INTO BehandelingPerVoorraad (Id, BehandelingId, VoorraadId, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 1, 1, NULL, NOW(), NOW()),
(2, 1, 3, 1, NULL, NOW(), NOW()),
(3, 2, 1, 1, NULL, NOW(), NOW()),
(4, 2, 2, 1, NULL, NOW(), NOW()),
(5, 2, 3, 1, NULL, NOW(), NOW()),
(6, 3, 2, 1, NULL, NOW(), NOW()),
(7, 4, 3, 1, NULL, NOW(), NOW()),
(8, 5, 4, 1, NULL, NOW(), NOW());

INSERT INTO Leverancier (Id, Naam, Straatnaam, Huisnummer, Toevoeging, Postcode, Plaats, Email, Mobiel, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'Van Duuren Haircosmetics', 'Prinses Irenestraat', 12, 'A', '3584AN', 'Utrecht', 'inkoop@vanduurenhaircosmetics.nl', '+31 623456121', 1, NULL, NOW(), NOW()),
(2, 'ColorPro Benelux', 'Gibraltarstraat', 234, NULL, '5611AA', 'Eindhoven', 'orders@colorpro-benelux.nl', '+31 623456122', 1, NULL, NOW(), NOW()),
(3, 'SalonStyle Supplies', 'Der Kinderenstraat', 456, 'Bis', '3011AB', 'Rotterdam', 'service@salonstylesupplies.nl', '+31 623456123', 1, NULL, NOW(), NOW()),
(4, 'BarberCare Nederland', 'Nachtegaalstraat', 233, 'A', '4811AA', 'Breda', 'bestellingen@barbercare-nederland.nl', '+31 623456124', 1, NULL, NOW(), NOW()),
(5, 'HairTools Groothandel', 'Bertram Russellstraat', 45, NULL, '8011AB', 'Zwolle', 'contact@hairtools-groothandel.nl', '+31 623456125', 1, NULL, NOW(), NOW());

INSERT INTO LeverancierOrder (Id, Ordernummer, ProductId, LeverancierId, Aantal, Orderdatum, Leverdatum, Leverstatus, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'ORD-2026-1001', 1, 1, 12, '2026-05-04', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW()),
(2, 'ORD-2026-1002', 5, 2, 8, '2026-05-05', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW()),
(3, 'ORD-2026-1003', 9, 3, 10, '2026-05-06', '2026-05-08', 'Geleverd', 1, NULL, NOW(), NOW()),
(4, 'ORD-2026-1004', 7, 2, 6, '2026-05-07', NULL, 'Nietleverbaar', 1, NULL, NOW(), NOW()),
(5, 'ORD-2026-1005', 10, 4, 9, '2026-05-08', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW()),
(6, 'ORD-2026-1006', 2, 1, 7, '2026-05-09', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW()),
(7, 'ORD-2026-1007', 3, 1, 6, '2026-05-10', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW()),
(8, 'ORD-2026-1008', 4, 4, 5, '2026-05-10', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW()),
(9, 'ORD-2026-1009', 6, 2, 6, '2026-05-11', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW()),
(10, 'ORD-2026-1010', 8, 3, 8, '2026-05-11', NULL, 'Inbehandeling', 1, NULL, NOW(), NOW());
