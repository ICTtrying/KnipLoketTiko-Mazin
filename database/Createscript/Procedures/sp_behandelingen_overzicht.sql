DROP PROCEDURE IF EXISTS sp_behandelingen_overzicht;

CREATE PROCEDURE sp_behandelingen_overzicht(IN p_status VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci)
BEGIN
    SELECT
        b.Id AS BehandelingId,
        b.Naam,
        b.Omschrijving,   
        b.DuurMinuten,
        b.Prijs,
        b.IsActief,
        b.Opmerking,
        b.DatumAangemaakt,
        b.DatumGewijzigd,
        COALESCE(bpv.AantalProducten, 0) AS AantalProducten
    FROM Behandeling b
    LEFT JOIN (
        SELECT BehandelingId, COUNT(*) AS AantalProducten
        FROM BehandelingPerVoorraad
        WHERE IsActief = 1
        GROUP BY BehandelingId
    ) bpv ON b.Id = bpv.BehandelingId
    WHERE 
        (
            p_status IS NULL 
            OR p_status = 'Alle behandelingen' 
            OR (p_status = '1' AND b.IsActief = 1)
            OR (p_status = '0' AND b.IsActief = 0)
        )
    ORDER BY b.Naam ASC;
END