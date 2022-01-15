import axios from './axios';
const LoginService = {
    login: async (user) => {
        const response = await axios.post(`/api/users/login4`, {username: user.username, password: user.password});
        return await response.data;
    },

    secondValidationMethod: function(value) {
        //inspect the value
    }
};

export default LoginService;
