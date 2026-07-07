-- =============================================
-- Procedure: sp_product_verkoopprijs_bijwerken
-- Doel: Werkt de verkoopprijs en opmerking van een product bij (User Story 06)
--       en zet DatumGewijzigd op NOW().
--       Businessregel (defense in depth, naast de Laravel-validatie): de nieuwe
--       verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen;
--       anders gooit de procedure een gecontroleerde fout via SIGNAL en wordt
--       er niets bijgewerkt.
-- Parameters: p_product_id INT UNSIGNED - Id van het product,
--             p_nieuwe_verkoopprijs DECIMAL(6,2) - de nieuwe verkoopprijs,
--             p_nieuwe_opmerking VARCHAR(255) - de nieuwe opmerking (mag NULL zijn)
-- Return: geen resultset; gooit SQLSTATE 45000 bij een ongeldige prijs
-- =============================================
DELIMITER $$

CREATE PROCEDURE sp_product_verkoopprijs_bijwerken(
    IN p_product_id INT UNSIGNED,
    IN p_nieuwe_verkoopprijs DECIMAL(6,2),
    IN p_nieuwe_opmerking VARCHAR(255)
)
BEGIN
    DECLARE v_inkoopprijs DECIMAL(6,2);

    SELECT p.InkoopPrijs
    INTO v_inkoopprijs
    FROM Product p
    WHERE p.Id = p_product_id
      AND p.IsActief = 1
    LIMIT 1;

    IF v_inkoopprijs IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Product niet gevonden';
    END IF;

    IF p_nieuwe_verkoopprijs < v_inkoopprijs * 1.30 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen';
    END IF;

    UPDATE Product
    SET VerkoopPrijs = p_nieuwe_verkoopprijs,
        Opmerking = p_nieuwe_opmerking,
        DatumGewijzigd = NOW()
    WHERE Id = p_product_id;
    
END$$

DELIMITER ;
