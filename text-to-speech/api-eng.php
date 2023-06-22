<?php


$api_key = ""; // your api key get api key to https://rapidapi.com/k_1/api/large-text-to-speech 

class Texttospeach
{
    public $text = "";
    public $api_key = "";
    public function sendrequest()
    {

        $curl = curl_init();
        if (!$this->api_key){
            return "api key not found !";
        }
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://large-text-to-speech.p.rapidapi.com/tts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'text' => "{$this->text}"
            ]),
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: large-text-to-speech.p.rapidapi.com",
                "X-RapidAPI-Key: {$this->api_key}",
                "content-type: application/json"
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

    public function getresponse($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://large-text-to-speech.p.rapidapi.com/tts?id=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: large-text-to-speech.p.rapidapi.com",
                "X-RapidAPI-Key: {$this->api_key}"
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
}

$tts = new Texttospeach();
$tts->text = "hello, how can i help you ?";
$tts->api_key = $api_key;

$send_req = json_decode($tts->sendrequest());

if ($send_req->status == "processing") {
    $id = $send_req->id;
    $status = 1;
    while ($status) {
        $get_resp = json_decode($tts->getresponse($id));
        if ($get_resp->status == "success") {
            $audio_url = $get_resp->url;
            $status = 0;
        } else {
            echo "waiting...\n";
            sleep(3);
            continue;
        }
    }
} else {
    $audio_url = $send_req->url;
}

$dir_name = "data/";
$file_name = uniqid() . date("-d-m-Y") . ".wav";

$req = file_put_contents($dir_name . $file_name, file_get_contents($audio_url));

if ($req) {
    echo "successfully saved.";
} else {
    echo "oops something went wrong.";
}
