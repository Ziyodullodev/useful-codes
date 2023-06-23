<?php

/*

author: Ziyodullo ALiyev
e-mail: mail@ziyodev.uz
web-site: ziyodev.uz

*/
function getmore($text)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.dictionaryapi.dev/api/v2/entries/en/" . urlencode($text));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    $result = json_decode($result);
    curl_close($curl);
    if ($result->title) {
        return false;
    }
    return $result[0];
}

function translate($text, $in, $out)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://translate.googleapis.com/translate_a/single?client=gtx&sl=$in&tl=$out&dt=t&q=" . urlencode($text));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    $result = json_decode($result);
    curl_close($curl);

    if (isset($result[0][0][0])) {
        return $result[0][0][0];
    } else {
        return false;
    }
}


$word = "hi, how can i help you ?";
$in = "en";
$to = "uz";
$translated = translate($word, $in, $to);
var_dump($translated);

/* get more function -- bu functionga biror ingilizcha so'z kiritsangiz
u sizga o'sha so'z haqida koproq ma'lumot beradi. 
misol uchun : hello 
uni us, uk holatda audio shakli va koplab sinonim va antonim ma'lumotlar beradi

dictionary url bergan author arab edi nomi ham esimda yoq lekin function o'zimga tegishli ;)
*/

$get_more = getmore("hello");
var_dump($get_more);