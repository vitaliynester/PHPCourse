<?php

use App\Entity\GeoApi;

include_once './Entity/GeoApi.php';

echo GeoApi::getAllData($_POST['address']);
