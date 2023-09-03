SELECT COUNT(DISTINCT fYears.awardYear)
FROM (SELECT awardYear, orgName FROM Laureate INNER JOIN Awarded ON Laureate.id=Awarded.id) AS fYears 
WHERE fYears.orgName IS NOT NULL;
