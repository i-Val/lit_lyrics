<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Throwable;

class TwitterBotController extends Controller
{
    public function get_lyrics(Request $request){
        try{
        $update = $request->all();

        if(isset($update['message'])) {
            $input = $update['message'];

            $chatId = $input['chat']['id'];
            $userInput = $input['text'];

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
        }
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
