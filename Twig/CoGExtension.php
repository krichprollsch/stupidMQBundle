<?php

namespace CoG\StupidMQBundle\Twig;

use CoG\StupidMQ\Message;
use CoG\StupidMQ\Message\MessageInterface;

class CoGExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('time_duration', array($this, 'timeDurationFilter')),
            new \Twig_SimpleFilter('colorize', array($this, 'colorizeFilter')),
            new \Twig_SimpleFilter('state_class', array($this, 'stateClass')),
            new \Twig_SimpleFilter('decode_content', array($this, 'decodeContent')),
        );
    }

    public function timeDurationFilter($dateFrom, $dateTo)
    {
        $from = new \DateTime($dateFrom);
        $to = new \DateTime($dateTo);
        $diff = $from->diff($to);
        $duration = '';

        if ($diff->h > 0) {
            $duration .= $diff->h . 'h';
        }
        if ($diff->i > 0) {
            $duration .= $diff->i . 'm';
        }
        $duration .= $diff->s . 's';

        return $duration;
    }

    public function colorizeFilter($code)
    {
        return highlight_string(sprintf("<?php \n %s \n ?>", $code), true);
    }

    public function stateClass(Message $message)
    {
        switch($message->getState()) {
            case MessageInterface::STATE_NEW:
                $stateClass = 'info';
                break;
            case MessageInterface::STATE_PENDING:
            case MessageInterface::STATE_RUNNING:
            case MessageInterface::STATE_CANCELED:
                $stateClass = 'warning';
                break;
            case MessageInterface::STATE_DONE:
                $stateClass = 'success';
                break;
            case MessageInterface::STATE_ERROR:
                $stateClass = 'danger';
                break;
        }
        return $stateClass;
    }

    public function decodeContent(Message $message)
    {
        return var_export(json_decode($message->getContent(), true) ? : unserialize($message->getContent()), true);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'cog_extension';
    }
}