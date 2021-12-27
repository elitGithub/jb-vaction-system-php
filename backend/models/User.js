const mongoose = require('mongoose');

const userSchema = new mongoose.Schema({
    userName: {
        type: String,
        required: true,
        lowercase: true,
        immutable: true,
        minLength: 3,
        validator: v => /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(v),
        message: props => `${props.value} is not a valid email`
    },
    password: {
        type: String,
        required: true,
        minLength: 8
    },
    firstName: String,
    lastName: String,
    createdAt: {
        type: Date,
        default: () => Date.now(),
        immutable: true,
    },
    updatedAt: {
        type: Date,
        default: () => Date.now()
    },
    followedVacations: [String],
    // role: mongoose.SchemaTypes.ObjectId <- references another object based on id. Will be implemented with the Role model.
});

module.exports = mongoose.model('User', userSchema);
