-- =============================================
-- Procedure: sp_product_detail
-- Doel: Geeft de detailgegevens van één actief product terug voor de
--       productdetailpagina (User Story 06), inclusief actuele voorraad en
--       de leveranciergegevens van de meest recente actieve leveranciersorder
--       (bepaald op laatste Orderdatum).
--       Joins: LEFT JOIN Voorraad, LEFT JOIN LeverancierOrder + Leverancier.
-- Parameters: p_product_id INT UNSIGNED - Id van het op te halen product
-- Return: resultset met ProductId, Naam, Merk, Omschrijving, EANcode,
--         Houdbaarheidsdatum, InkoopPrijs, VerkoopPrijs, AantalOpVoorraad,
--         LeverancierNaam, LeverancierPostcode, LeverancierPlaats,
--         LeverancierEmail, LeverancierMobiel, Opmerking
-- =============================================
DELIMITER $$

CREATE PROCEDURE sp_product_detail(IN p_product_id INT UNSIGNED)
BEGIN
    SELECT
        p.Id AS ProductId,
        p.Naam,
        p.Merk,
        p.Omschrijving,
        p.EANcode,
        p.Houdbaarheidsdatum,
        p.InkoopPrijs,
        p.VerkoopPrijs,
        IFNULL(v.AantalOpVoorraad, 0) AS AantalOpVoorraad,
        l.Naam AS LeverancierNaam,
        l.Postcode AS LeverancierPostcode,
        l.Plaats AS LeverancierPlaats,
        l.Email AS LeverancierEmail,
        l.Mobiel AS LeverancierMobiel,
        p.Opmerking
    FROM Product p
    LEFT JOIN Voorraad v ON v.ProductId = p.Id AND v.IsActief = 1
    -- Alleen de meest recente actieve order (laatste Orderdatum) bepaalt de
    -- getoonde leverancier, zodat meerdere orders niet tot dubbele rijen leiden
    LEFT JOIN LeverancierOrder lo ON lo.ProductId = p.Id
        AND lo.IsActief = 1
        AND lo.Id = (
            SELECT lo2.Id
            FROM LeverancierOrder lo2
            WHERE lo2.ProductId = p.Id
              AND lo2.IsActief = 1
            ORDER BY lo2.Orderdatum DESC, lo2.Id DESC
            LIMIT 1
        )
    LEFT JOIN Leverancier l ON l.Id = lo.LeverancierId AND l.IsActief = 1
    WHERE p.Id = p_product_id
      AND p.IsActief = 1;
END$$

DELIMITER ;
