/**
 * Обработчик нажатия кнопки "найти адрес"
 */
$(document).on("click", "#btn-send-request", function () {
    // Получаем введенное значение
    const query = $("#address-input").val().trim();
    // Если введенное значение пустое, то выходим
    if (query.length === 0) {
        return false;
    }

    // Выполняем запрос
    $.ajax({
        url: 'src/handler.php',
        type: 'POST',
        cache: false,
        data: {
            'address': query
        },
        dataType: 'json',
        beforeSend: function () {
            // Перед отправкой блокируем кнопку для избежания повторной отправки
            $("#btn-send-request").prop('disabled', true);
        },
        success: function (data) {
            // Из результата получаем массив с описанием адреса и список станций метро
            let addressData = data.address;
            let metroList = data.metro;

            // Вызываем обработчик данных адреса
            addressHandler(addressData);
            // Вызываем обработчик данных о станциях метро
            metroHandler(metroList);
            // Вызываем обработчик для отрисовки новой карты
            mapHandler(addressData, metroList);

            // Обратно включаем кнопку отправки данных
            $("#btn-send-request").prop('disabled', false);
        },
        error: function (_) {
            // Обратно включаем кнопку отправки данных
            $("#btn-send-request").prop('disabled', false);
        }
    });
});

/**
 * Обработчик полученного адреса
 * @param address (массив с описанием полученного адреса)
 */
const addressHandler = (address) => {
    // Получаем объект на HTML форме со списком информации об адресе
    let addressHtmlList = $("#address-list");
    // Очищаем текущую информацию об адресах
    addressHtmlList.empty();

    // Если полученный адрес оказался некорректным
    if (address.length === 0) {
        // Показываем сообщение об ошибке
        $("#address-error").removeClass('d-none');
        // Активируем кнопку для отправки запроса
        $("#btn-send-request").prop('disabled', false);
        // Выходим из обработчика адреса
        return;
    }

    // Если адрес оказался корректным, то прячем сообщение об ошибке
    $("#address-error").addClass('d-none');
    // Добавляем в объект с информацией об адресе заголовок
    addressHtmlList.append("<p class='text-center'><strong>Информация об адресе</strong></p>")
    // Проходимся по полученному массиву адреса и выводим информацию
    Object.keys(address).forEach(key => {
        // Формируем данные для добавления на форму
        let rowData = `<p><strong>${address[key][0]}</strong>${address[key][1]}</p>`;
        // Добавляем в форму сформированные данные
        addressHtmlList.append(rowData);
    });
}

/**
 * Обработчик полученного массива станций метро
 * @param metroList (массив с объектами станции метро)
 */
const metroHandler = (metroList) => {
    // Получаем объект на HTML форме со списком информации об станциях метро
    let metroHtmlList = $("#metro-list");
    // Очищаем текущий список станций метро
    metroHtmlList.empty();

    // Если полученный список станций метро пуст, то выводим ошибку
    if (metroList.length === undefined || metroList.length === 0) {
        // Выводим сообщение об отсутствии станций метро
        $("#metro-error").removeClass('d-none');
        // Активируем кнопку отправки запроса
        $("#btn-send-request").prop('disabled', false);
        return;
    }

    // Если ошибок нет, то скрываем сообщение об этом
    $("#metro-error").addClass('d-none');
    // Добавляем заголовок в список со станциями метро
    metroHtmlList.append("<p class='text-center'><strong>Список станций метро</strong></p>");
    // Проходимся по всему списку станций метро
    metroList.forEach(function(item) {
        // Формируем объект для добавления в список станций метро
        let li = `<li>${item.full_address}</li>`;
        // Добавляем в форму сформированную информацию об станции метро
        metroHtmlList.append(li);
    })
}

/**
 * Обработчик для обновлении информации о карте
 * @param address (информация об полученном адресе)
 * @param metroList (информация об полученных станциях метро)
 */
const mapHandler = (address, metroList) => {
    // Если полученный адрес оказался некорректным, то выходим из функции
    if (address.length === 0) {
        return;
    }
    // Получаем объект карты из формы HTML
    let map = document.getElementById('map');
    // Удаляем её, так как для обновления необходимо пересоздать карту
    map.remove();
    // Добавляем в контейнер для хранения карты новый объект с картой
    $("#map-container").append('<div id="map" class="h-100" style="min-height: 700px"></div>');

    // Устанавливаем координаты центра карты (координаты указанного адреса)
    map = L.map('map').setView([address.longitude[1],address.latitude[1]], 16);

    // Подключаем стиль карты от Гугла
    L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {subdomains:['mt0','mt1','mt2','mt3']}).addTo(map);

    // Формируем маркер для отображения координат указанного адреса
    const userPositionMarker = L.marker([address.longitude[1], address.latitude[1]]).addTo(map);
    // Добавляем содержимое в сформированный маркет
    userPositionMarker.bindPopup('Указанный адрес находится здесь').openPopup();

    // Если список станций метро пуст, то выходим из функции
    if (metroList.length === undefined || metroList.length === 0) {
        return;
    }

    // Проходимся по каждой станции метро
    metroList.forEach(function(item) {
        // Формируем маркер для отображения станции метро на карте
        const pointMarker = L.marker([item.longitude, item.latitude]).addTo(map);
        // В содержимое отображаемое при нажатии добавляем полный адрес станции метро
        pointMarker.bindPopup(item.full_address);
    });
}