const express = require('express');
const path = require('path');
const app = express();
const cors = require('cors');
const port = 3006;
const logEvents = require('./logEvents');
app.use(cors());

app.use(express.static(path.join(__dirname, '../build/')));
app.get('/', (req, res) => {
    res.sendFile('index.html');
});

app.post('/login', (req, res) => {
    logEvents.emit('log', `Example app listening at http://localhost:${port}`);
    res.send(JSON.stringify({'success': true, 'message': 'logged in'}));

});

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
});

/**
 * mongodb root pass: VHeAHDLVqE7dteEw
 * user root
 *
 *
 *  todo:
 *
 *  set up the server and the connection to db
 *  set up a basic crud operation setup for the server
 *  set up the auth and users db
 *  connect the front to the backend.
 */