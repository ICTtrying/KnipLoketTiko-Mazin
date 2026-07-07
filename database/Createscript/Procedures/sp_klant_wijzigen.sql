-- =============================================
-- Procedure: sp_klant_wijzigen
-- Doel: Werkt de naam- en contactgegevens van een klant bij (Klant + gekoppeld Contact).
-- Parameters: p_id INT - id van de klant, daarna de nieuwe waarden voor Klant en Contact,
--             p_succes BIT OUT - resultaatindicator, p_foutmelding VARCHAR(255) OUT - foutmelding
-- Return: geen resultset; vult p_succes en p_foutmelding
-- =============================================

-- Alle stringparameters krijgen expliciet de collation van de tabellen (utf8mb4_unicode_ci);
-- zonder deze duiding gebruikt MySQL 8 de database-default (utf8mb4_0900_ai_ci)
-- en falen stringvergelijkingen op een collation-conflict.
CREATE PROCEDURE sp_klant_wijzigen(
    IN p_id INT,
    IN p_voornaam VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_tussenvoegsel VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_achternaam VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_bijzonderheden VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_email VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_straatnaam VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_huisnummer SMALLINT,
    IN p_toevoeging VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_postcode VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_plaats VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_mobiel VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    OUT p_succes BIT,
    OUT p_foutmelding VARCHAR(255)
)
BEGIN
    DECLARE v_contact_id INT UNSIGNED;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_succes = 0;
        SET p_foutmelding = 'Klantgegevens zijn niet bijgewerkt';
    END;

    IF NOT EXISTS (SELECT 1 FROM Klant WHERE Id = p_id AND IsActief = 1) THEN
        SET p_succes = 0;
        SET p_foutmelding = 'Klant niet gevonden';
    ELSE
        START TRANSACTION;

        UPDATE Klant
        SET Voornaam = p_voornaam,
            Tussenvoegsel = p_tussenvoegsel,
            Achternaam = p_achternaam,
            -- Bijzonderheden is NOT NULL in het create-script; een leeg formulierveld wordt een lege string
            Bijzonderheden = COALESCE(p_bijzonderheden, ''),
            DatumGewijzigd = NOW(6)
        WHERE Id = p_id;

        SELECT kpc.ContactId
        INTO v_contact_id
        FROM KlantPerContact kpc
        WHERE kpc.KlantId = p_id
          AND kpc.IsActief = 1
        ORDER BY kpc.Id
        LIMIT 1;

        -- Contactgegevens alleen bijwerken als er een actieve contactkoppeling bestaat
        IF v_contact_id IS NOT NULL THEN
            UPDATE Contact
            SET Email = p_email,
                Straatnaam = p_straatnaam,
                Huisnummer = p_huisnummer,
                Toevoeging = p_toevoeging,
                Postcode = p_postcode,
                Plaats = p_plaats,
                Mobiel = p_mobiel,
                DatumGewijzigd = NOW()
            WHERE Id = v_contact_id;
        END IF;

        COMMIT;

        SET p_succes = 1;
        SET p_foutmelding = NULL;
    END IF;
END
