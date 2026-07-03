-- =============================================
-- Procedure: GetAllProducten
-- Doel: Geeft alle actieve producten terug met categorienaam en actuele voorraad
--       ten behoeve van het productenoverzicht (User Story 07).
--       Joins: INNER JOIN Categorie (elk product heeft een categorie),
--       LEFT JOIN Voorraad (niet elk product hoeft een voorraadregel te hebben).
-- Parameters: p_CategorieId INT UNSIGNED - CategorieId om op te filteren,
--             of NULL/0 voor alle categorieën
-- Return: resultset met Id, Naam, CategorieNaam, Merk, EANcode, VerkoopPrijs,
--         AantalOpVoorraad (0 als er geen voorraadregel bestaat)
-- =============================================
DELIMITER $$

CREATE PROCEDURE GetAllProducten(IN p_CategorieId INT UNSIGNED)
BEGIN
    SELECT
        p.Id,
        p.Naam,
        c.Naam AS CategorieNaam,
        p.Merk,
        p.EANcode,
        p.VerkoopPrijs,
        IFNULL(v.AantalOpVoorraad, 0) AS AantalOpVoorraad
    FROM Product p
    INNER JOIN Categorie c ON c.Id = p.CategorieId
    LEFT JOIN Voorraad v ON v.ProductId = p.Id AND v.IsActief = 1
    WHERE p.IsActief = 1
      -- NULL of 0 betekent: geen filter, toon producten uit alle categorieën
      AND (p_CategorieId IS NULL OR p_CategorieId = 0 OR p.CategorieId = p_CategorieId)
    ORDER BY p.Id ASC;
END$$

DELIMITER ;
