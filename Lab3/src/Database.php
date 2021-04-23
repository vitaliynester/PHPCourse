<?php

namespace Lab3;

use DateTime;
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
     * Запись новой заявки в БД
     *
     * @param array $data (массив с информацией о заявке для записи в БД)
     *
     * @return array (ответ БД при добавлении записи)
     */
    public function writeToDb(array $data): array
    {
        // Формируем строку для добавления данных в БД
        $sql = 'INSERT INTO ' . $this->settings['db_table'] .
            ' (last_name, first_name, patronymic, email, phone, content) ' .
            'VALUES (:last_name, :first_name, :patronymic, :email, :phone, :content)';
        try {
            // Устанавливаем аттрибут на получение только исключений и выше
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Подготавливаем SQL запрос
            $sth = $this->pdo->prepare($sql);
            // Мапим данные к запросу и выполняем его
            $success = $sth->execute($data);
            // Создаем массив для создания ответа
            $response = [];
            // Если получилось добавить запись в БД
            if ($success) {
                // Формируем тему письма
                $subject = '=?utf-8?B?' . base64_encode('Новая заявка с формы') . '?=';
                // Формируем текст сообщения
                $message = $data['content'];
                // Формируем хеддер для отправки письма
                $headers = 'From: ' . $data['email'] .
                    "\r\nReply-to: " . $data['email'] .
                    "\r\nContent-type: text/html; charset=utf-8";

                // Пытаемся отправить письмо на почту менеджера
                mail($this->settings['manager_email'], $subject, $message, $headers);

                // Получаем текущее время
                $time = new DateTime();
                // Формируем тело ответа
                $response['first_name'] = $data['first_name'];
                $response['last_name'] = $data['last_name'];
                $response['patronymic'] = $data['patronymic'];
                $response['email'] = $data['email'];
                $response['phone'] = $data['phone'];
                $response['content'] = $data['content'];
                $response['created_at'] = $time->modify('+1 hour')
                    ->modify('+30 minute')
                    ->format('H:i:s d-m-Y');
            } else {
                // Если не получилось добавить в БД, то получаем текст ошибки
                $regexMatch = [];
                preg_match(
                    '/Raise exception: 7 ERROR:  (.*?)\nCONTEXT:/',
                    $sth->errorInfo()[2],
                    $regexMatch,
                    PREG_OFFSET_CAPTURE
                );

                return ['error' => $regexMatch[1][0]];
            }

            return $response;
        } catch (Exception $e) {
            $regexMatch = [];
            preg_match(
                '/Raise exception: 7 ERROR:  (.*?)\nCONTEXT:/',
                $e->getMessage(),
                $regexMatch,
                PREG_OFFSET_CAPTURE
            );

            return ['error' => $regexMatch[1][0]];
        }
    }
}
