<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Song;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\TelegramBotRequestHelper;

class TwitterBotController extends Controller
{
    public function get_lyrics(Request $request){
        $update=$request->all();
        if(isset($update['message'])) {
         return TelegramBotRequestHelper::sendSearchResults($request);
        }

        
        if (strpos($update['callback_query']['data'], 'music-sheets') !== false) {
            // The callback_data contains "music-sheets"
            return TelegramBotRequestHelper::sendFileToUser($request);
        }

        if(isset($update['callback_query'])) {
            return TelegramBotRequestHelper::sendMusicLyric($request); 
           }else{
            $update = $request->all();
            $input = $request->callback_query->message;
            $client = new Client();
    
                $chatId = $input['chat']['id'];
            $client->post("https://api.telegram.org/bot7806842577:AAGGBAynHIJBkPL-HiR2pLMneNOKOv5is0g/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $request
                ]
            ]);
           }
   
    }
    
}
