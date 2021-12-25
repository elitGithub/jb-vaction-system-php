import { Fragment } from "react";
import classes from "./Navbar.module.css";
import { Link } from "react-router-dom";

export const Navbar = () => {
    return(<Fragment>
        <div className={classes.topnav}>
            <Link to='/'>
               Admin Panel
            </Link>
            <Link to={'/vacation-list'}>
                Vacation List
            </Link>
            <Link to={'/login'}>
                Login
            </Link>
            <Link to={'/register'}>Register</Link>
        </div>
    </Fragment>);

}