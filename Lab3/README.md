## Лабораторная работа №3 по PHP

### Задание:

Создание формы обратной связи на лендинге с отправкой письма и записью в БД

Организовать на странице форму обратной связи

Форма должна содержать 4 поля: ФИО, почта, телефон, комментарий

После отправки формы пользователь оказывается на той же странице, но вместо формы он должен увидеть сообщение вида:

Оставлено сообщение из формы обратной связи.

Имя:

Фамилия:

Отчество:

E-mail:

Телефон:

С Вами свяжутся после [время отправки формы + 1.5 часа (в формате ЧЧ:ММ:СС ДД.ММ.ГГГГ)]

Отправка формы должна происходить без перезагрузки страницы (аяксом)

Поля валидировать средствами JS (на пустоту и формат почты и телефона). Если валидация не прошла, подсвечивать поля с
ошибками красным

Если заявка успешно оформлена, то сохранить ее в БД вместе с временем создания и отправить письмо с информацией из
заявки на email менеджера.

Запретить создание повторной заявки на тот же email в течение часа. Вывести соответствующее сообщение и написать время,
через которое можно создать повторную заявку.

### Ход работы:

Для выполнения данной работы была сформирована следующая структура проекта:

```
├── README.md
├── docker
│   ├── docker-compose.yaml
│   ├── nginx
│   │   ├── Dockerfile
│   │   └── default.conf
│   ├── php-fpm
│   │   └── Dockerfile
│   └── postgres
│       ├── Dockerfile
│       ├── create_table.sql
│       ├── database.env
│       └── trigger_before_insert.sql
├── index.php
├── js
│   ├── script.js
│   ├── set_errors.js
│   └── validation.js
└── src
    ├── Database.php
    ├── config.php
    └── handler.php

6 каталогов, 16 файлов
```

Для изменения базы данных на другую необходимо изменить настройки в файле `settings.php` и выполнить скрипт аналогичный
тому, что представлен далее.

Пример SQL скрипта для создания таблицы:

```sql
CREATE TABLE feedback
(
    id         serial PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name  VARCHAR(100) NOT NULL,
    patronymic VARCHAR(100),
    email      VARCHAR(255) NOT NULL,
    phone      VARCHAR(50)  NOT NULL,
    content    TEXT         NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
```

Логика обработки новой заявки представлена в виде триггера на PlpSQL:

```postgresql
create or replace function check_exist_feedback() returns trigger as
$$
declare
    last_feedback timestamp with time zone;
begin
    last_feedback = (select created_at
                     from feedback
                     where email = new.email
                     order by created_at desc
                     limit 1);

    if last_feedback is null then
        return new;
    end if;

    if new.created_at is null then
        new.created_at = now();
    end if;

    if new.created_at - last_feedback < interval '1 HOURS' then
        raise exception 'Вы еще не можете оставить заявку! Подождите еще %!',
            to_char(interval '1 HOURS' - (new.created_at - last_feedback), 'MI:SS');
    end if;

    return new;
end;
$$ language plpgsql;

create trigger check_exist_feedback_trigger
    before insert
    on feedback
    for each row
execute procedure check_exist_feedback();
```

Пример конфига для базы данных:

```dotenv
POSTGRES_USER=feedback_user
POSTGRES_PASSWORD=123321qwe
POSTGRES_DB=feedback_db
```

### Запуск проекта

Для запуска данного проекта необходим установленный Docker и docker-compose.

1. Перейдите в каталог `docker` и выполните команду `docker-compose up -d`.
2. После успешного запуска форма будет доступна по [этому](http://localhost:80/) адресу.
3. База данных будет доступна на порте 5435.
4. Для изменения почты менеджера необходимо изменить поле `manager_email` в файле `config.php`.