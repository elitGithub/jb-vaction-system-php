const express = require('express');
const path = require('path');
const app = express();
const cors = require('cors');
const port = 3006;
const logEvents = require('./logEvents');
app.use(cors());

app.use(express.static(path.join(__dirname, '../build/')));

app.get('^/$|/index(.html)?', (req, res) => {
    res.sendFile('index.html');
});

app.get('/vacation-list(.html)?', (req, res) => {
    res.sendFile('index.html');
});

app.post('/login(.html)?', (req, res) => {
    logEvents.emit('log', `Login`);
    res.send(JSON.stringify({'success': true, 'message': 'logged in'}));
});

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
});

