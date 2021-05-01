<?php

namespace App\Entity;

class Metro
{
    // Полный адрес станции метро
    private string $fullAddress;
    // Долгота координаты станции метро
    private string $longitude;
    // Широта координаты станции метро
    private string $latitude;

    /**
     * Конструктор для сущности "Станция метро"
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function __construct(array $data)
    {
        // Устанавливаем полный адрес станции метро
        $this->setFullAddress($data);
        // Устанавливаем координату долготы станции метро
        $this->setLongitude($data);
        // Устанавливаем координату широты станции метро
        $this->setLatitude($data);
    }

    /**
     * Метод для установки полного адреса
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setFullAddress(array $data): void
    {
        $currentAddress = $data['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
        if (isset($currentAddress)) {
            $this->fullAddress = $currentAddress;
        }
    }

    /**
     * Метод для установки координаты долготы
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setLongitude(array $data): void
    {
        $currentPos = $data['GeoObject']['Point']['pos'];
        if (isset($currentPos)) {
            $currentLongitude = explode(' ', $currentPos)[1];
            $this->longitude = $currentLongitude;
        }
    }

    /**
     * Метод для установки координаты широты
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setLatitude(array $data): void
    {
        $currentPos = $data['GeoObject']['Point']['pos'];
        if (isset($currentPos)) {
            $currentLatitude = explode(' ', $currentPos)[0];
            $this->latitude = $currentLatitude;
        }
    }

    /**
     * Метод для преобразования текущих данных сущностей в массив
     *
     * @return array (ассоциативный массив с полями текущей сущности)
     */
    public function getDataArray(): array
    {
        $result = [];
        if (isset($this->fullAddress)) {
            $result['full_address'] = $this->fullAddress;
        }
        if (isset($this->longitude)) {
            $result['longitude'] = $this->longitude;
        }
        if (isset($this->latitude)) {
            $result['latitude'] = $this->latitude;
        }

        return $result;
    }
}
