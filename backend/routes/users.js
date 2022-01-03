const express = require('express');
const router = express.Router();
const path = require('path');
const usersController = require("../controllers/usersController");


router.route('/login(.html)?')
    .get((req, res) => {
       res.json({'hu':'huhu'});
    })
    .post((req, res) => {
        console.log(req.body);
        res.json({ 'success': true, 'message': 'logged in' });
    });

router.post('/register(.html)?', async (req, res) => {
    const result = await usersController.register(req.body);
    res.json({ 'success': !!result, 'message': 'user saved' });
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