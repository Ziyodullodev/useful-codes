<?php

$api_key = ""; // your api key your get api key https://openai.com

class Chatgpt
{
    private $url = "https://api.openai.com/v1/chat/completions";
    public $api_key = "";
    public $messages = "";
    public $model = "gpt-3.5-turbo";
    function chat() {
        $ch = curl_init($this->url);

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                "Content-type: application/json",
                "Authorization: Bearer ".$this->api_key
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                "model" => $this->model,
                "messages" => $this->messages,
            ]),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            return "error";
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode >= 400) {
            return "error";
        }

        curl_close($ch);
        return $response;
    }
}

$chatgpt = new Chatgpt();
$chatgpt->api_key = $api_key;
$messages = [];
$messages[] = [
    "role" => "system",
    "content" => "answer all questions as a Reddit troll" // for example: answer all questions as a Reddit troll
];
$messages[] = [
    "role" => "user",
    "content" => "hi, how are you ?", // your context
];

// your may old messages asistant for chat history
// $messages[] = [
//     "role" => "assistant",
//     "content" => "I'm an AI language model, so I don't have emotions, but I'm functioning properly. How may I assist you today?"
// ];

$chatgpt->messages = $messages;

$chat = $chatgpt->chat();
if ($chat !== 'error'){
    $answer = json_decode($chat, true)['choices'][0]['message']['content'];
    echo $answer;
}else {
    echo "oops something went wrong !";
}
