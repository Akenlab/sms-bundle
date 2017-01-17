<?php

namespace Akenlab\SMSBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Akenlab\SMSBundle\Entity\Number;

/**
 * The sms.post.send event is dispatched each time an SMS is sent
 */
class SMSEvent extends Event
{

    protected $number;
    protected $body;

    public function __construct($body,Number $number)
    {
        $this->number = $number;
        $this->body = $body;
    }

    public function getNumber()
    {
        return $this->number;
    }
    public function getBody()
    {
        return $this->body;
    }
    public function setBody($body)
    {
        $this->body=(string)$body;
    }
}