const User =  require('../models/User');
const logEvents = require('../logEvents');
const register = async (params) => {
    console.log(params);
    return true;
    /*
    const user = new User({userName: 'we@are.pirates', password: 'arrrrrr', firstName: 'Monkey D', lastName: 'Luffy'});
    try {
        await user.save();
    } catch (e) {
        logEvents.emit('error', e.message());
        return false;
    }*/
};

module.exports = { register, }