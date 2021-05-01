## Лабораторная работа №4 по PHP

### Задание:

Подготовить форму с возможностью выполнения SQL-инъекции. Произвести SQL-инъекцию и предотвратить её.

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
│       └── user_data.sql
├── index.php
├── js
│   └── script.js
└── src
    ├── Database.php
    ├── config.php
    └── handler.php

6 каталогов, 14 файлов
```

Для изменения базы данных на другую необходимо изменить настройки в файле `settings.php` и выполнить скрипт аналогичный
тому, что представлен далее.

Пример SQL скрипта для создания таблицы:

```sql
CREATE TABLE IF NOT EXISTS "user"
(
    id         serial PRIMARY KEY,
    last_name  varchar(255) not null,
    first_name varchar(255) not null,
    email      varchar(255) not null,
    password   varchar(255) not null
);
```

Данные добавляемые в БД имеют следующий вид:

```postgresql
insert into "user" (last_name, first_name, email, password)
values ('Stapells', 'Rozele', 'rstapells0@ocn.ne.jp', 'Fgnjtapdo8f');
insert into "user" (last_name, first_name, email, password)
values ('Croney', 'Jany', 'jcroney1@gmpg.org', 'cLTYRFB');
insert into "user" (last_name, first_name, email, password)
values ('Myall', 'Cynthea', 'cmyall2@webeden.co.uk', '1RPLOL');
insert into "user" (last_name, first_name, email, password)
values ('Lafferty', 'Boris', 'blafferty3@i2i.jp', 'V9h60Fzs6R');
insert into "user" (last_name, first_name, email, password)
values ('Crichley', 'Kasper', 'kcrichley4@hao123.com', 'LBbmbp1YhbW');
```

Пример конфига для базы данных:

```dotenv
POSTGRES_USER=injection_user
POSTGRES_PASSWORD=123321qwe
POSTGRES_DB=injection_db
```

### Запуск проекта

Для запуска данного проекта необходим установленный Docker и docker-compose.

1. Перейдите в каталог `docker` и выполните команду `docker-compose up -d`.
2. После успешного запуска форма будет доступна по [этому](http://localhost:80/) адресу.
3. База данных будет доступна на порте 5435.

### Проверка работоспособности разработанного проекта

Для проверки работоспособности попробуем ввести в левое поле (защищенное от SQL-инъекций) ввести значение заранее
неправильное, например: `1; SELECT * FROM "user"`. Если бы инъекция была выполнена, то мы бы получили список всех
пользователей, но этого не произошло.

Теперь попробуем ввести аналогичный пример в правое поле (незащищенное от SQL-инъекций). После выполнения запроса мы
получили данные всех пользователей. Также можно использовать запрос для удаления всех данных из
таблиц: `1; DROP TABLE "user"`, после выполнения данного запроса таблица с пользователями окажется пуста.