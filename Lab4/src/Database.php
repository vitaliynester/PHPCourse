<?php

namespace Lab4;

use Exception;
use PDO;
use PDOException;


class Database
{
    // Экземпляр PDO для работы с БД
    private PDO $pdo;
    // Массив с настройками БД
    private array $settings;

    /**
     * Конструктор базы данных
     *
     * @throws Exception (ошибка подключения к базе данных)
     */
    public function __construct()
    {
        // Подключаем и сохраняем настройки БД
        $this->settings = require_once 'config.php';
        // Формируем строку подключения к БД
        $dsn = 'pgsql:host=' . $this->settings['db_host'] .
            ';port=' . $this->settings['db_port'] .
            ';dbname=' . $this->settings['db_name'] .
            ';user=' . $this->settings['db_user'] .
            ';password=' . $this->settings['db_password'];
        try {
            // Подключаемся к БД
            $this->pdo = new PDO($dsn);
        } catch (PDOException $e) {
            // Если не получилось подключиться к БД, то бросаем исключение
            throw $e;
        }
    }

    /**
     * Метод для получения данных без использования инъекции
     *
     * @param array $data (принимает массив данных, где есть только ID пользователя)
     *
     * @return array (массив с полученными данными из БД)
     */
    public function getValueFromDbPDO(array $data): array
    {
        // Формируем селект запрос
        $sql = 'SELECT * FROM "user" WHERE id = :id';
        try {
            // Подготавливаем SQL запрос
            $sth = $this->pdo->prepare($sql);
            // Мапим данные к запросу и выполняем его
            $sth->execute($data);
            // Возвращаем все полученные записи
            return $sth->fetchAll();
        } catch (Exception $e) {
            // Если есть ошибка, то возвращаем сообщение об этом
            return ['error' => true];
        }
    }

    /**
     * Метод для получения данных с использованием инъекции
     *
     * @param string $queryString (запрос введенный пользователем)
     *
     * @return array (массив с результатами выполнения запроса)
     */
    public function getValueFromDbInjection(string $queryString): array
    {
        // Формируем строку для подключения к БД
        $dsn = 'host=' . $this->settings['db_host'] .
            ' port=' . $this->settings['db_port'] .
            ' dbname=' . $this->settings['db_name'] .
            ' user=' . $this->settings['db_user'] .
            ' password=' . $this->settings['db_password'];
        // Синхронно подключаемся к БД
        $dbConn = pg_connect($dsn);
        // Выполняем запрос с инъекцией (добавляем к строке запрос пользователя)
        $sql = 'SELECT * FROM "user" WHERE id = ' . $queryString;
        // Получаем данные из выполненного запроса к БД
        $resource = pg_query($dbConn, $sql);
        // Создаем массив с результатом выполнения запроса
        $result = [];
        do {
            // Получаем результат выполнения запроса (разделяется по символу ";")
            $queryResult = pg_fetch_array($resource, null, PGSQL_ASSOC);
            // Если новых данных для прочтения нет, то завершаем цикл
            if (false == $resource || null == $queryResult) {
                break;
            }
            // Добавляем в массив полученный результат выполнения запроса
            $result[] = $queryResult;
        } while (true);
        // Возвращаем результат выполнения запросов
        return $result;
    }

    /**
     * Метод для создания таблицы пользователей и добавления в неё данных
     */
    public function addRowsInDb()
    {
        // Считываем SQL запрос для создания таблицы
        $sqlCreateTable = file_get_contents('../docker/postgres/create_table.sql', true);
        // Выполняем запрос для создания таблицы
        $this->pdo->exec($sqlCreateTable);

        // Считываем SQL запрос для добавления данных в таблицу
        $sqlUserData = file_get_contents('../docker/postgres/user_data.sql', true);
        // Выполняем запрос для добавления данных в таблицу
        $this->pdo->exec($sqlUserData);
    }
}
