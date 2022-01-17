const express = require('express');
const router = express.Router();
const path = require('path');
const usersController = require("../controllers/usersController");

router.route('/login(.html)?')
    .post(async (req, res) => {
        // const data = await usersController.findUserByEmail({userName: req.body.username, password: req.body.password});
        // const success = !!data;
        // const message = success ? '' : 'An error has occurred';
        res.json({ success: true, message: '', data: [] });
        res.end();
    });

router.post('/register(.html)?', async (req, res) => {
    const userExists = await usersController.findUserByEmail({userName: req.body.email});
    const userExistsSuccess = !!userExists;
    const errMessage = userExistsSuccess ? 'User Already exists' : '';
    if (userExistsSuccess) {
        res.json({ success: false, message: errMessage, data: [] });
        res.end();
        return;
    }
    const data = await usersController.register(req.body);
    const success = !!data;
    const message = success ? 'User saved' : 'Error Occurred during registration';
    res.json({ success, message, data });
    res.end();
});

router.post('/find-user(.html)?', async (req, res) => {
    const result = await usersController.findUserByEmail(req.body);
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
