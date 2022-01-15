import { Fragment, useEffect, useState } from "react";
import classes from "./Navbar.module.css";
import { Link } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { showHide } from "../features/modal";
import { logout } from "../features/user";

export const Navbar = () => {
    const user = useSelector((state) => state.user.value);
    const modal = useSelector((state) => state.modal.value);
    const dispatch = useDispatch();
    const addVacation = (e) => {
        e.preventDefault();
        dispatch(showHide({ isShown: !modal.isShown }));
    };

    const [loggedIn, setLoggedIn] = useState(false);
    const [isAdmin, setIsAdmin] = useState(false);

    useEffect(() => {
        setLoggedIn(user && user.loggedIn);
        setIsAdmin(user && user.isAdmin && user.loggedIn);
        console.log('in use effect', user);

    }, [user]);

    console.log('navbsar', user);

    // setTimeout(() => {
    //     console.log('timeout', state.user.getValue());
    // }, 2000)

    return (<Fragment>
        <div className={ classes.topnav }>
            { loggedIn && isAdmin && <Link to='/admin-panel'>
                Admin Panel
            </Link> }
            { loggedIn && isAdmin && <button onClick={ addVacation }>
                Add Vacation
            </button> }
            { loggedIn && <Link to={ '/vacation-list' }>
                Vacation List
            </Link> }
            { loggedIn && <Link to={ '/logout' } onClick={ () => dispatch((logout())) }>
                Logout
            </Link> }
            { !loggedIn && <Link to={ '/login' }>
                Login
            </Link> }
            { !loggedIn && <Link to={ '/register' }>
                Register
            </Link> }
        </div>
    </Fragment>);

}
