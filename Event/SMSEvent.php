<?php

namespace Akenlab\SMSBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Akenlab\SMSBundle\Entity\Number;

/**
 * The sms.sent event is dispatched each time an SMS is sent
 */
class SMSEvent extends Event
{

    protected $number;
    protected $body;

    public function __construct($body,Number $number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }
    public function getBody()
    {
        return $this->body;
    }
}