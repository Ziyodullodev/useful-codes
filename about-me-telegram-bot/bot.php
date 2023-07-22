<?php

$token = 'bot-token';

/*

bot webhook orqali ishlash uchun mo'ljallangan
author: Ziyodullo ALiyev
TG: @ZiyoDev

*/

function bot($method, $datas = [])
{
    global $token;
    $url = "https://api.telegram.org/bot" . $token . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message ?? null;
$callback_query = $update->callback_query ?? null;
$command_text = "/loading - turli xil loading kolleksiyasi\n/aboutme - O'zim haqimda";


if (isset($callback_query)) {
    $data = $update->callback_query->data ?? null;
    $cid = $update->callback_query->message->chat->id ?? null;
    $chat_id = $update->callback_query->message->chat->id ?? null;
    $mid = $update->callback_query->message->message_id ?? null;
    $text = $update->callback_query->message->text ?? null;
    $keyboard = $update->callback_query->message->reply_markup ?? null;

    $book_loading = [
        'Kitob izlayapman. 🔎 • • •',
        'Kitob izlayapman. • 🔎 • •',
        'Kitob izlayapman. • • 🔎 •',
        'Kitob izlayapman. • • • 🔎',
        'Kitob izlayapman. • • • 🔍',
        'Kitob izlayapman. • • 🔍 •',
        'Kitob izlayapman. •  🔍 • •',
        'Kitob izlayapman. 🔍 • • •'
    ];

    if ($data == "booksearch") {
        foreach ($book_loading as $load) {
            bot('editmessagetext', [
                'chat_id' => $cid,
                'message_id' => $mid,
                'text' => $load,
            ]);
            sleep(0.5);
        }
        bot('editmessagetext', [
            'chat_id' => $cid,
            'message_id' => $mid,
            'text' => $text,
            'reply_markup' => json_encode($keyboard),
        ]);
    }
    $loading = [
        'block' => [
            'black' => [
                'load' => '◼️',
                'block' => "▪️"
            ],
            'white' => [
                'load' => '◽️',
                'block' => "▫️"
            ]
        ]
    ];
    if ($data == "block-black") {
        $first = $loading['block']['black']['load'];
        $last = $loading['block']['black']['block'];
        loading($first, $last);
    } else if ($data == "block-white") {
        $first = $loading['block']['white']['load'];
        $last = $loading['block']['white']['block'];
        loading($first, $last);
    }
    else {
        bot('editmessagetext', [
            'chat_id' => $cid,
            'message_id' => $mid,
            'text' => "Bot ishlatish uchun komandalar\n\n" . $command_text,
        ]);
    }
} elseif (isset($message)) {
    $text = $message->text ?? null;
    $name = $message->from->first_name ?? null;
    $from_id = $message->from->id ?? null;
    $cid = $message->chat->id ?? null;
    $type = $message->chat->type ?? null;
    $mid = $message->message_id ?? null;

    $about_me = "Aliyev Ziyodullo 2001-08-30";
    if ($text == "/start") {
        bot('sendmessage', [
            'chat_id' => $cid,
            'text' => "Assalomu alaykum botga xush kelibsiz\n\n" . $command_text,
        ]);
    } elseif ($text == "/loading") {
        bot('sendmessage', [
            'chat_id' => $cid,
            'text' => "loadings...",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "🔎 Book searching...", 'callback_data' => 'booksearch']],
                    [['text' => "◼️ Block", 'callback_data' => 'block-black']],
                    [['text' => "◽️ Block", 'callback_data' => 'block-white']],
                    [['text' => "GO 🔙", 'callback_data' => 'back']],
                ]
            ])
        ]);
    } elseif ($text == "/aboutme") {
        bot('sendmessage', [
            'chat_id' => $cid,
            'text' => $about_me,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "Telegram", 'url' => 'tg://user?id=848796050']],
                    [['text' => "Github", 'url' => 'https://github.com/Ziyodullodev']],
                    [['text' => "Linkedin", 'url' => 'http://linkedin.com/in/Ziyodullo']],
                    [['text' => "Instagram", 'url' => 'http://instagram.com/Ziyo.Dev']],
                    [['text' => "GO 🔙", 'callback_data' => 'back']],
                ]
            ])
        ]);
    } else {
        bot('sendmessage', [
            'chat_id' => $cid,
            'text' => "Bot ishlatish uchun komandalar\n\n" . $command_text,
        ]);
    }
}


function loading($first, $last)
{
    global $cid, $mid, $text, $keyboard;
    $all = 10;
    $load = 1;
    $load_text = '';
    while ($load <= $all) {
        $load_text = str_repeat($first, $load) . str_repeat($last, $all - $load);
        bot('editmessagetext', [
            'chat_id' => $cid,
            'message_id' => $mid,
            'text' => $load_text,
        ]);
        sleep(0.5);
        $load++;
    }
    bot('editmessagetext', [
        'chat_id' => $cid,
        'message_id' => $mid,
        'text' => $text,
        'reply_markup' => json_encode($keyboard),
    ]);
}
