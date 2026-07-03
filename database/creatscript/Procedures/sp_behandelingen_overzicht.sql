-- =============================================
-- Procedure: sp_behandelingen_overzicht
-- Doel: Geeft alle actieve behandelingen terug met het aantal gekoppelde
--       producten (via de joins Behandeling -> BehandelingPerVoorraad ->
--       Voorraad -> Product), optioneel gefilterd op behandelnaam.
-- Parameters: p_naam VARCHAR(100) - exacte behandelnaam om op te filteren,
--             of NULL/'Alle behandelingen' voor geen filter
-- Return: resultset met BehandelingId, Naam, Omschrijving, DuurMinuten,
--         Prijs, AantalProducten
-- =============================================
DELIMITER $$

-- De COLLATE-clausule voorkomt een collation-conflict: de tabellen gebruiken
-- utf8mb4_unicode_ci, terwijl MySQL 8 parameters standaard utf8mb4_0900_ai_ci geeft
CREATE PROCEDURE sp_behandelingen_overzicht(IN p_naam VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci)
BEGIN
    SELECT
        b.Id AS BehandelingId,
        b.Naam,
        b.Omschrijving,
        b.DuurMinuten,
        b.Prijs,
        COUNT(p.Id) AS AantalProducten
    FROM Behandeling b
    LEFT JOIN BehandelingPerVoorraad bpv ON bpv.BehandelingId = b.Id AND bpv.IsActief = 1
    LEFT JOIN Voorraad v ON v.Id = bpv.VoorraadId AND v.IsActief = 1
    LEFT JOIN Product p ON p.Id = v.ProductId AND p.IsActief = 1
    WHERE b.IsActief = 1
      -- NULL of 'Alle behandelingen' betekent: geen filter, toon alle behandelingen
      AND (p_naam IS NULL OR p_naam = 'Alle behandelingen' OR b.Naam = p_naam)
    GROUP BY b.Id, b.Naam, b.Omschrijving, b.DuurMinuten, b.Prijs
    ORDER BY b.Id ASC;
END$$

DELIMITER ;
