export const validateUserName = (string) => {
    return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(string);
}

export const validatePassword = (string) => {
    return string.length > 7;
}

export const validateAlphanumeric = (string) =>  {
    return /^[\w\-\s]+$/.test(string);
}