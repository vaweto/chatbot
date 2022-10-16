<?php

namespace App\Botman\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ButtonConversation extends Conversation
{

    public function run()
    {
        $question = Question::create('what animal person are you?')
            ->addButtons([
                Button::create('I like cats')->value('cat'),
                Button::create('I like dogs')->value('dog'),
            ]);

        $this->ask($question, function ($answer) {
            if($answer->isInteractiveMessageReply()) {
                $this->say('Oh you are a '. $answer->getText() .' person');
            } else {
                $this->repeat();
            }

        });
    }
}
