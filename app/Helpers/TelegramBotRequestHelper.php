<?php
namespace App\Helpers;

use Throwable;
use App\Models\Song;
use GuzzleHttp\Client;

class TelegramBotRequestHelper {
    public static function sendSearchResults($request){
         
        try{
            $update = $request->all();
                $input = $update['message'];
    
                $chatId = $input['chat']['id'];
                $userInput = $input['text'];
    
                $songs = Song::where('title', 'LIKE', '%'.$userInput.'%')->get();
    
                // if($songs){
                //     $song =str_replace("<p>", "\n",$songs->verses);
                //     $song =str_replace("<br>", "\n\n",$song);
                //     $song =str_replace("</p>", " ",$song);
                // }else{
                //     $song = 'no records found!';
                // }
    
                // $client = new Client();
    
                // $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                //     'json'=>[
                //         'chat_id'=>$chatId,
                //         'text'=> "Title: $songs->title"
                //     ]
                // ]);
                // $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                //     'json'=>[
                //         'chat_id'=>$chatId,
                //         'text'=> $songs->author?"Author:$songs->author":"Author: pending"
                //     ]
                // ]);
                // $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                //     'json'=>[
                //         'chat_id'=>$chatId,
                //         'text'=> $song
                //     ]
                // ]);

                $inlineKeyboard = [
                    [
                       
                    ],
                ];

                foreach($songs as $song) {
                    array_push($inlineKeyboard[0], ['text' => "$song->title by $song->author", 'callback_data' => "$song->id"]);
                    }
                
                $replyMarkup = [
                    'inline_keyboard' => $inlineKeyboard
                ];
                
                $data = [
                    'chat_id' => $chatId,
                    'text' => "Please choose an option:",
                    'reply_markup' => json_encode($replyMarkup)
                ];

                $client = new Client();
    
                $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                    'json'=>$data
                ]);
                
                
        }catch(Throwable $error) {
            $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $error->getMessage()
                ]
            ]);
        }
    }

    public static function sendMusicLyric($request) {
        try{
            $update = $request->all();
                $input = $update['callback_query'];
    
                $message = $update['message'];
                $userInput = $input['data'];
                $chatId = $message['chat']['id'];
    
                $songs = Song::where('title', 'LIKE', '%'.$userInput.'%')->first();
    
                if($songs){
                    $song =str_replace("<p>", "\n",$songs->verses);
                    $song =str_replace("<br>", "\n\n",$song);
                    $song =str_replace("</p>", " ",$song);
                }else{
                    $song = 'no records found!';
                }
    
                $client = new Client();
    
                $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> "Title: $songs->title"
                    ]
                ]);
                $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> $songs->author?"Author:$songs->author":"Author: pending"
                    ]
                ]);
                $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                    'json'=>[
                        'chat_id'=>$chatId,
                        'text'=> $song
                    ]
                ]);
        }catch(Throwable $error) {
            $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $error->getMessage()
                ]
            ]);
        }
    }
}