const User = require('../models/User');
const logEvents = require('../middleware/logEvents');
const register = async (params) => {
    try {
        return await User.create({
            userName: params.email,
            password: params.password,
            firstName: params.firstName,
            lastName: params.lastName,
            isAdmin: false,
            loggedIn: true
        });
    } catch (e) {
        logEvents.customEmitter.emit('error', e);
        return false;
    }
};

const findUserByEmail = async (searchParams) => {
    try {
        return await User.find({ userName: searchParams.userName });
    } catch (e) {
        logEvents.customEmitter.emit('error', e);
        return false;
    }
};

const listUsers = async () => {
    try {
        const users = await User.find({});
        console.log(users);
        const userMap = {};
        users.forEach((user) => {
            console.log(user);
            userMap[user._id] = user;
        });
        return await User.find({});
    } catch (e) {
        logEvents.customEmitter.emit('error', e);
        return false;
    }
};

module.exports = { register, findUserByEmail, listUsers };
