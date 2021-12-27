const User = require('../models/User');
const logEvents = require('../logEvents');
const register = async (params) => {
    try {
        const user = await User.create({
            userName: params.userName,
            password: params.password,
            firstName: params.firstName,
            lastName: params.lastName
        });
        return user.id;
    } catch (e) {
        logEvents.emit('error', e);
        return false;
    }
};

const listUsers = async (searchParams) => {
    try {
        const user = await User.find({ searchParams });
        console.log('user', user);
        return await user;
    } catch (e) {
        logEvents.emit('error', e);
        return false;
    }
};

module.exports = { register, listUsers }
