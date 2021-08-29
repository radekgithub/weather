<?php

namespace App\Model;

class OpenWeatherMap implements ApiInterface
{
    const URL = 'http://api.openweathermap.org/data/2.5/weather?q=[LOCATION]&units=metric&appid=[APP_ID]';

    public function checkTemperature($country, $city): ?float
    {
        $location = $city . ',' . $country->getIsoCode();
        $url = str_replace('[LOCATION]', $location, self::URL);
        $url = str_replace('[APP_ID]', $_ENV['API_OPENWEATHER_APPID'], $url);
        $data = json_decode(file_get_contents($url));
        if ($data->main) {
            return $data->main->temp;
        }
        return null;
    }
}
