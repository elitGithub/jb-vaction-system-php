const express = require('express');
const router = express.Router();
const path = require('path');
const usersController = require("../controllers/usersController");

router.route('/login4(.html)?')
    .post((req, res) => {
        res.json({ success: true, message: '', data: {name: 'John Doe', email: 'John@doe'} });
        res.end();
    });


router.route('/login(.html)?')
    .post(async (req, res) => {
        const data = await usersController.findUser({userName: req, password: req.password});
        const success = !!data;
        const message = success ? '' : 'An error has occurred';
        res.json({ success, message, data });
        res.end();
    });

router.post('/register(.html)?', async (req, res) => {
    const data = await usersController.register(req.body);
    const success = !!data;
    const message = success ? 'User saved' : 'Error Occurred during registration';
    res.json({ success, message, data });
    res.end();
});

router.post('/find-user(.html)?', async (req, res) => {
    const result = await usersController.findUser(req.body);
    res.json({ 'success': !!result, 'message': '', data: result });
    res.end();
});
router.get('/users-list(.html)?', async (req, res) => {
    const result = await usersController.listUsers();
    console.log(result);
    res.json({ 'success': !!result, 'message': '', data: result });
    res.end();
});



module.exports = router;
