/**
 * Главная функция для обработки всех ошибок
 * @param errors (массив с ошибками)
 * @returns {boolean} (была ли найдена ошибка)
 */
const setErrors = (errors) => {
    // Переменная показывающая была ли уже найдена ошибка
    let error = false;
    // Массив наименований элементов на форме
    const names = ["last_name", "first_name", "patronymic", "email", "phone", "comment"];
    // Проходимся по всем ошибкам и элементам формы
    for (let i = 0; i < names.length; i++) {
        // Если ошибка есть, то выставляем ей сообщение с ошибкой
        if (errors[i] !== "") {
            setErrorOnInput("#" + names[i], errors[i]);
            error = true;
        } else {
            // Если ошибок нет для этого инпута, то очищаем ранние ошибки
            removeErrorFromInput("#" + names[i]);
        }
    }
    // Возвращаем флаг нахождения ошибок
    return error;
}

/**
 * Функция для установки ошибки
 * @param id (идентификатор элемента которому будет установлена ошибка)
 * @param message (текст ошибки)
 */
const setErrorOnInput = (id, message) => {
    $(id).addClass("is-invalid");
    $(id + "_error").text(message);
    $(id + "_label").addClass("text-danger");
}

/**
 * Функция для очистки элемента от ошибки
 * @param id (идентификатор элемента у которого будет убрана ошибка)
 */
const removeErrorFromInput = (id) => {
    $(id).removeClass("is-invalid");
    $(id + "_error").text("");
    $(id + "_label").removeClass("text-danger");
}