import axios from 'axios';
import configService from './configService';
const LoginService = {
    login: async (username, password) => {
        const response = await axios.post(`${configService.apiUrl}/api/users/login`, {username, password});
        return await response.data;
    },

    secondValidationMethod: function(value) {
        //inspect the value
    }
};

export default LoginService;
