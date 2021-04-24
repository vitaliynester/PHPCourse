<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l"
          crossorigin="anonymous">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <title>SQL инъекции</title>
</head>
<body>
<div class="container">
    <h1 class="text-center mt-4">Получение данных из таблицы USER</h1>
    <div class="row mt-4">
        <div class="col-md-6 col-sm-6 col-6">
            <h4 class="text-center">Получение без инъекции</h4>
            <form action="">

                <div class="form-group">
                    <textarea name="query-pdo"
                              id="query-pdo"
                              class="form-control"
                              placeholder="Введите ID пользователя"></textarea>
                    <span id="comment_error" class="text-danger"></span>
                </div>

                <button type="button" id="send_form" class="btn btn-success btn-block">Выполнить запрос без инъекции
                </button>

            </form>
        </div>
        <div class="col-md-6 col-sm-6 col-6">
            <h4 class="text-center">Получение через инъекцию</h4>
            <form action="">

                <div class="form-group">
                    <textarea name="query-injection"
                              id="query-injection"
                              class="form-control"
                              placeholder="Введите SQL запрос"></textarea>
                    <span id="comment_error" class="text-danger"></span>
                </div>

                <button type="button" id="send_form_injection" class="btn btn-success btn-block">Выполнить запрос с
                    инъекцией
                </button>

            </form>
        </div>
    </div>
    <button type="button" id="add-rows" class="col-12 mt-4 btn btn-success btn-block">Создать таблицу и добавить записи
        (если удалили таблицу или записи)
    </button>
    <div class="col-md-12 col-lg-12 col-sm-12 col-12 text-center mt-4">
        <h4>Результат выполнения запроса</h4>
        <span>
            <table class="table col-md-12 col-lg-12 col-sm-12 col-12 table-hover mt-4">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Фамилия</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Почта</th>
                </tr>
                </thead>
                <tbody id="result-data">
                <tr>

                </tr>
                </tbody>
            </table>
        </span>
    </div>
</div>
</body>
</html>
