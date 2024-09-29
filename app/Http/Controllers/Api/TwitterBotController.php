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
        // $update=$request->all();
        // if(isset($update['message'])) {
        //  return TelegramBotRequestHelper::sendSearchResults($request);
        // }else{
        //     return 'there was an issue!';
        // }

        // if(isset($request->callback_query->data)) {
            return TelegramBotRequestHelper::sendSearchResults($request);
        //    }else{
        //        return 'there was an issue!';
        //    }
   
    }
    
}
