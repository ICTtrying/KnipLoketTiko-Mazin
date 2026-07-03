-- =============================================
-- Procedure: sp_bestellingen_overzicht
-- Doel: Geeft alle bestellingen terug met klantnaam, aantal producten en totaalbedrag,
--       optioneel gefilterd op Bestelstatus.
-- Parameters: p_status VARCHAR(30) - filterwaarde, of NULL/'Alle statussen' voor geen filter
-- Return: resultset met BestellingId, BestelNummer, KlantNaam, Relatienummer, Datum, Tijd,
--         Bestelstatus, AantalProducten, Totaal
-- =============================================
DELIMITER $$

-- De parameter krijgt expliciet de collation van de tabellen (utf8mb4_unicode_ci);
-- zonder deze duiding gebruikt MySQL 8 de database-default (utf8mb4_0900_ai_ci)
-- en faalt de vergelijking met Bestelling.Bestelstatus op een collation-conflict.
CREATE PROCEDURE sp_bestellingen_overzicht(IN p_status VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci)
BEGIN
    SELECT
        b.Id AS BestellingId,
        b.BestelNummer,
        -- Weergavenaam samenstellen: Klant heeft geen Naam-kolom; CONCAT_WS slaat NULL-tussenvoegsels over
        CONCAT_WS(' ', k.Voornaam, k.Tussenvoegsel, k.Achternaam) AS KlantNaam,
        -- Relatienummer bestaat als kolom en wordt rechtstreeks gebruikt (niet berekend)
        k.Relatienummer,
        b.Datum,
        b.Tijd,
        b.Bestelstatus,
        COUNT(ppb.Id) AS AantalProducten,
        COALESCE(SUM(ppb.UnitPrijs * ppb.Aantal * (1 - ppb.Korting / 100) * (1 + ppb.BTWPercentage / 100)), 0) AS Totaal
    FROM Bestelling b
    INNER JOIN Klant k ON k.Id = b.KlantId
    LEFT JOIN ProductPerBestelling ppb ON ppb.BestellingId = b.Id AND ppb.IsActief = 1
    WHERE b.IsActief = 1
      AND (p_status IS NULL OR p_status = 'Alle statussen' OR b.Bestelstatus = p_status)
    GROUP BY b.Id, b.BestelNummer, k.Voornaam, k.Tussenvoegsel, k.Achternaam, k.Relatienummer, b.Datum, b.Tijd, b.Bestelstatus
    ORDER BY b.Datum DESC, b.Tijd DESC;
END$$

DELIMITER ;