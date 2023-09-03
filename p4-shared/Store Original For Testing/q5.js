db.laureates.aggregate([
    { $match : { orgName : { $exists : true } } },
    { $unwind : '$nobelPrizes' },
    { $group : { _id : { years : '$nobelPrizes.awardYear' } } },
    { $count : 'years' }
]);