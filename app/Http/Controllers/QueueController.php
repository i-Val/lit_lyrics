<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendMessageJob;

class QueueController extends Controller
{

    
    public function dispatch(){
      

        SendMessageJob::dispatch('Hello from RabbitMQ');

    }
}
