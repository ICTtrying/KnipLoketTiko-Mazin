-- =============================================
-- Procedure: sp_bestelproduct_wijzigen
-- Doel: Wijzigt het aantal van een bestelproduct als de bestelling nog niet is afgeleverd.
-- Parameters: p_id INT - id van het bestelproduct, p_nieuw_aantal INT - nieuw aantal,
--             p_succes BIT OUT - resultaatindicator, p_foutmelding VARCHAR(255) OUT - foutmelding
-- Return: geen resultset; vult p_succes en p_foutmelding
-- =============================================
DELIMITER $$

CREATE PROCEDURE sp_bestelproduct_wijzigen(
    IN p_id INT,
    IN p_nieuw_aantal INT,
    OUT p_succes BIT,
    OUT p_foutmelding VARCHAR(255)
)
BEGIN
    DECLARE v_bestelstatus VARCHAR(30);

    SELECT b.Bestelstatus
    INTO v_bestelstatus
    FROM ProductPerBestelling ppb
    INNER JOIN Bestelling b ON b.Id = ppb.BestellingId
    WHERE ppb.Id = p_id
      AND ppb.IsActief = 1
      AND b.IsActief = 1
    LIMIT 1;

    IF v_bestelstatus IS NULL THEN
        SET p_succes = 0;
        SET p_foutmelding = 'Bestelproduct niet gevonden';
    ELSEIF v_bestelstatus = 'Afgeleverd' THEN
        SET p_succes = 0;
        -- Exacte meldingtekst uit wireframe-10, inclusief punt aan het einde
        SET p_foutmelding = 'Aantal kan niet worden gewijzigd omdat de bestelling al is afgeleverd.';
    ELSE
        UPDATE ProductPerBestelling
        SET Aantal = p_nieuw_aantal,
            DatumGewijzigd = NOW()
        WHERE Id = p_id;

        SET p_succes = 1;
        SET p_foutmelding = NULL;
    END IF;
END$$

DELIMITER ;