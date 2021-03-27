<?php

class Database
{
    // Экземпляр PDO для работы с БД
    private PDO $pdo;
    // Массив с настройками БД
    private array $settings;

    /**
     * Конструктор базы данных
     * @throws Exception (ошибка подключения к базе данных)
     */
    public function __construct()
    {
        // Подключаем и сохраняем настройки БД
        $this->settings = require_once "settings.php";
        // Формируем строку подключения к БД
        $dsn = 'pgsql:host=' . $this->settings['db_host'] .
            ";port=" . $this->settings['db_port'] .
            ";dbname=" . $this->settings['db_name'] .
            ";user=" . $this->settings['db_user'] .
            ";password=" . $this->settings['db_password'];
        try {
            // Подключаемся к БД
            $this->pdo = new PDO($dsn);
            if ($this->pdo) {
                echo "Соединение с БД установлено!\r\n";
            }
        } catch (PDOException $e) {
            // Если не получилось подключиться к БД, то бросаем исключение
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Запись данных в БД
     * @param array $data (массив с информацией о пользователе для записи в БД)
     * @return bool (результат записи в БД (True - успех, False - неудача))
     */
    public function writeToDb(array $data): bool
    {
        $sql = "INSERT INTO " . $this->settings['db_table'] . " (name, phone, email, address) " .
            "VALUES (:name, :phone, :email, :address)";
        return $this->pdo->prepare($sql)->execute($data);
    }

    /**
     * Получение данных из БД
     * @return array (массив с записями из БД)
     */
    public function getDataFromDb(): array
    {
        $statement = $this->pdo->prepare("SELECT * " .
            "FROM " . $this->settings['db_table']);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}