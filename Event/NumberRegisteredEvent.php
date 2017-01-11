<?php

namespace Akenlab\SMSBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Akenlab\SMSBundle\Entity\Number;

/**
 * The number.registered event is dispatched each time a number is created
 * in the system.
 */
class NumberRegisteredEvent extends Event
{
    const NAME = 'number.registered';

    protected $number;

    public function __construct(Number $number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }
}