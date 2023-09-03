// import fs module to read/write file
const fs = require('fs');

// load JSON data
let file = fs.readFileSync("/home/cs143/data/nobel-laureates.json");
let data = JSON.parse(file);

// create import file
var path = "laureates.import";
if(fs.existsSync(path)) {
        try {
                fs.unlinkSync(path);
        } catch(err) {
                console.error(err);
        }
}
var stream = fs.createWriteStream(path, {flags:'a'});

// write to import file
var index;
for(index = 0; index < data.laureates.length; index++) {
        stream.write(JSON.stringify(data.laureates[index]) + "\n");
}
stream.end();