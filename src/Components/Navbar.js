import {Fragment, useState} from "react";
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
        dispatch(showHide({isShown: !modal.isShown}));
    };

    return(<Fragment>
        <div className={classes.topnav}>
            {user.isAdmin && user.loggedIn && <Link to='/'>
                Admin Panel
            </Link>}
            {user.isAdmin && user.loggedIn && <button onClick={addVacation}>
                Add Vacation
            </button>}
            {user.loggedIn && <Link to={'/vacation-list'}>
                Vacation List
            </Link>}
            {!user.loggedIn && <Link to={'/login'}>
                Login
            </Link>}
            {user.loggedIn && <Link to={'/logout'} onClick={() => dispatch((logout()))}>
                Logout
            </Link>}
            {!user.loggedIn && <Link to={'/register'}>
                Register
            </Link>}
        </div>
    </Fragment>);

}
