require('./db/mongoose');
const express = require('express');
const path = require('path');
const app = express();
const cors = require('cors');
const port = process.env.PORT || 3006;
const logEvents = require('./logEvents');
const usersController = require('./controllers/usersController');
app.use(cors());
app.use(express.urlencoded({extended: true}));
app.use(express.json());
app.use(express.static(path.join(__dirname, '../build/')));



app.get('^/$|/index(.html)?', (req, res) => {
    res.sendFile('index.html');
});

app.get('/vacation-list(.html)?', (req, res) => {
    res.sendFile('index.html');
});

app.post('/login(.html)?', (req, res) => {
    // logEvents.emit('log', `Login`);
    console.log(req.body.json());
    res.json({ 'success': true, 'message': 'logged in' });
    //res.send(JSON.stringify({ 'success': true, 'message': 'logged in' }));
});

app.post('/register(.html)?', async (req, res) => {
    const result = await usersController.register(req.body);
    res.json({ 'success': !!result, 'message': 'user saved' });
    res.end();
});

app.post('/users-list(.html)?', async (req, res) => {
    const result = await usersController.listUsers(req.body);
    res.json({ 'success': result, 'message': '', data: result });
    res.end();
});

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${ port }`)
});
