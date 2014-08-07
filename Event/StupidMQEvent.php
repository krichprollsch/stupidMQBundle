<?php

namespace CoG\StupidMQBundle\Event;

use CoG\StupidMQ\Message;
use Symfony\Component\EventDispatcher\Event;

abstract class StupidMQEvent extends Event
{
    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
} 