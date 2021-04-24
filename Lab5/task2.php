<?php

/**
 * Функция для чтения данных из файла (построчно)
 * @param string $fileName (название файла откуда необходимо считать данные)
 * @return array (строки данных)
 */
function readFromFile(string $fileName): array
{
    // Создаем массив куда будем записывать результат чтения данных
    $result = array();
    // Открываем файл на чтение
    $handle = fopen($fileName, "r");
    // Если удалось открыть файл
    if ($handle) {
        // Читаем построчно
        while (($buffer = fgets($handle, 4096)) !== false) {
            // Записываем прочитанную строку в массив
            array_push($result, $buffer);
        }
        // Закрываем файл после прочтения
        fclose($handle);
    }
    // Возвращаем массив с прочитанными данными
    return $result;
}

/**
 * Проверяем количество аргументов строки
 * $argv[1] - файл откуда будем считывать данные
 * $argv[2] - файл куда будем записывать данные
 */
if ($argc == 3) {
    // Регулярное выражение для нахождения номера законопроекта
    $pattern = '/(\d+)(-*)(\d+)/';
    // Получаем название файла откуда будем считывать данные
    $inputFile = $argv[1];
    // Получаем название файла куда необходимо записать результат
    $outputFile = $argv[2];
    // Получаем массив строк из исходного файла
    $inputData = readFromFile($inputFile);
    // Создаем массив для хранения результата
    $result = array();
    // Проходимся по каждой ссылке из файла
    foreach ($inputData as $url) {
        // Создаем массив для хранения совпадений
        $matches = array();
        // Ищем совпадения по регулярному выражению и записываем в массив
        preg_match($pattern, $url, $matches);
        // Добавляем в результирующий массив значение нового сайта с идентификатором из регулярки
        array_push($result, "https://sozd.duma.gov.ru/bill/$matches[0]\r\n");
    }
    // Записываем результат в ранее указанный файл
    file_put_contents($outputFile, $result);
    // Выводим сообщение об успехе в консоль
    echo "Данные успешно преобразованы!\r\n";
} else {
    // Если не указан аргумент, то выводим сообщение
    echo "Укажите названия файлов для чтения и записи!\r\n";
}

