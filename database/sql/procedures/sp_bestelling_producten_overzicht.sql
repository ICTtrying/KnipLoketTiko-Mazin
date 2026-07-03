-- =============================================
-- Procedure: sp_bestelling_producten_overzicht
-- Doel: Geeft alle productregels van een bestelling terug, inclusief product-, categorie-
--       en bestelgegevens en het berekende totaal per regel.
-- Parameters: p_bestelling_id INT - id van de bestelling
-- Return: resultset met BestellingId, BestelNummer, Bestelstatus, KlantNaam, Relatienummer,
--         ProductPerBestellingId, ProductId, ProductNaam, CategorieNaam, Merk, Aantal,
--         UnitPrijs, BTWPercentage, Korting, RegelTotaal
-- =============================================

CREATE PROCEDURE sp_bestelling_producten_overzicht(IN p_bestelling_id INT)
BEGIN
    SELECT
        b.Id AS BestellingId,
        b.BestelNummer,
        b.Bestelstatus,
        k.Naam AS KlantNaam,
        CONCAT('KL-2026-', LPAD(k.Id, 3, '0')) AS Relatienummer,
        ppb.Id AS ProductPerBestellingId,
        p.Id AS ProductId,
        p.Naam AS ProductNaam,
        c.Naam AS CategorieNaam,
        p.Merk,
        ppb.Aantal,
        ppb.UnitPrijs,
        ppb.BTWPercentage,
        ppb.Korting,
        (ppb.UnitPrijs * ppb.Aantal * (1 - ppb.Korting / 100) * (1 + ppb.BTWPercentage / 100)) AS RegelTotaal
    FROM ProductPerBestelling ppb
    INNER JOIN Bestelling b ON b.Id = ppb.BestellingId
    INNER JOIN Klant k ON k.Id = b.KlantId
    INNER JOIN Product p ON p.Id = ppb.ProductId
    INNER JOIN Categorie c ON c.Id = p.CategorieId
    WHERE ppb.IsActief = 1
      AND b.IsActief = 1
      AND b.Id = p_bestelling_id
    ORDER BY p.Naam;
END

