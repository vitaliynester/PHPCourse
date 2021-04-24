<?php

namespace Lab3;

use DateTime;
use Exception;
use PDO;
use PDOException;
use PHPMailer\PHPMailer\PHPMailer;

require  '../lib/src/Exception.php';
require '../lib/src/PHPMailer.php';
require '../lib/src/SMTP.php';

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
                // Пытаемся отправить письмо на почту менеджера
                self::sendMail($data);

                // Получаем текущее время
                $time = new DateTime();
                // Формируем тело ответа
                $response['first_name'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['first_name']);
                $response['last_name'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['last_name']);
                $response['patronymic'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['patronymic']);
                $response['email'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['email']);
                $response['phone'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['phone']);
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

    /**
     * Функция для отправки письма на почту
     *
     * @param array $data (данные для отправки на почту)
     * @throws \PHPMailer\PHPMailer\Exception (ошибка отправки сообщения)
     */
    private function sendMail(array $data): void
    {
        $mail = new PHPMailer(false);
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $this->settings['manager_login'];
        $mail->Password   = $this->settings['manager_password'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        //Recipients
        $mail->setFrom($this->settings['manager_email'], 'Менеджер формы');
        $mail->addAddress($this->settings['manager_email'], 'Менеджер формы');
        $mail->addAddress($data['email']);

        //Content
        $mail->isHTML(false);
        $mail->Subject = 'Новая заявка с формы';
        $mail->Body    = $data['content'];

        $mail->send();
    }
}
