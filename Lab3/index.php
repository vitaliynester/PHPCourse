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
    <script type="text/javascript" src="js/validation.js"></script>
    <script type="text/javascript" src="js/set_errors.js"></script>
    <title>Форма обратной связи</title>
</head>
<body>
<div class="container">
    <h1 class="text-center mt-4">Форма обратной связи</h1>
    <div id="notification-div" class="alert text-center">
    </div>
    <form action="">
        <div class="form-group">
            <label id="last_name_label" class="" for="last_name">Фамилия</label>
            <input type="text"
                   class="form-control "
                   name="last_name"
                   id="last_name"
                   placeholder="Иванов"
                   value=""
                   required>
            <span id="last_name_error" class="text-danger"></span>
        </div>

        <div class="form-group">
            <label id="first_name_label" class="" for="first_name">Имя</label>
            <input type="text"
                   class="form-control "
                   name="first_name"
                   id="first_name"
                   placeholder="Иван"
                   value=""
                   required>
            <span id="first_name_error" class="text-danger"></span>
        </div>

        <div class="form-group">
            <label id="patronymic_label" class="" for="patronymic">Отчество</label>
            <input type="text"
                   class="form-control "
                   name="patronymic"
                   id="patronymic"
                   placeholder="Иванович"
                   value="">
            <span id="patronymic_error" class="text-danger"></span>
        </div>

        <div class="form-group">
            <label id="email_label" class="" for="email">Электронная почта</label>
            <input type="email"
                   class="form-control "
                   name="email"
                   id="email"
                   placeholder="example@mail.ru"
                   value="">
            <span id="email_error" class="text-danger"></span>
        </div>

        <div class="form-group">
            <label id="phone_label" class="" for="phone">Номер телефона</label>
            <input type="text"
                   class="form-control"
                   name="phone"
                   id="phone"
                   placeholder="+7(999)999-99-99"
                   value="">
            <span id="phone_error" class="text-danger"></span>
        </div>

        <div class="form-group">
            <label id="comment_label" class="" for="comment">Комментарий</label>
            <textarea name="comment"
                      id="comment"
                      class="form-control"
                      placeholder="Введите комментарий"></textarea>
            <span id="comment_error" class="text-danger"></span>
        </div>

        <button type="button" id="send_form" class="btn btn-success btn-block">Отправить заявку</button>

    </form>
</div>
</body>
</html>
