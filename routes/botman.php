<?php

use App\Botman\Conversations\ButtonConversation;
use App\Botman\Conversations\OnboardingConversation;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

$botman = app('botman');

//fallback
$botman->fallback(function ($bot) {
    $message = $bot->getMessage();

    $bot->reply('sorry i dont understand: "'. $message->getText() .'"');
    $bot->reply('My known commands are:');
    $bot->reply('Weather in location');
});

// Define all bot commands
$botman->hears('hi(.*)', function($bot) {
    $bot->reply('hello');
});


$botman->hears('bye|adios|end', function($bot) {
    $bot->reply('bye bye');
});

//Capture input
$botman->hears('Weather in {location}', function($bot, $location) {
    $url = 'http://api.weatherstack.com/current?access_key=400eea50787fec2a6b07d275a168750f&query='. urlencode($location);
    $response = json_decode(file_get_contents($url));

    $bot->reply('The weather in '. $response->location->name . ', ' . $response->location->country . ' is:');
    $bot->reply($response->current->weather_descriptions[0]);
    $bot->reply('Temperature: ' . $response->current->temperature . ' celcius');
});

//Capture with regular expression need better plan on api
$botman->hears('([0-9]) day forecast for {location}', function($bot, $days, $location) {
    $url = 'http://api.weatherstack.com/forecast?access_key=400eea50787fec2a6b07d275a168750f&query='. urlencode($location).'$forecast_days='.$days;
    $response = json_decode(file_get_contents($url));

    $bot->reply('The weather in '. $response->location->name . ', ' . $response->location->country . ' is:');
    $bot->reply($response->current->weather_descriptions[0]);
    $bot->reply('Temperature: ' . $response->current->temperature . ' celcius');
});

//Gif
$botman->hears('/gif {name}' , function ($bot, $name) {

    $url = 'https://api.giphy.com/v1/gifs/search?api_key=vkgnxrDRYIJiCrPNNqlZ2VVgnoDOTlmr&limit=10&q='. $name;
    $response = json_decode(file_get_contents($url));
    $image = $response->data[0]->images->downsized_large->url;

    $message = OutgoingMessage::create('This is your gif')
        ->withAttachment(
            new Image($image)
        );

    $bot->reply($message);
});

//Video
$botman->hears('/video' , function ($bot) {

    $url = 'https://www.w3schools.com/html/mov_bbb.mp4';

    $message = OutgoingMessage::create('This is your video')
        ->withAttachment(
            new Video($url)
        );

    $bot->reply($message);
});

//store user info
$botman->hears('My name is {name}', function($bot, $name) {
    $bot->userStorage()->save([
        'name' => $name
    ]);
    $bot->reply('hello ' . $name);
});

$botman->hears('Say my name', function($bot) {
    $bot->reply('Your name is  ' . $bot->userStorage()->get('name'));
});

//aCCESS USER INFORMATION
$botman->hears('information', function($bot) {
    $user = $bot->getUser();
    //it is associate with the driver facebook or telegram or web
});

//Get attachments
//also recieve videos, audios and files
$botman->receivesImages(function ($bot, $images) {
   $image = $images[0];
   $bot->reply(OutgoingMessage::create('I receive your image')->withAttachment($image));
});

//inline conversation
$botman->hears('survey', function ($bot) {
   $bot->ask('what is your name?', function ($answer, $conversation) {
      $value= $answer->getText();
      $conversation->say('Nice to meet you, '. $value);
   });
});

//Convertation class
$botman->hears('conv', function ($bot) {
   $bot->startConversation(new OnboardingConversation);
});

//help
$botman->hears('help', function ($bot) {
    $bot->reply('this is the helping information');
})->skipsConversation();

//Stop
$botman->hears('stop', function ($bot) {
    $bot->reply('We stop your conversation');
})->stopsConversation();

//Button
$botman->hears('animal', function ($bot) {
    $bot->startConversation(new ButtonConversation());
});



