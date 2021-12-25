import { Fragment, useState } from "react";
import classes from "../Components/Register.module.css";
import { Link } from "react-router-dom";
import { validateAlphanumeric, validatePassword, validateUserName } from "../shared/utils";


const Register = () => {

    const [inputs, setInputs] = useState({});
    const [validEmail, setValidEmail] = useState(false);
    const [validaPassword, setValidPassword] = useState(false);
    const [validName, setValidName] = useState(false);
    const [validLastName, setValidLastName] = useState(false);
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

        if (inputs.firstName) {
            setValidName(validateAlphanumeric(inputs.firstName));
        }

        if (inputs.lastName) {
            setValidLastName(validateAlphanumeric(inputs.lastName));
        }
    }

    const handleSubmit = (event) => {
        event.preventDefault();
        if (!inputs.username || !inputs.password || !inputs.firstName || !inputs.lastName) {
            setInputError(true);
            return;
        }

        if (validEmail && validaPassword && validName && validLastName) {
            // TODO: DB call to validate the data on the backend.
            // Username must be unique.
            setInputError(false);
            console.log('hey! registered!');
            return;
        }

        setInputError(true);
    }
    return (<Fragment>
        <form onSubmit={handleSubmit} className={classes.form}>
            <h2>Login to your account</h2>

            <div className={classes['input-parent']}>
                <label htmlFor="firstName">First Name</label>
                <input className={inputError ? classes['input-error'] : ''}
                       type="text"
                       name="firstName"
                       value={inputs.firstName || ""}
                       onInput={handleChange}/>
            </div>
            <div className={classes['input-parent']}>
                <label htmlFor="lastName">Last Name</label>
                <input className={inputError ? classes['input-error'] : ''}
                       type="text"
                       name="lastName"
                       value={inputs.lastName || ""}
                       onInput={handleChange}/>
            </div>
            <div className={classes['input-parent']}>
                <label htmlFor="username">Email</label>
                <input className={inputError ? classes['input-error'] : ''}
                       type="email"
                       name="username"
                       value={inputs.username || ""}
                       onInput={handleChange}/>
            </div>
            <div className={classes['input-parent']}>
                <label htmlFor="password">Password</label>
                <input className={inputError ? classes['input-error'] : ''}
                       type="password"
                       name="password"
                       value={inputs.password || ""}
                       onInput={handleChange}/>
            </div>

            <div className={classes['button-wrapper']}>
                <button className={classes['login-btn']} type="submit">Register</button>
                <button className={classes['login-btn']}><Link to="/login">Already Have an account?</Link></button>
            </div>
        </form>

    </Fragment>)
}

export default Register;