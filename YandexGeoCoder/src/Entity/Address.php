<?php

namespace App\Entity;

class Address
{
    // Полный корректный адрес
    private string $fullAddress;
    // Страна указанного адреса
    private string $country;
    // Центр указанного адреса
    private string $province;
    // Область указанного адреса
    private string $area;
    // Город указанного адреса
    private string $locality;
    // Округ указанного адреса
    private string $district;
    // Улица указанного адреса
    private string $street;
    // Дом указанного адреса
    private string $house;
    // Индекс почты указанного адреса
    private string $postalCode;
    // Широта указанного адреса
    private string $latitude;
    // Долгота указанного адреса
    private string $longitude;

    /**
     * Конструктор сущности "Адрес"
     *
     * @param array $data (массив с полученным ответом от сервиса Яндекса)
     */
    public function __construct(array $data)
    {
        // Устанавливаем полный адрес
        $this->setFullAddress($data);
        // Устанавливаем дополнительные значения компоненте сущности
        self::initFields($data);
        // Устанавливаем почтовый индекс адреса
        $this->setPostalCode($data);
        // Устанавливаем широту полученного адреса
        $this->setLatitude($data);
        // Устанавливаем долготу полученного адреса
        $this->setLongitude($data);
    }

    /**
     * Метод для указания полного адреса
     *
     * @param array $data (массив с полученными данными из GeoCoder)
     */
    public function setFullAddress(array $data): void
    {
        $currentAddress = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['formatted'];
        if (isset($currentAddress)) {
            $this->fullAddress = $currentAddress;
        }
    }

    /**
     * Метод для установки дополнительных полей в сущность "Адрес"
     *
     * @param array $data (массив с полученным ответом от сервиса Яндекс)
     */
    private function initFields(array $data)
    {
        // Получаем информацию о дополнительных полях в полученном ответе от сервиса Яндекс
        $components = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'];
        // Проверяем наличие дополнительных полей в ответе
        if (!isset($components)) {
            // Если дополнительных полей нет, то выходим из функции
            return;
        }
        // Проходимся по каждому дополнительному полю
        foreach ($components as $component) {
            // По значению ключа устанавливаем значения соответствующим полям сущности
            switch ($component['kind']) {
                case 'country':
                    $this->setCountry($component['name']);
                    break;
                case 'province':
                    $this->setProvince($component['name']);
                    break;
                case 'area':
                    $this->setArea($component['name']);
                    break;
                case 'locality':
                    $this->setLocality($component['name']);
                    break;
                case 'street':
                    $this->setStreet($component['name']);
                    break;
                case 'house':
                    $this->setHouse($component['name']);
                    break;
                case 'district':
                    $this->setDistrict($component['name']);
                    break;
            }
        }
    }

    /**
     * Метод для указания нового значения страны
     *
     * @param string $country (новое значение страны)
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * Метод для указания нового значения центра
     *
     * @param string $province (новое значение центра)
     */
    public function setProvince(string $province): void
    {
        $this->province = $province;
    }

    /**
     * Метод для указания новой области
     *
     * @param string $area (новое значение области)
     */
    public function setArea(string $area): void
    {
        $this->area = $area;
    }

    /**
     * Метод для указания нового города
     *
     * @param string $locality (новое значение города)
     */
    public function setLocality(string $locality): void
    {
        $this->locality = $locality;
    }

    /**
     * Метод для указания новой улицы
     *
     * @param string $street (новое значение улицы)
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * Метод для указания нового дома
     *
     * @param string $house (новое значение дома)
     */
    public function setHouse(string $house): void
    {
        $this->house = $house;
    }

    /**
     * Метод для указания нового округа
     *
     * @param string $district (новое значение округа)
     */
    public function setDistrict(string $district): void
    {
        $this->district = $district;
    }

    /**
     * Метод для указания нового почтового индекса
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setPostalCode(array $data): void
    {
        $currentPostalCode = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['postal_code'];
        if (isset($currentPostalCode)) {
            $this->postalCode = $currentPostalCode;
        }
    }

    /**
     * Метод для указания новой широты
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setLatitude(array $data): void
    {
        $currentPosition = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
        if (isset($currentPosition)) {
            $latitude = explode(' ', $currentPosition)[0];
            $this->latitude = $latitude;
        }
    }

    /**
     * Метод для указания новой долготы
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setLongitude(array $data): void
    {
        $currentPosition = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
        if (isset($currentPosition)) {
            $longitude = explode(' ', $currentPosition)[1];
            $this->longitude = $longitude;
        }
    }

    /**
     * Метод для получения данных текущей сущности в виде массива
     *
     * @return array (ассоциативный массив с информацией о текущей сущности)
     */
    public function getDataArray(): array
    {
        $result = [];
        if (isset($this->fullAddress)) {
            $result['full_address'] = ['Полный адрес: ', $this->fullAddress];
        }
        if (isset($this->country)) {
            $result['country'] = ['Страна:', $this->country];
        }
        if (isset($this->province)) {
            $result['province'] = ['Центр: ', $this->province];
        }
        if (isset($this->area)) {
            $result['area'] = ['Область: ', $this->area];
        }
        if (isset($this->locality)) {
            $result['locality'] = ['Город: ', $this->locality];
        }
        if (isset($this->district)) {
            $result['district'] = ['Округ: ', $this->district];
        }
        if (isset($this->street)) {
            $result['street'] = ['Улица: ', $this->street];
        }
        if (isset($this->house)) {
            $result['house'] = ['Дом: ', $this->house];
        }
        if (isset($this->postalCode)) {
            $result['postal_code'] = ['Индекс: ', $this->postalCode];
        }
        if (isset($this->latitude)) {
            $result['latitude'] = ['Широта: ', $this->latitude];
        }
        if (isset($this->longitude)) {
            $result['longitude'] = ['Долгота: ', $this->longitude];
        }

        return $result;
    }

    /**
     * Метод для получения координат в подготовленном формате для запроса
     *
     * @return string (координаты в формате для выполнения запроса к получению списка станций метро)
     */
    public function getCoordinateRow(): string
    {
        return $this->latitude . ',' . $this->longitude;
    }
}
