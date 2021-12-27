require('./db/mongoose');
const express = require('express');
const path = require('path');
const app = express();
const cors = require('cors');
const port = process.env.PORT || 3006;
const logEvents = require('./middleware/logEvents');
const usersController = require('./controllers/usersController');


app.use(cors());
app.use(express.urlencoded({extended: true}));
app.use(express.json());

app.use((req, res, next) => {
    logEvents.emit('log', `Method: ${req.method}, path: ${req.path}` );
    next();
});

app.use(express.static(path.join(__dirname, '../build/')));



app.get('^/$|/index(.html)?', (req, res) => {
    res.sendFile('index.html');
});

app.get('/vacation-list(.html)?', (req, res) => {
    res.sendFile('index.html');
});

app.post('/login(.html)?', (req, res) => {
    // logEvents.emit('log', `Login`);
    console.log(req.body);
    res.json({ 'success': true, 'message': 'logged in' });
    //res.send(JSON.stringify({ 'success': true, 'message': 'logged in' }));
});

app.post('/register(.html)?', async (req, res) => {
    const result = await usersController.register(req.body);
    res.json({ 'success': !!result, 'message': 'user saved' });
    res.end();
});

app.post('/find-user(.html)?', async (req, res) => {
    const result = await usersController.findUser(req.body);
    res.json({ 'success': !!result, 'message': '', data: result });
    res.end();
});
app.get('/users-list(.html)?', async (req, res) => {
    const result = await usersController.listUsers();
    console.log(result);
    res.json({ 'success': !!result, 'message': '', data: result });
    res.end();
});

app.get('/*', (req, res) => {
    res.redirect('/');
});

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${ port }`)
});
