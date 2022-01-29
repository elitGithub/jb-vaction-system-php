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

router.route('/register(.html)?')
    .get()
    .post((req, res) =>  usersController.register(req, res));

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
