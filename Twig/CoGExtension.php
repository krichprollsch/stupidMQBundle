<?php

namespace CoG\StupidMQBundle\Twig;

class CoGExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('time_duration', array($this, 'timeDurationFilter')),
            new \Twig_SimpleFilter('colorize', array($this, 'colorizeFilter')),
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