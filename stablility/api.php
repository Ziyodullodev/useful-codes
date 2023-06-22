<?php

class StableApi
{
    private $url = "https://api.stability.ai/v1/generation/";
    public $type = "/text-to-image";
    public $engineId = "";
    public $api_key = "";
    public $text = "";
    public $height = "512";
    public $width = "512";
    public $cfg_scale = "7";
    public $clip_guidance_preset = "NONE";
    public $sampler = "";
    public $samples = "1";
    public $seed = "0";
    public $steps = "50";
    public $style_preset = "3d-model";
    public $extras = "";
    public function generate()
    {

        $url = $this->url.$this->engineId.$this->type;

        $data = [
            "cfg_scale" => $this->cfg_scale,
            "clip_guidance_preset" => $this->clip_guidance_preset,
            "height" => $this->height,
            "width" => $this->width,
            "sampler" => $this->sampler,
            "samples" => $this->samples,
            "seed" => $this->seed,
            "steps" => $this->steps,
            "style_preset" => $this->style_preset,
            // "extras" => $this->extras,
            "text_prompts" => [
                [
                    "text" => $this->text,
                    "weight" => 0.5
                ]
            ]
        ];
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->api_key
        ];
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        // Handle the response
        if ($response === false) {
            echo "Error occurred: " . curl_error($curl);
        } else {
            // return $response;
            $imageDataArray = json_decode($response, true);
            // Iterate through each image data
            foreach ($imageDataArray as $imageData) {
                foreach ($imageData as $image) {
                    // Get the base64-encoded image data
                    $base64Data = $image['base64'];

                    // Decode the base64 data
                    $imageData = base64_decode($base64Data);

                    // Generate a unique file name or use any desired naming convention
                    $fileName = uniqid() . '.jpg';

                    // Specify the file path where you want to save the image

                    // Save the image data to the file
                    $directory = 'images/';

                    // Check if the directory exists, and create it if it doesn't
                    if (!is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    // Proceed with writing the file
                    $file = $directory . $fileName;
                    file_put_contents($file, $imageData);
                    // Check if the file was saved successfully
                    // if ($file !== false) {
                    //     return $file;
                    // } else {
                    //     return false;
                    // }
                    var_dump($file);
                }
            }
            return "lololol";
        }

    }
    public function balance()
    {
        $api_host = 'https://api.stability.ai';
        $url = $api_host . '/v1/user/balance';

        $headers = [
            'Authorization: Bearer ' . $this->api_key,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($status_code !== 200) {
            throw new Exception('Non-200 response: ' . $response);
        }

        // Do something with the payload...
        $payload = json_decode($response, true);

        // Access the data in the payload
        $balance = $payload['credits'];

        return $balance;
    }
}


$stable = new StableApi();
$apiKey = "your api key"; // your get api key to: https://platform.stability.ai/docs/getting-started/authentication
$engineId = "stable-diffusion-xl-beta-v2-2-2"; // esrgan-v1-x2plus
$text = "A spiky fierce porcupine"; // your text
$stable->api_key = $apiKey;
$stable->engineId = $engineId;
$stable->text = $text;
$stable->height = 512; // default: 512
$stable->width = 512; // default: 512
$stable->cfg_scale = 7; // default: 7
$stable->clip_guidance_preset = "FAST_BLUE"; // ['FAST_BLUE', 'FAST_GREEN', 'SIMPLE', 'SLOW', 'SLOWER', 'SLOWEST'] default : NONE
$stable->sampler = "K_DPM_2_ANCESTRAL"; // [ 'DDIM', 'DDPM', 'K_DPMPP_2M', 'K_DPMPP_2S_ANCESTRAL', 'K_DPM_2', 'K_DPM_2_ANCESTRAL', 'K_EULER' 'K_EULER_ANCESTRAL', 'K_HEUN', 'K_LMS' ]
$stable->samples = 1; // default: 1
$stable->seed = 0; // default: 0
$stable->steps = 50; // default: 50
$stable->style_preset = "3d-model"; /* ['3d-model', 'analog-film', 'anime', 'cinematic', 'comic-book', 'digital-art', 'enhance', 'fantasy-art', 'isometric',
'line-art', 'low-poly', 'modeling-compound', 'neon-punk', 'origami', 'photographic', 'pixel-art', 'tile-texture'] */

$stable->extras = ""; // default: "",  Extra parameters passed to the engine. These parameters are used for in-development or experimental features and may change without warning, so please use with caution.


$photoPath = $stable->generate();
echo "your photo path: ".$photoPath;