db.laureates.find({ givenName : { en : 'Marie', se : 'Marie' }, familyName : { en : 'Curie', se : 'Curie' } }, { _id : 0, id : 1 });