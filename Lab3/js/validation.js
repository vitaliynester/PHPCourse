/**
 * Функция для валидации фамилии
 * @param lastName (фамилия пользователя)
 * @returns {string} (текст ошибки)
 */
const validateLastName = (lastName) => {
    if (lastName === "") {
        return "Введите корректную фамилию";
    } else if (lastName.length >= 100) {
        return "Максимальная длина фамилии 100 символов!";
    }
    return "";
}

/**
 * Функция для валидации имени
 * @param firstName (имя пользователя)
 * @returns {string} (текст ошибки)
 */
const validateFirstName = (firstName) => {
    if (firstName === "") {
        return "Введите корректное имя";
    } else if (firstName.length >= 100) {
        return "Максимальная длина имени 100 символов!";
    }
    return "";
}

/**
 * Функция для валидации отчества
 * @param patronymic (отчество пользователя)
 * @returns {string} (текст ошибки)
 */
const validatePatronymic = (patronymic) => {
    if (patronymic.length >= 100) {
        return "Максимальная длина отчества 100 символов!";
    }
    return "";
}

/**
 * Функция для валидации почты
 * @param email (электронная почта пользователя)
 * @returns {string} (текст ошибки)
 */
const validateEmail = (email) => {
    const re = new RegExp("^(([^<>()[\\]\\\\.,;:\\s@\"]+(\\.[^<>()[\\]\\\\.,;:\\s@\"]+)*)|(\".+\"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$");
    if (!re.test(email)) {
        return "Введите корректный Email";
    }
    return "";
}

/**
 * Функция для валидации телефона пользователя
 * @param phone (номер телефона пользователя)
 * @returns {string} (текст ошибки)
 */
const validatePhone = (phone) => {
    const re = new RegExp("^(\\+7|7|8)?[\\s\\-]?\\(?[489][0-9]{2}\\)?[\\s\\-]?[0-9]{3}[\\s\\-]?[0-9]{2}[\\s\\-]?[0-9]{2}$");
    if (!re.test(phone)) {
        return "Введите корректный номер телефона";
    }
    return "";
}

/**
 * Функция для валидации текста сообщения
 * @param message (текст сообщения)
 * @returns {string} (текст ошибки)
 */
const validateMessage = (message) => {
    if (message === "") {
        return "Введите корректное сообщение";
    }
    return "";
}