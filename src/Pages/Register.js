import { Fragment, useEffect, useRef, useState } from "react";
import classes from "../Components/Register.module.css";
import { Link } from "react-router-dom";
import { validateAlphanumeric, validateAndMatchPasswords, validatePassword, validateUserName } from "../shared/utils";
import { faCheck, faInfoCircle, faTimes } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

const Register = () => {

    const emailRef = useRef();
    const errRef = useRef();

    // Username
    const [email, setEmail] = useState('');
    const [emailFocus, setEmailFocus] = useState(false);
    const [validEmail, setValidEmail] = useState(false);
    // Username

    // Password
    const [password, setPassword] = useState('');
    const [passwordFocus, setPasswordFocus] = useState(false);
    const [validPassword, setValidPassword] = useState(false);
    // Password

    // Match Password
    const [matchPassword, setPasswordMatch] = useState('');
    const [passwordMatchFocus, setpasswordMatchFocus] = useState(false);
    const [validPasswordMatch, setValidPasswordMatch] = useState(false);
    // Match Password

    // Passwords are valid and matching
    const [matchingPwds, setPwdsMatchingPwds] = useState(false);
    // Passwords are valid and matching


    const [firstName, setFirsttName] = useState('');
    const [firstNameFocus, setFirstNameFocus] = useState(false);
    const [validFirstName, setValidFirstName] = useState(false);

    const [lastName, setLastName] = useState('');
    const [lastNameFocus, setLastNameFocus] = useState(false);
    const [validLastName, setValidLastName] = useState(false);


    const [errMessage, setErrMessage] = useState('');
    const [success, setSuccess] = useState(false);

    useEffect(() => {
        emailRef.current.focus();
    }, []);

    useEffect(() => {
        setValidFirstName(validateAlphanumeric(firstName));
    }, [firstName]);

    useEffect(() => {
        setValidLastName(validateAlphanumeric(lastName));
    }, [lastName]);

    useEffect(() => {
        const result = validateUserName(email);
        console.log(result);
        console.log(email);
        setValidEmail(result);
    }, [email]);

    useEffect(() => {
        const resultPwd = validatePassword(password);
        setValidPassword(resultPwd);
        console.log('resultPwd', resultPwd);
        const resultPwdMatch = validatePassword(matchPassword);
        setValidPasswordMatch(resultPwdMatch);
        console.log('resultPwdMatch', resultPwdMatch);
        const match = (password === matchPassword);
        console.log('match', match);
        setPasswordMatch(validateAndMatchPasswords(password, matchPassword));
    }, [password, matchPassword]);

    useEffect(() => {
        setErrMessage('')
    }, [email, password, matchPassword]);

    return (<Fragment>
        <p ref={errRef} className={errMessage ? classes.errorMessage : classes.offScreen}
           aria-live="assertive">{errMessage}</p>
        <form className="form">
            <h2>Register</h2>

            <div className={classes['input-parent']}>
                <label htmlFor="username">Username:
                    <FontAwesomeIcon icon={faCheck} className={validEmail ? classes.valid : classes.hide} />
                    <FontAwesomeIcon icon={faTimes} className={validEmail || !email ? classes.hide : classes.invalid} />
                </label>
                <input type="email"
                       id="username"
                       ref={emailRef}
                       autoComplete="off"
                       onChange={(e) => {
                           setEmail(e.target.value)
                       }}
                       required
                       aria-invalid={validEmail ? "false" : "true"}
                       aria-describedby="uidnote"
                       name="username"
                       value={email}
                       onFocus={() => setEmailFocus(true)}
                       onBlur={() => setEmailFocus(false)}/>
                <p id="uidnote"
                   className={emailFocus && email && !validEmail ? classes.instructions : classes.offScreen}>
                    <FontAwesomeIcon icon={faInfoCircle}/>
                    Must be a valid email address.
                </p>
            </div>

            <div className={classes['input-parent']}>
                <label htmlFor="firstName">First Name</label>
                <input
                    type="text"
                    name="firstName"
                    value={firstName || ""}
                />

            </div>
            <div className={classes['input-parent']}>
                <label htmlFor="lastName">Last Name</label>
                <input
                    type="text"
                    name="lastName"
                    value={lastName || ""}
                />
            </div>

            <div className={classes['input-parent']}>
                <label htmlFor="password">Password</label>
                <input
                    type="password"
                    name="password"
                    value={password || ""}
                />
            </div>

            <div className={classes['button-wrapper']}>
                <button className={classes['login-btn']} type="submit">Register</button>
                <button className={classes['login-btn']}><Link to="/login">Already Have an account?</Link></button>
            </div>
        </form>

    </Fragment>)
}

export default Register;