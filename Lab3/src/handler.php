<?php

use Lab3\Database;

// Подключаем файл для работы с БД
include_once 'Database.php';

// Получаем данные переданные через POST
$lastName = $_POST['last_name'];
$firstName = $_POST['first_name'];
$patronymic = $_POST['patronymic'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['comment'];

try {
    // Создаем новый экземпляр БД
    $db = new Database();
    // Пытаемся записать данные в БД
    $response = $db->writeToDb([
        'last_name' => $lastName,
        'first_name' => $firstName,
        'patronymic' => $patronymic,
        'email' => $email,
        'phone' => $phone,
        'content' => $message,
    ]);
    // Если получилось, то возвращаем ответ
    echo json_encode($response);
} catch (Exception $e) {
    // Иначе - ошибку
    echo $e->getMessage();
}
