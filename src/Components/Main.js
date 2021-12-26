import { BrowserRouter as Router, Route, Routes, } from "react-router-dom";
import Login from "../Pages/Login";
import Register from "../Pages/Register";
import { Fragment } from "react";
import { Navbar } from "./Navbar";
import VacationList from "../Pages/VacationList";
import AdminPanel from "../Pages/AdminPanel";
import CreateEditVacation from "../Pages/CreateEditVacation";
import {useSelector} from "react-redux";
// TODO: add state management
// TODO: once state is on, add backend
const Main = () => {
    const modal = useSelector((state) => state.modal.value);
    return (
        <Fragment>
            <Router>
                <Navbar />
                <Routes>
                    <Route path="/admin-panel" element={<AdminPanel/>}/>
                    <Route path="/vacation-list" element={<VacationList/>}/>
                    <Route path="/" element={<VacationList/>}/>
                    <Route path="/login" element={<Login/>}/>
                    <Route path="/register" element={<Register/>}/>
                </Routes>
            </Router>
            {modal.isShown && <CreateEditVacation/>}
        </Fragment>
    );
}

export default Main;
