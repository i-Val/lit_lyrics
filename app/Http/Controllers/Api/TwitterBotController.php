<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TwitterBotController extends Controller
{
    public function get_lyrics(Request $request){
        $update = $request->all();

        if(isset($update['message'])) {
            $input = $update['message'];

            $chatId = $input['chat']['id'];
            $userInput = $input['text'];

            $song = Song::where('title', 'LIKE', "%$userInput%")->first();

            $client = new Client();

            $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $song->verses
                ]
            ]);
        }
    }
}
