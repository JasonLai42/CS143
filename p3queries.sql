SELECT id FROM Laureate WHERE givenName="Marie" AND familyName="Curie";

SELECT country FROM Institution WHERE affiliationName="CERN";

SELECT fNames.familyName 
FROM (SELECT familyName FROM Laureate INNER JOIN Awarded ON Laureate.id=Awarded.id) AS fNames 
GROUP BY fNames.familyName 
HAVING COUNT(fNames.familyName) >= 5;

SELECT COUNT(*) FROM Institution WHERE affiliationName="University of California";

SELECT COUNT(DISTINCT fYears.awardYear)
FROM (SELECT awardYear, orgName FROM Laureate INNER JOIN Awarded ON Laureate.id=Awarded.id) AS fYears 
WHERE fYears.orgName IS NOT NULL;