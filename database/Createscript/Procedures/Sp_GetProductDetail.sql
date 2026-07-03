-- =============================================
-- Procedure: GetProductDetail
-- Doel: Geeft de detailgegevens van één actief product terug voor de
--       productdetailpagina (User Story 08), inclusief actuele voorraad en
--       de leveranciergegevens van de meest recente actieve leveranciersorder.
--       Joins: LEFT JOIN Voorraad (niet elk product hoeft een voorraadregel te
--       hebben), LEFT JOIN LeverancierOrder + Leverancier (niet elk product
--       hoeft een leveranciersorder te hebben).
-- Parameters: p_ProductId INT UNSIGNED - Id van het op te halen product
-- Return: resultset met Id, Naam, Merk, Omschrijving, EANcode,
--         Houdbaarheidsdatum, InkoopPrijs, VerkoopPrijs, AantalOpVoorraad,
--         LeverancierNaam, LeverancierPostcode, LeverancierPlaats,
--         LeverancierEmail, LeverancierMobiel, Opmerking
-- =============================================
DELIMITER $$

CREATE PROCEDURE GetProductDetail(IN p_ProductId INT UNSIGNED)
BEGIN
    SELECT
        p.Id,
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
    -- Alleen de meest recente actieve order bepaalt de getoonde leverancier,
    -- zodat een product met meerdere orders niet tot dubbele rijen leidt
    LEFT JOIN LeverancierOrder lo ON lo.ProductId = p.Id
        AND lo.IsActief = 1
        AND lo.Id = (
            SELECT MAX(lo2.Id)
            FROM LeverancierOrder lo2
            WHERE lo2.ProductId = p.Id
              AND lo2.IsActief = 1
        )
    LEFT JOIN Leverancier l ON l.Id = lo.LeverancierId AND l.IsActief = 1
    WHERE p.Id = p_ProductId
      AND p.IsActief = 1;
END$$

DELIMITER ;
