const User = require('../models/User');
const logEvents = require('../middleware/logEvents');
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

const findUser = async (searchParams) => {
    try {
        return await User.find(searchParams);
    } catch (e) {
        logEvents.emit('error', e);
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
        logEvents.emit('error', e);
        return false;
    }
};

module.exports = { register, findUser, listUsers };
