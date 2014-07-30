<?php

namespace CoG\StupidMQBundle\Event;

final class StupidMQEvents
{
    /**
     * The stupidmq.warn_error event is thrown
     * each time an error occurs in a queue.
     *
     * The event listener receives a
     * CoG\StupidMQBundle\Event\StupidMQErrorEvent instance.
     *
     * @var string
     */
    const STUPIDMQ_ERROR = 'stupidmq.warn_error';

    /**
     * The stupidmq.warn_error event is thrown
     * each time a fatal error occurs in a queue.
     *
     * The event listener receives a
     * CoG\StupidMQBundle\Event\StupidMQExceptionErrorEvent instance.
     *
     * @var string
     */
    const STUPIDMQ_FATAL_ERROR = 'stupidmq.warn_exception_error';

} 