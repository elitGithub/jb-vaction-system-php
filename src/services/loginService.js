import axios from './axios';
const LoginService = {
    login: async (user) => {
        const response = await axios.post(
            `/api/users/login`,
            {username: user.userName, password: user.password},
            {
                headers: { 'Content-Type': 'application/json' },
                withCredentials: true
            });
        return await response.data;
    },

    secondValidationMethod: function(value) {
        //inspect the value
    }
};

export default LoginService;
