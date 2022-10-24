<?php

namespace App\Http\Middleware\Botman;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class HeardMiddleware implements Heard
{

    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        return $next($message);
    }
}
