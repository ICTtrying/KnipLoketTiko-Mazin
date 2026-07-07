-- =============================================
-- Procedure: sp_producten_per_behandeling
-- Doel: Geeft alle actieve producten terug die bij een behandeling horen
--       (via de joins Behandeling -> BehandelingPerVoorraad -> Voorraad ->
--       Product), voor de pagina "Producten per behandeling" (User Story 06).
-- Parameters: p_behandeling_id INT UNSIGNED - Id van de behandeling
-- Return: resultset met ProductId, Naam, Merk, Omschrijving, EANcode,
--         AantalOpVoorraad, VerkoopPrijs
-- =============================================
DELIMITER $$

CREATE PROCEDURE sp_producten_per_behandeling(IN p_behandeling_id INT UNSIGNED)
BEGIN
    SELECT
        p.Id AS ProductId,
        p.Naam,
        p.Merk,
        p.Omschrijving,
        p.EANcode,
        v.AantalOpVoorraad,
        p.VerkoopPrijs
    FROM Behandeling b
    INNER JOIN BehandelingPerVoorraad bpv ON bpv.BehandelingId = b.Id AND bpv.IsActief = 1
    INNER JOIN Voorraad v ON v.Id = bpv.VoorraadId AND v.IsActief = 1
    INNER JOIN Product p ON p.Id = v.ProductId AND p.IsActief = 1
    WHERE b.Id = p_behandeling_id
      AND b.IsActief = 1
    ORDER BY p.DatumAangemaakt DESC;
END$$

DELIMITER ;
