-- =============================================
-- Procedure: sp_bestelproduct_ophalen
-- Doel: Haalt alle gegevens op die nodig zijn voor het wijzigformulier van een bestelproduct.
-- Parameters: p_product_per_bestelling_id INT - id van het bestelproduct
-- Return: resultset met BestellingId, BestelNummer, Bestelstatus, KlantNaam, Relatienummer,
--         ProductPerBestellingId, ProductNaam, CategorieNaam, Merk, UnitPrijs, Aantal
-- =============================================
DELIMITER $$

CREATE PROCEDURE sp_bestelproduct_ophalen(IN p_product_per_bestelling_id INT)
BEGIN
    SELECT
        b.Id AS BestellingId,
        b.BestelNummer,
        b.Bestelstatus,
        -- Weergavenaam samenstellen: Klant heeft geen Naam-kolom; CONCAT_WS slaat NULL-tussenvoegsels over
        CONCAT_WS(' ', k.Voornaam, k.Tussenvoegsel, k.Achternaam) AS KlantNaam,
        -- Relatienummer bestaat als kolom en wordt rechtstreeks gebruikt (niet berekend)
        k.Relatienummer,
        ppb.Id AS ProductPerBestellingId,
        p.Naam AS ProductNaam,
        c.Naam AS CategorieNaam,
        p.Merk,
        ppb.UnitPrijs,
        ppb.Aantal
    FROM ProductPerBestelling ppb
    INNER JOIN Bestelling b ON b.Id = ppb.BestellingId
    INNER JOIN Klant k ON k.Id = b.KlantId
    INNER JOIN Product p ON p.Id = ppb.ProductId
    INNER JOIN Categorie c ON c.Id = p.CategorieId
    WHERE ppb.Id = p_product_per_bestelling_id
      AND ppb.IsActief = 1
      AND b.IsActief = 1
    LIMIT 1;
END$$

DELIMITER ;