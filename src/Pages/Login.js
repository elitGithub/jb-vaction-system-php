import { Fragment, useState } from "react";
import classes from "../Components/Login.module.css";
import { Link } from "react-router-dom";
import { validatePassword, validateUserName } from "../shared/utils";
import { useDispatch, useSelector } from "react-redux";
import { login } from '../features/user';
import LoginService from "../services/loginService";

const Login = () => {
    const loginService = LoginService;
    const user = useSelector((state) => state.user.value);
    const dispatch = useDispatch();
    const [inputs, setInputs] = useState({});
    const [validEmail, setValidEmail] = useState(false);
    const [validaPassword, setValidPassword] = useState(false);
    const [inputError, setInputError] = useState(false);

    const handleChange = (event) => {
        setInputError(false);
        const name = event.target.name;
        const value = event.target.value;
        setInputs(values => ({ ...values, [name]: value }));
        if (inputs.username) {
            setValidEmail(validateUserName(inputs.username));
        }

        if (inputs.password) {
            setValidPassword(validatePassword(inputs.password));
        }
    }

    const handleSubmit = async (event) => {
        event.preventDefault();
        if (!inputs.username || !inputs.password) {
            setInputError(true);
            return;
        }

        if (validEmail && validaPassword) {
            // TODO: DB call to validate the data on the backend.
            setInputError(false);
            const response = await loginService.login(inputs.username, inputs.password);
            if (response.hasOwnProperty('success') && response.success === true) {
                // Here we'll later use the data from be!
                dispatch(login({
                    firstName: 'John',
                    lastName: 'Doe',
                    email: inputs.email,
                    password: inputs.password,
                    loggedIn: true,
                    isAdmin: true,
                }));
            }
            return;
        }

        setInputError(true);
    }

    return (<Fragment>
        <form onSubmit={ handleSubmit } className={ classes.form }>
            <h2>Login to your account { user.name }</h2>

            <div className={ classes['input-parent'] }>
                <label htmlFor="username">Email</label>
                <input className={ inputError ? classes['input-error'] : '' }
                       type="email"
                       name="username"
                       value={ inputs.username || "" }
                       onInput={ handleChange }/>
            </div>
            <div className={ classes['input-parent'] }>
                <label htmlFor="password">Password</label>
                <input className={ inputError ? classes['input-error'] : '' }
                       type="password"
                       name="password"
                       value={ inputs.password || "" }
                       onInput={ handleChange }/>
            </div>
            <div className={ classes['button-wrapper'] }>
                <button className={ classes['login-btn'] } type="submit">Login</button>
                <button className={ classes['login-btn'] }><Link to="/register">Don't have an account?</Link></button>
            </div>
        </form>

    </Fragment>)
};

export default Login;
