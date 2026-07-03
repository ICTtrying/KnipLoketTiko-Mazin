-- ==========================================
-- STORED PROCEDURES VOOR KLANTEN
-- ==========================================

-- Procedure 1: Get all klanten with contact info (3+ JOINS)
DELIMITER //

CREATE PROCEDURE sp_get_klanten(
    IN p_postcode VARCHAR(10)
)
BEGIN
    SELECT 
        k.Id,
        k.UserId,
        k.Voornaam,
        k.Tussenvoegsel,
        k.Achternaam,
        k.Relatienummer,
        k.Bijzonderheden,
        u.email,
        c.Straatnaam,
        c.Huisnummer,
        c.Toevoeging,
        c.Postcode,
        c.Plaats,
        c.Email AS ContactEmail,
        c.Mobiel
    FROM Klant k
    INNER JOIN users u ON k.UserId = u.Id
    INNER JOIN KlantPerContact kpc ON k.Id = kpc.KlantId
    INNER JOIN Contact c ON kpc.ContactId = c.Id
    WHERE k.IsActief = 1
        AND (p_postcode IS NULL OR p_postcode = '' OR c.Postcode = p_postcode)
    ORDER BY k.Voornaam ASC;
END//

DELIMITER ;

-- Procedure 2: Get single klant by ID with all details
DELIMITER //

CREATE PROCEDURE sp_get_klant_by_id(
    IN p_klant_id INT
)
BEGIN
    SELECT 
        k.Id,
        k.UserId,
        k.Voornaam,
        k.Tussenvoegsel,
        k.Achternaam,
        k.Relatienummer,
        k.Bijzonderheden,
        u.email,
        c.Id AS ContactId,
        c.Straatnaam,
        c.Huisnummer,
        c.Toevoeging,
        c.Postcode,
        c.Plaats,
        c.Email AS ContactEmail,
        c.Mobiel
    FROM Klant k
    INNER JOIN users u ON k.UserId = u.Id
    INNER JOIN KlantPerContact kpc ON k.Id = kpc.KlantId
    INNER JOIN Contact c ON kpc.ContactId = c.Id
    WHERE k.Id = p_klant_id
        AND k.IsActief = 1
    LIMIT 1;
END//

DELIMITER ;

-- Procedure 3: Update klant contact email
DELIMITER //

CREATE PROCEDURE sp_update_klant_email(
    IN p_contact_id INT,
    IN p_new_email VARCHAR(255),
    OUT p_success BIT,
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SET p_success = 0;
        SET p_message = 'Database error occurred';
    END;

    -- Check if email already exists in Contact table
    IF EXISTS (SELECT 1 FROM Contact WHERE Email = p_new_email AND Id != p_contact_id) THEN
        SET p_success = 0;
        SET p_message = 'Email already in use';
    ELSE
        -- Update the contact email
        UPDATE Contact 
        SET Email = p_new_email,
            DatumGewijzigd = NOW()
        WHERE Id = p_contact_id;

        IF ROW_COUNT() > 0 THEN
            SET p_success = 1;
            SET p_message = 'Success';
        ELSE
            SET p_success = 0;
            SET p_message = 'No record updated';
        END IF;
    END IF;
END//

DELIMITER ;