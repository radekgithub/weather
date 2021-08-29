<?php

namespace App\Factory;

use App\Model\AerisWeather;
use App\Model\OpenWeatherMap;

class TemperatureFactory
{
    public static function checkTemperature($country, $city): ?float
    {
        $temperatures = [];
        $openWeatherMap = new OpenWeatherMap();
        if ($temperature = $openWeatherMap->checkTemperature($country, $city)) {
            $temperatures[] = $temperature;
        }
        $aerisWeather = new AerisWeather();
        if ($temperature = $aerisWeather->checkTemperature($country, $city)) {
            $temperatures[] = $temperature;
        }
        if ($count = count($temperatures)) {
            return round(array_sum($temperatures) / $count, 2);
        }
        return null;
    }
}
