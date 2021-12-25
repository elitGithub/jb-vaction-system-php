// The pizza store contains slices of data inside the reducer container.

import { configureStore } from "@reduxjs/toolkit";
import userReducer from './features/user';
const store = configureStore({
    reducer: {
        user: userReducer
    }
});

export default store