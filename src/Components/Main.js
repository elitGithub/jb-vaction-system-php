import { BrowserRouter as Router, Route, Routes, } from "react-router-dom";
import Login from "../Pages/Login";
import Register from "../Pages/Register";
import { Fragment } from "react";
import { Navbar } from "./Navbar";
import VacationList from "../Pages/VacationList";
import AdminPanel from "../Pages/AdminPanel";
// TODO: add state management
// TODO: once state is on, add backend
const Main = () => {
    return (
        <Fragment>
            <Router>
                <Navbar />
                <Routes>
                    <Route path="/" element={<AdminPanel/>}/>
                    <Route path="/vacation-list" element={<VacationList/>}/>
                    <Route path="/login" element={<Login/>}/>
                    <Route path="/register" element={<Register/>}/>
                </Routes>
            </Router>
        </Fragment>
    );
}

export default Main;