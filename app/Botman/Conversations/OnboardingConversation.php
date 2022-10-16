<?php

namespace App\Botman\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class OnboardingConversation extends Conversation
{
    protected $firstname;

    protected $email;

    public function askFirstname()
    {
        $this->ask('Hello! What is your firstname?', function(Answer $answer) {
            // Save result
            $value = $answer->getText();
            if(trim($value) == '') {
                return $this->repeat('this does not look like as a real name. Please provide your name');
            }
            $this->firstname = $answer->getText();

            $this->say('Nice to meet you '.$this->firstname);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, '.$this->firstname);

            //$this->askImage();
        });
    }

    protected function askImage()
    {
        $this->askForImages('Please send me a picture of you', function ($images) {
           $this->say('Thank you - I received'. count($images) . 'images');
        }, function () {
            $this->say('Ummm this does not look like a valid image');
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askFirstname();
    }
}
