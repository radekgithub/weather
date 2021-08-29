<?php

namespace App\Model;

class AerisWeather implements ApiInterface
{
    const URL = 'https://api.aerisapi.com/conditions/[LOCATION]?format=json&plimit=1&filter=1min&client_id=[CLIENT_ID]&client_secret=[CLIENT_SECRET]';

    public function checkTemperature($country, $city): ?float
    {
        $location = $city . ',' . $country->getIsoCode();
        $url = str_replace('[LOCATION]', $location, self::URL);
        $url = str_replace('[CLIENT_ID]', $_ENV['API_AERISWEATHER_ACCESSID'], $url);
        $url = str_replace('[CLIENT_SECRET]', $_ENV['API_AERISWEATHER_SECRETKEY'], $url);
        $data = json_decode(file_get_contents($url));
        if ($data->success) {
            return $data->response[0]->periods[0]->tempC;
        }
        return null;
    }
}
