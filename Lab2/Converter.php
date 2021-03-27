<?php

// Подключаем класс БД
require_once "Database.php";

class Converter
{
    // Путь до XML файла, откуда будем читать данные
    private string $xmlFilePath;
    // Экземпляр класса БД для взаимодействия с записанными данными
    private Database $db;

    /**
     * Конструктор класса конвертера
     * @param string $xmlFilePath (путь до XML файла откуда будем считывать данные)
     * @throws Exception (Ошибка отсутствия указанного файла и ошибка подключения к БД)
     */
    public function __construct(string $xmlFilePath)
    {
        $this->xmlFilePath = $xmlFilePath;
        // Проверяем существование указанного файла
        if (!file_exists($this->xmlFilePath)) {
            throw new Exception("Ошибка! Указанный файл не существует!");
        }
        try {
            // Создаем новый экземпляр класса БД
            $this->db = new Database();
        } catch (Exception $e) {
            // Если не получилось подключиться к БД, то бросаем исключение
            throw $e;
        }
    }

    /**
     * Конвертация данных из XML в БД
     * @return bool (результат конвертации (True - успех, False - неудача))
     * @throws Exception (Ошибка записи в БД)
     */
    public function convertFromXmlToDb(): bool
    {
        // Считываем данные из XML файла
        $xml = simplexml_load_file($this->xmlFilePath);
        // Проходимся по всем записям из XML файла
        foreach ($xml->record as $user) {
            $data = [
                'name' => (string)$user->name,
                'phone' => (string)$user->phone,
                'email' => (string)$user->email,
                'address' => (string)$user->address
            ];
            // Записываем запись об одном пользователе в БД
            $writeToDbResult = $this->db->writeToDb($data);
            // Если записать не получилось, то бросаем ошибку
            if (!$writeToDbResult) {
                throw new Exception("Ошибка! Пользователя " . $data['name'] . " не удалось записать в БД!\r\n");
            }
        }
        // Если получилось записать все данные в БД
        return true;
    }

    /**
     * Конвертация данных из БД в JSON файл
     * @param string $jsonFileName (название JSON файла для помещения результата)
     * @return bool (результат записи в файл (True - успех, False - неудача))
     * @throws Exception (Ошибка работы с файлом)
     */
    public function convertFromDbToJson(string $jsonFileName): bool
    {
        // Получаем все записи из БД в виде массива
        $dataFromDb = $this->db->getDataFromDb();
        // Перегоняем полученный массив в JSON формат
        $json = json_encode($dataFromDb);
        // Пытаемся записать в файл
        try {
            // Если получилось записать файл, то возвращаем результат
            return $this->writeToFile($jsonFileName, $json);
        } catch (Exception $e) {
            // Если не получилось записать данные в файл, то бросаем исключение
            throw $e;
        }
    }

    /**
     * Запись данных в указанный файл
     * @param string $fileName (название файла в который необходимо произвести запись)
     * @param string $data (данные для записи в файл)
     * @return bool (результат записи в файл (True - успех, False - неудача))
     */
    private function writeToFile(string $fileName, string $data): bool
    {
        // Пытаемся открыть файл и записать в него данные с последующим закрытием
        try {
            $fp = fopen($fileName, 'w');
            fwrite($fp, $data);
            fclose($fp);
            // Если запись в файл успешно завершена
            return true;
        } catch (Exception $e) {
            // Если не получилось записать в файл, то бросаем исключение
            throw $e;
        }
    }
}