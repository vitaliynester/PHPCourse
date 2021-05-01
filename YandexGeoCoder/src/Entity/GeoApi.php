<?php

namespace App\Entity;

include_once 'Address.php';
include_once 'Metro.php';

class GeoApi
{
    /**
     * Метод для получения массива с информацией об корректном адресе и ближайших станций метро
     *
     * @param string $address (введенное значение адреса для поиска)
     *
     * @return false|string (результирующий ассоциативный массив в формате JSON)
     */
    public static function getAllData(string $address)
    {
        // Формируем результирующий массив
        $resultData = [];
        // Получаем правильное значение введенного адреса
        $correctAddress = self::getCurrentAddress($address);
        // Если не получилось получить корректное значение адреса
        if (0 == count($correctAddress->getDataArray())) {
            // То в список станций метро помещаем пустой массив
            $metroList = new Metro([]);
        } else {
            // Если полученный адрес корректный, то получаем ближайшие станции метро
            $metroList = self::getNearMetros($correctAddress->getCoordinateRow());
        }
        // В результирующий массив помещаем полученные значения
        $resultData['address'] = $correctAddress->getDataArray();
        $resultData['metro'] = $metroList;
        // Возвращаем результирующий массив в формате JSON
        return json_encode($resultData);
    }

    /**
     * Метод для получения корректного адреса
     *
     * @param string $address (исходное значение адреса для преобразования)
     *
     * @return Address (сущность адреса)
     */
    private static function getCurrentAddress(string $address): Address
    {
        // Получаем значения URL адреса для выполнения запроса и ключ доступа
        $config = self::getConfig();
        $url = $config['api_url'];
        $key = $config['api_key'];

        // Формируем массив параметров для выполнения запросов
        $params = [
            'geocode' => $address,
            'apikey' => $key,
            'format' => 'json',
            'results' => 1,
        ];

        // Формируем результирующий адрес для получения данных
        $requestUrl = "$url?" . http_build_query($params);

        // Выполняем запрос
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        // Получаем данные после выполнения запроса
        $result = curl_exec($ch);
        curl_close($ch);
        // Преобразовываем полученные данные в ассоциативный массив
        $json = json_decode($result, true);

        // Возвращаем новую сущность адреса
        return new Address($json);
    }

    /**
     * Метод для получения массива с конфигурацией
     *
     * @return mixed (массив с конфигурацией: URL и ключ доступа)
     */
    private static function getConfig()
    {
        return require 'config.php';
    }

    /**
     * Метод для получения ближайших станций метро
     *
     * @param string $position (координаты для поиска в формате "long,lat")
     *
     * @return array (массив со списком станциях метро)
     */
    private static function getNearMetros(string $position): array
    {
        // Получаем значения URL адреса для выполнения запроса и ключ доступа
        $config = self::getConfig();
        $url = $config['api_url'];
        $key = $config['api_key'];

        // Формируем массив параметров для выполнения запросов
        $params = [
            'geocode' => $position,
            'apikey' => $key,
            'format' => 'json',
            'kind' => 'metro',
        ];

        // Формируем результирующий адрес для получения данных
        $requestUrl = "$url?" . http_build_query($params);

        // Выполняем запрос
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        // Получаем данные после выполнения запроса
        $result = curl_exec($ch);
        curl_close($ch);
        // Преобразовываем полученные данные в ассоциативный массив
        $json = json_decode($result, true);
        // Получаем список ближайших станций метро
        $iterableData = $json['response']['GeoObjectCollection']['featureMember'];
        // Формируем результирующий массив со станциями метро
        $result = [];
        // Проходимся по каждой станцией метро
        foreach ($iterableData as $rowData) {
            // Создаем новую сущность станции метро
            $metro = new Metro($rowData);
            // Добавляем в результирующий массив полученную сущность станции метро
            $result[] = $metro->getDataArray();
        }
        // Возвращаем результирующий массив станций метро
        return $result;
    }
}
