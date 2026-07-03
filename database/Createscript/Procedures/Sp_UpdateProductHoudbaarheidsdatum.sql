-- =============================================
-- Procedure: UpdateProductHoudbaarheidsdatum
-- Doel: Wijzigt de houdbaarheidsdatum van een product (User Story 08).
--       Businessregel: de houdbaarheidsdatum mag met maximaal 7 dagen worden
--       verlengd ten opzichte van de huidige houdbaarheidsdatum.
-- Parameters: p_ProductId INT UNSIGNED - Id van het product,
--             p_NieuweDatum DATE - de nieuwe houdbaarheidsdatum,
--             p_Succes BIT OUT - resultaatindicator,
--             p_Foutmelding VARCHAR(255) OUT - foutmelding voor de gebruiker
-- Return: geen resultset; vult p_Succes en p_Foutmelding
-- =============================================
DELIMITER $$

CREATE PROCEDURE UpdateProductHoudbaarheidsdatum(
    IN p_ProductId INT UNSIGNED,
    IN p_NieuweDatum DATE,
    OUT p_Succes BIT,
    OUT p_Foutmelding VARCHAR(255)
)
BEGIN
    DECLARE v_HuidigeDatum DATE;

    SELECT p.Houdbaarheidsdatum
    INTO v_HuidigeDatum
    FROM Product p
    WHERE p.Id = p_ProductId
      AND p.IsActief = 1
    LIMIT 1;

    IF v_HuidigeDatum IS NULL THEN
        SET p_Succes = 0;
        SET p_Foutmelding = 'Product niet gevonden';
    ELSEIF DATEDIFF(p_NieuweDatum, v_HuidigeDatum) > 7 THEN
        SET p_Succes = 0;
        SET p_Foutmelding = 'De houdbaarheidsdatum is met meer dan 7 dagen verlengd.';
    ELSE
        UPDATE Product
        SET Houdbaarheidsdatum = p_NieuweDatum,
            DatumGewijzigd = NOW()
        WHERE Id = p_ProductId;

        SET p_Succes = 1;
        SET p_Foutmelding = NULL;
    END IF;
END$$

DELIMITER ;
