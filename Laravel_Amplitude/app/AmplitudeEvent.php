<?php
namespace App;
use \Zumba\Amplitude\Event as Event;

class AmplitudeEvent extends Event {

    public function __construct(Event $event)
    {
        parent::__construct($event->toArray());
        $this->availableVars['session_id'] = 'string';
    }
}

?>