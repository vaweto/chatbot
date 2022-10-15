<?php

$botman = app('botman');


// Define all bot commands
$botman->hears('hi(.*)', function($bot) {
    $bot->reply('hello');
});


$botman->hears('bye|adios|end"', function($bot) {
    $bot->reply('bye bye');
});

