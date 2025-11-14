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
        try{
        $bot_token = env('BOT_TOKEN');

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
            $client->post("https://api.telegram.org/$bot_token/sendMessage", [
                'json'=>[
                    'chat_id'=>$chatId,
                    'text'=> $request,
                ]
            ]);
           }
        }catch(Throwable $ex){
            return $ex->getMessage();
        }
   
    }
    
}
