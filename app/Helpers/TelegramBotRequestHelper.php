<?php
namespace App\Helpers;

use Throwable;
use App\Models\Song;
use GuzzleHttp\Client;

class TelegramBotRequestHelper {
   

    public static function sendSearchResults($request){
        
         
        try{
            $bot_token = env('BOT_TOKEN');
            $client = new Client();
            $update = $request->all();
                $input = $update['message'];
    
                $chatId = $input['chat']['id'];
                $userInput = $input['text'];
    
                $songs = Song::where('title', 'LIKE', '%'.$userInput.'%')->get();
    
                if ($songs->count() > 0) {

                $inlineKeyboard = [];

                foreach($songs as $song) {
                    array_push($inlineKeyboard, [['text' => "$song->title by $song->author", 'callback_data' => "$song->id"]]);
                    }
                
                $replyMarkup = [
                    'inline_keyboard' => $inlineKeyboard
                ];
                
                $data = [
                    'chat_id' => $chatId,
                    'text' => "Please choose an option:",
                    'reply_markup' => json_encode($replyMarkup)
                ];

                
    
                $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                    'json'=>$data
                ]);
            }else {
                $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> "we don't have that in out database"
                    ]
                ]);
                $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> "Contact Iwuchukwu Valentine via: valentineiwuchukwu@outlook.com"
                    ]
                ]);
            }
                
                
        }catch(Throwable $error) {
            $client->post("https://api.telegram.org/$bot_token/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $error->getMessage()
                ]
            ]);
        }
    }

    public static function sendMusicLyric($request) {
        try{
            $bot_token = env('BOT_TOKEN');
                $update = $request->all();
                $input = $update['callback_query']['data'];
    
                $userInput = $input;
                $chatId = $update['callback_query']['message']['chat']['id'];
    
                $songs = Song::where('id',$userInput)->first();
    
                if($songs!=null){
                    $song =str_replace("<p>", "\n",$songs->verses);
                    $song =str_replace("<br>", " ",$song);
                    $song =str_replace("</p>", " ",$song);
                    $song =str_replace("&nbsp;", " ",$song);
                }else{
                    $song = 'no records found!';
                }
    
                $client = new Client();
    
                $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> "Title: $songs->title"
                    ]
                ]);
                $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> $songs->author?"Author:$songs->author":"Author: pending"
                    ]
                ]);
                $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> $song,
                        'parse_mode' => 'HTML'
                    ]
                ]);

                if ($songs->music_sheet !=null) {
                    $inlineKeyboard = [
                        [
                            ['text' => "Yes, dowload file!", 'callback_data' => "$songs->music_sheet"]
                        ],
                    ];

                    $replyMarkup = [
                        'inline_keyboard' => $inlineKeyboard
                    ];

                    $data = [
                        'chat_id' => $chatId,
                        'text' => "Do you wish to download the music score?:",
                        'reply_markup' => json_encode($replyMarkup)
                    ];
    
                    
        
                    $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                        'json'=>$data
                    ]);
                }
        }catch(Throwable $error) {
            $client->post("https://api.telegram.org/bot$bot_token/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $error->getMessage()
                ]
            ]);
        }
    }



    public static function sendFileToUser($request) {
        try {
            $update = $request->all();
            $input = $update['callback_query']['data'];

            $filePath = $input;

            $chatId = $update['callback_query']['message']['chat']['id'];

            $token = env('BOT_TOKEN');
            $url = "https://api.telegram.org/bot{$token}/sendDocument";
            $client = new Client();

        
                $client->post($url, [
                    'multipart' => [
                        [
                            'name'     => 'chat_id',
                            'contents' => $chatId
                        ],
                        [
                            'name'     => 'document',
                            'contents' => fopen(storage_path("app/$filePath"), 'r'),
                            'filename' => basename($filePath) 
                        ]
                    ]
                ]);
        } catch (Throwable $error) {
            $client->post("https://api.telegram.org/bot$token/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $error->getMessage()
                ]
            ]);
        }
    }

}