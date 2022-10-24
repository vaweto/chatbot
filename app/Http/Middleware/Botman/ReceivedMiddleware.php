<?php

namespace App\Http\Middleware\Botman;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class ReceivedMiddleware implements Received
{

    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        //$message->setText('vagelis');
        $message->addExtras('timestamp', time());
        return $next($message);
    }
}
