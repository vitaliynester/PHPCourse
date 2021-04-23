$(document).on("click", "#send_form", function () {
    // Получаем переданные значения и убираем лишние пробелы
    const lastName = $("#last_name").val().trim();
    const firstName = $("#first_name").val().trim();
    const patronymic = $("#patronymic").val().trim();
    const email = $("#email").val().trim();
    const phone = $("#phone").val().trim();
    const comment = $("#comment").val().trim();

    // Получаем ошибки для каждого поля
    const lastNameError = validateLastName(lastName);
    const firstNameError = validateFirstName(firstName);
    const patronymicError = validatePatronymic(patronymic);
    const emailError = validateEmail(email);
    const phoneError = validatePhone(phone);
    const commentError = validateMessage(comment);

    // Устанавливаем ошибки на форму
    const error = setErrors([lastNameError, firstNameError, patronymicError, emailError, phoneError, commentError]);
    // Если была найдена ошибка, то прекращаем работу
    if (error) {
        return false;
    }

    // Если ошибок не найдено, то отправляем запрос
    $.ajax({
        url: 'src/handler.php',
        type: 'POST',
        cache: false,
        data: {
            'last_name': lastName,
            'first_name': firstName,
            'patronymic': patronymic,
            'email': email,
            'phone': phone,
            'comment': comment
        },
        dataType: 'json',
        beforeSend: function () {
            // Перед отправкой блокируем кнопку для избежания повторной отправки
            $("#send_form").prop('disabled', true);
        },
        success: function (data) {
            // В случае успешной отправки запроса
            // Получаем элемент для отображения вспомогательной информации
            let notificationDiv = $("#notification-div");
            // Если данные были успешно отправлены и ошибок не возникло
            if (data.error == null) {
                // Удаляем класс для отображения ошибок
                notificationDiv.removeClass("alert-danger");
                // Добавляем класс для отображения успешной информации
                notificationDiv.addClass("alert-success");
                // Очищаем дочерние элементы
                notificationDiv.empty();
                // Добавляем дополнительную информацию о записях
                notificationDiv.append("<div>Оставлено сообщение из формы обратной связи.</div>");
                notificationDiv.append(`<div>Имя: ${data.first_name}.</div>`);
                notificationDiv.append(`<div>Фамилия: ${data.last_name}.</div>`);
                // Проверяем наличие отчества у полученных данных
                if (data.patronymic !== "") {
                    notificationDiv.append(`<div>Отчество: ${data.patronymic}.</div>`);
                }
                notificationDiv.append(`<div>Email: ${data.email}.</div>`);
                notificationDiv.append(`<div>Телефон: ${data.phone}.</div>`);
                notificationDiv.append(`<div>С вами свяжутся после ${data.created_at}.</div>`);
            } else {
                // Если при добавлении в БД возникли ошибки
                // Удаляем класс для отображения успешной информации
                notificationDiv.removeClass("alert-success");
                // Добавляем класс для отображения ошибок
                notificationDiv.addClass("alert-danger");
                // Очищаем дочерние элементы
                notificationDiv.empty();
                // Добавляем сообщение об ошибке
                notificationDiv.append(`<div>${data.error}</div>`);
            }
            // Обратно включаем кнопку отправки данных
            $("#send_form").prop('disabled', false);
        }
    });
});
