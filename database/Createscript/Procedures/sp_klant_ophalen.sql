-- =============================================
-- Procedure: sp_klant_ophalen
-- Doel: Haalt één actieve klant op met contactgegevens voor de detail- en wijzigpagina.
-- Parameters: p_id INT - id van de klant
-- Return: resultset met één rij: Id, Voornaam, Tussenvoegsel, Achternaam, Relatienummer,
--         Bijzonderheden, ContactId, Straatnaam, Huisnummer, Toevoeging, Postcode,
--         Plaats, Mobiel, Email (leeg als de klant niet bestaat of inactief is)
-- =============================================

CREATE PROCEDURE sp_klant_ophalen(IN p_id INT)
BEGIN
    SELECT
        k.Id,
        k.Voornaam,
        k.Tussenvoegsel,
        k.Achternaam,
        k.Relatienummer,
        k.Bijzonderheden,
        c.Id AS ContactId,
        c.Straatnaam,
        c.Huisnummer,
        c.Toevoeging,
        c.Postcode,
        c.Plaats,
        c.Mobiel,
        c.Email
    FROM Klant k
    -- LEFT JOIN zodat de detailpagina ook werkt voor een klant zonder contactkoppeling
    LEFT JOIN KlantPerContact kpc ON kpc.KlantId = k.Id AND kpc.IsActief = 1
    LEFT JOIN Contact c ON c.Id = kpc.ContactId AND c.IsActief = 1
    WHERE k.Id = p_id
      AND k.IsActief = 1
    LIMIT 1;
END
