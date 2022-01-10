const USER_REGEX = /^[A-z][A-z0-9-_]{3,23}$/;
const PWD_REGEX = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%]).{8,24}$/;

export const validateUserName = (string) => {
    return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(string);
}

export const validatePassword = (string) => {
    return string.length >= 7 && PWD_REGEX.test(string);
}

export const validateAndMatchPasswords = (password, passwordMatch) => {
    const passwordIsValid = validatePassword(password);
    const passwordMatchIsValid = validatePassword(passwordMatch);
    const passwordsAreIdentical = passwordMatch === password;
    return passwordIsValid && passwordMatchIsValid && passwordsAreIdentical;
};

export const validateAlphanumeric = (string) =>  {
    return /^[\w\-\s]+$/.test(string);
}