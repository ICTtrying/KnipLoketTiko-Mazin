-- =============================================
-- Procedure: sp_bestellingen_overzicht
-- Doel: Geeft alle bestellingen terug met klantnaam, aantal producten en totaalbedrag,
--       optioneel gefilterd op Bestelstatus.
-- Parameters: p_status VARCHAR(30) - filterwaarde, of NULL/'Alle statussen' voor geen filter
-- Return: resultset met BestellingId, BestelNummer, KlantNaam, Relatienummer, Datum, Tijd,
--         Bestelstatus, AantalProducten, Totaal
-- =============================================
DELIMITER $$

CREATE PROCEDURE sp_bestellingen_overzicht(IN p_status VARCHAR(30))
BEGIN
    SELECT
        b.Id AS BestellingId,
        b.BestelNummer,
        k.Naam AS KlantNaam,
        CONCAT('KL-2026-', LPAD(k.Id, 3, '0')) AS Relatienummer,
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
    GROUP BY b.Id, b.BestelNummer, k.Naam, k.Id, b.Datum, b.Tijd, b.Bestelstatus
    ORDER BY b.Datum DESC, b.Tijd DESC;
END$$

DELIMITER ;