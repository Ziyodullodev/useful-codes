<?php

function getwheather($city_name)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://weatherapi-com.p.rapidapi.com/current.json?q={$city_name}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: weatherapi-com.p.rapidapi.com",
            "X-RapidAPI-Key: 996bae0df7msh33de2c882089c7fp1d9724jsn41d2db2e6b7d"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
}
// write fergana, buxoro or something
var_dump(getwheather("buxoro"));