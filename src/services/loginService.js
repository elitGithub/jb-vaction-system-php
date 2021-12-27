import axios from 'axios';

const LoginService = {
    login: async (username, password) => {
        const response = await axios.post('http://localhost:3500/login', {username, password});
        return await response.data;
    },

    secondValidationMethod: function(value) {
        //inspect the value
    }
};

export default LoginService;
