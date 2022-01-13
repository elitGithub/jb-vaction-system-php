const USER_REGEX = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
const PWD_REGEX = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%]).{8,24}$/;

export const validateUserName = (string) => {
    return USER_REGEX.test(string);
}

export const validatePassword = (string) => {
    console.log('validatePassword', string);
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