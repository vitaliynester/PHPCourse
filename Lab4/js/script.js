/**
 * Обработчик нажатия кнопки "без использования инъекции"
 */
$(document).on("click", "#send_form", function () {
    // Получаем введенное значение
    const query = $("#query-pdo").val().trim();

    $.ajax({
        url: 'src/handler.php',
        type: 'POST',
        cache: false,
        data: {
            'id': query
        },
        dataType: 'json',
        beforeSend: function () {
            // Перед отправкой блокируем кнопку для избежания повторной отправки
            $("#send_form").prop('disabled', true);
        },
        success: function (data) {
            // В случае успешной отправки запроса
            // Получаем элемент для отображения
            let resultTable = $("#result-data");
            resultTable.empty();
            for (let i = 0; i < data.length; i++) {
                let rowData = `<tr>` + `<th scope="row">${data[i].id}</th>\n` +
                    `<td>${data[i].last_name}</td>\n` +
                    `<td>${data[i].first_name}</td>\n` +
                    `<td>${data[i].email}</td>\n` + `</tr>`;
                resultTable.append(rowData);
            }
            // Обратно включаем кнопку отправки данных
            $("#send_form").prop('disabled', false);
        },
        error: function (data) {
            // В случае ошибки очищаем данные
            let resultTable = $("#result-data");
            resultTable.empty();
            // Обратно включаем кнопку отправки данных
            $("#send_form").prop('disabled', false);
        }
    });
});

/**
 * Обработчик нажатия кнопки "с использованием инъекции"
 */
$(document).on("click", "#send_form_injection", function () {
    // Получаем веденное значение
    const query = $("#query-injection").val().trim();

    $.ajax({
        url: 'src/handler.php',
        type: 'POST',
        cache: false,
        data: {
            'query': query,
        },
        dataType: 'json',
        beforeSend: function () {
            // Перед отправкой блокируем кнопку для избежания повторной отправки
            $("#send_form_injection").prop('disabled', true);
        },
        success: function (data) {
            // В случае успешной отправки запроса
            // Получаем элементы для отображения
            let resultTable = $("#result-data");
            resultTable.empty();
            for (let i = 0; i < data.length; i++) {
                let rowData = `<tr>` + `<th scope="row">${data[i].id}</th>\n` +
                    `<td>${data[i].last_name}</td>\n` +
                    `<td>${data[i].first_name}</td>\n` +
                    `<td>${data[i].email}</td>\n` + `</tr>`;
                resultTable.append(rowData);
            }
            // Обратно включаем кнопку отправки данных
            $("#send_form_injection").prop('disabled', false);
        },
        error: function (data) {
            // В случае ошибки очищаем данные
            let resultTable = $("#result-data");
            resultTable.empty();
            // Обратно включаем кнопку отправки данных
            $("#send_form_injection").prop('disabled', false);
        }
    });
});

/**
 * Обработчик нажатия на кнопку добавления данных в БД
 */
$(document).on("click", "#add-rows", function () {
    $.ajax({
        url: 'src/handler.php',
        type: 'POST',
        cache: false,
        data: {
            'add_to_db': true,
        },
        dataType: 'json',
        beforeSend: function () {
            // Перед отправкой блокируем кнопку для избежания повторной отправки
            $("#add-rows").prop('disabled', true);
        },
        success: function (data) {
            // Обратно включаем кнопку отправки данных
            $("#add-rows").prop('disabled', false);
        },
        error: function (data) {
            // Обратно включаем кнопку отправки данных
            $("#add-rows").prop('disabled', false);
        }
    });
});
