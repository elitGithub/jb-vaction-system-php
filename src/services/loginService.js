import axios from 'axios';

const LoginService = {
    login: async (username, password) => {
        return await axios.post('http://localhost:3006/login', {username, password});
    },

    secondValidationMethod: function(value) {
        //inspect the value
    }
};

export default LoginService;