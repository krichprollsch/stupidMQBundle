<?php


namespace CoG\StupidMQBundle\Feeback;


use CoG\StupidMQ\Message\MessageInterface;

class Feedback
{
    protected $message;
    protected $state;

    public static function createFromValue($value)
    {
        if (is_bool($value)) {
            $state = $value ? MessageInterface::STATE_DONE : MessageInterface::STATE_ERROR;
            $message = null;
        } elseif (is_scalar($value)) {
            $state = MessageInterface::STATE_DONE;
            $message = $value;
        } elseif ($value instanceof Feedback) {

            return $value;
        } else {
            $state = MessageInterface::STATE_DONE;
            $message = null;
        }

        return self::create($state, $message);
    }

    public static function create($state, $message = null)
    {
        return new self($state, $message);
    }

    public function __construct($state, $message = null)
    {
        $this->setState($state);
        $this->message = $message;
    }

    public function setState($state)
    {
        switch($state) {
            case MessageInterface::STATE_NEW:
            case MessageInterface::STATE_CANCELED:
            case MessageInterface::STATE_DONE:
            case MessageInterface::STATE_ERROR:
            case MessageInterface::STATE_PENDING:
            case MessageInterface::STATE_RUNNING:
                $this->state = $state;
                return;
        }

        throw new \UnexpectedValueException('Bad value for state');
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getState()
    {
        return $this->state;
    }
}