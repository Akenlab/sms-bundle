<?php

namespace SMSBundle\EventListener;

use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use SMSBundle\SMSEngine\SMSEngine;

class NumberStateListener implements EventSubscriberInterface
{
    protected $smsEngine;

    public function __construct(SMSEngine $smsEngine) 
    {
        $this->smsEngine = $smsEngine;
    }

    public function leave(Event $event)
    {
        
    }
    public function enter(Event $event)
    {
        
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.numbers_state.leave' => array('leave'),
            'workflow.numbers_state.enter' => array('enter'),
        );
    }
}
?>