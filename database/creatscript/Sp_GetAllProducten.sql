-- ==========================================
-- Exameneis 1: Duidelijk Nederlands commentaar
-- Exameneis 2: Tabel JOINS geïmplementeerd binnen de procedure
-- Exameneis 5: Volledig via een Stored Procedure (inclusief USE database)
-- ==========================================

-- Selecteer expliciet de juiste database (inclusief backticks vanwege de koppeltekens)
USE `Kniploket-Tiko-Mazin`;

DROP PROCEDURE IF EXISTS Sp_GetAllProducten;

DELIMITER $$

CREATE PROCEDURE Sp_GetAllProducten(
    IN p_CategorieId INT UNSIGNED
)
BEGIN
    SELECT 
        p.Id,
        p.Naam AS ProductNaam,
        c.Naam AS CategorieNaam,
        p.Merk,
        p.EANcode,
        p.VerkoopPrijs,
        IFNULL(v.AantalOpVoorraad, 0) AS AantalOpVoorraad
    FROM Product p
    INNER JOIN Categorie c ON p.CategorieId = c.Id
    LEFT JOIN Voorraad v ON p.Id = v.ProductId AND v.IsActief = 1
    WHERE p.IsActief = 1
      -- Als p_CategorieId NULL is, toon dan alle categorieën, anders filteren
      AND (p_CategorieId IS NULL OR p.CategorieId = p_CategorieId)
    ORDER BY c.Naam ASC, p.Naam ASC;
END$$

DELIMITER ;