<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <title>YandexGeoCoder</title>
</head>
<body>
<div class="container-fluid">

    <div class="input-group mb-4 mt-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Адрес:</span>
        </div>
        <input type="text" id="address-input" class="form-control" placeholder="г. Липецк, ул. Физкультурная д.13"
               aria-describedby="basic-addon1">
        <div class="input-group-append">
            <button type="button" id="btn-send-request" class="input-group-text btn btn-success btn-block">Найти адрес
            </button>
        </div>
    </div>

    <div class="result row">
        <div id="map-container" class="col-sm-8 col-lg-8 col-md-8 col-xl-8 col-8">
            <div id="map" class="h-100" style="min-height: 700px"></div>
            <script>
                var map = L.map('map').setView([52.60204457166468, 39.50474399999997], 16);
                L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {subdomains: ['mt0', 'mt1', 'mt2', 'mt3']}).addTo(map);
            </script>
        </div>

        <div class="com-sm-4 col-lg-4 col-md-4 col-xl-4 col-4">

            <div id="address-list"></div>
            <div id="metro-list"></div>

            <h4 id="address-error" class="text-center d-none">Не удалось получить данные об указанном адресе!</h4>
            <h4 id="metro-error" class="text-center d-none">Не удалось обнаружить ближайшие станции метро!</h4>

        </div>
    </div>
</div>
</body>
</html>