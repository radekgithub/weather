<?php

namespace App\Model;

use App\Entity\Country;

interface ApiInterface
{
    public function checkTemperature(Country $country, string $city): ?float;
}
