const express = require('express');
const router = express.Router();
const path = require('path');

router.get('/vacation-list(.html)?', (req, res) => {
    res.sendFile('index.html');
});


module.exports = router;