db.laureates.aggregate([
    { $match : { familyName : { $exists : true } } },
    { $group : { _id : '$familyName', count : { $sum : { $size : '$nobelPrizes' } } } },
    { $match : { count : { $gte : 5 } } },
    { $project : { _id : 0, familyName : '$_id.en' } }
]);