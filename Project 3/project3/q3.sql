SELECT fNames.familyName 
FROM (SELECT familyName FROM Laureate INNER JOIN Awarded ON Laureate.id=Awarded.id) AS fNames 
GROUP BY fNames.familyName 
HAVING COUNT(fNames.familyName) >= 5;
