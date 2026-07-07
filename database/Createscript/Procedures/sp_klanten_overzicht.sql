-- =============================================
-- Procedure: sp_klanten_overzicht
-- Doel: Geeft alle actieve klanten terug met hun contactgegevens,
--       optioneel gefilterd op (een deel van) de postcode.
-- Parameters: p_postcode VARCHAR(10) - filterwaarde, of NULL/'' voor geen filter
-- Return: resultset met Id, Voornaam, Tussenvoegsel, Achternaam, Relatienummer,
--         Straatnaam, Huisnummer, Toevoeging, Postcode, Plaats, Mobiel, Email
-- =============================================

-- De parameter krijgt expliciet de collation van de tabellen (utf8mb4_unicode_ci);
-- zonder deze duiding gebruikt MySQL 8 de database-default (utf8mb4_0900_ai_ci)
-- en faalt de vergelijking met Contact.Postcode op een collation-conflict.
CREATE PROCEDURE sp_klanten_overzicht(IN p_postcode VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci)
BEGIN
    SELECT
        k.Id,
        k.Voornaam,
        k.Tussenvoegsel,
        k.Achternaam,
        k.Relatienummer,
        c.Straatnaam,
        c.Huisnummer,
        c.Toevoeging,
        c.Postcode,
        c.Plaats,
        c.Mobiel,
        c.Email
    FROM Klant k
    -- LEFT JOIN zodat een klant zonder (actieve) contactkoppeling toch in het overzicht staat
    LEFT JOIN KlantPerContact kpc ON kpc.KlantId = k.Id AND kpc.IsActief = 1
    LEFT JOIN Contact c ON c.Id = kpc.ContactId AND c.IsActief = 1
    WHERE k.IsActief = 1
      AND (p_postcode IS NULL OR p_postcode = '' OR c.Postcode LIKE CONCAT('%', p_postcode, '%'))
    ORDER BY k.Id;
END
