<?php

namespace CoG\StupidMQBundle\Twig;

use CoG\StupidMQ\Message;
use CoG\StupidMQ\Message\MessageInterface;

class CoGExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('colorize', array($this, 'colorizeFilter')),
            new \Twig_SimpleFilter('state_class', array($this, 'stateClass')),
            new \Twig_SimpleFilter('decode_content', array($this, 'decodeContent')),
        );
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