import { createSlice } from "@reduxjs/toolkit";
const initialStateValue = {
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    loggedIn: false,
    isAdmin: false
};
// Exporting the whole slice of data from the store
export const userSlice = createSlice({
    name: "user",
    initialState: {
        value: initialStateValue,
    },
    // The actions that can be performed.
    // Basically an object that is a function, that alters the state of the parent object (slice) it takes
    // In this case, login would modify the above defined initial state to whatever was passed as the 'payload' argument
    // So we would have firstName = action.payload.firstName, for example.
    reducers: {
        login: (state, action) => {
            state.value = action.payload;
        },
        logout: (state, action) => {
            state.value = initialStateValue;
        }
    }
});

// Exporting just the reducer method
export default userSlice.reducer;

// Exporting just actions as functions
export const { login, logout } = userSlice.actions;
