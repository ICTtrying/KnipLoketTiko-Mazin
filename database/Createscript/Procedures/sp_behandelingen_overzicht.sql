-- =============================================
-- Procedure: sp_behandelingen_overzicht
-- Doel: Geeft alle behandelingen terug, optioneel gefilterd op IsActief status.
-- Parameters: p_status VARCHAR(30) - filterwaarde ('1' voor actief, '0' voor inactief, of NULL/'Alle behandelingen' voor geen filter)
-- Return: resultset met BestellingId (Id), BestelNummer (Naam), KlantNaam (Omschrijving), 
--         Relatienummer (Duur), Datum (DatumAangemaakt), Tijd (DatumGewijzigd), 
--         Bestelstatus (IsActief), AantalProducten (Duur), Totaal (Prijs)
-- =============================================


CREATE PROCEDURE sp_behandelingen_overzicht(IN p_status VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci)
BEGIN
    SELECT
        b.Id AS BestellingId,          -- Gemapped naar BestellingId voor de view
        b.Naam AS BestelNummer,        -- Gemapped naar BestelNummer voor de view
        b.Omschrijving AS KlantNaam,   -- Gemapped naar KlantNaam voor de view
        b.DuurMinuten AS Relatienummer,-- Gemapped naar Relatienummer voor de view
        b.DatumAangemaakt AS Datum,
        b.DatumGewijzigd AS Tijd,
        b.IsActief AS Bestelstatus,    -- Gemapped naar Bestelstatus (0 of 1) voor de view
        b.DuurMinuten AS AantalProducten,
        b.Prijs AS Totaal
    FROM Behandeling b
    WHERE 
        (
            p_status IS NULL 
            OR p_status = 'Alle behandelingen' 
            -- Omdat p_status een VARCHAR is en IsActief een BIT, casten of vergelijken we op basis van de stringwaarde ('1' of '0')
            OR (p_status = '1' AND b.IsActief = 1)
            OR (p_status = '0' AND b.IsActief = 0)
        )
    ORDER BY b.Naam ASC;
END 
