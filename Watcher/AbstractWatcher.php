<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  04/12/12 11:08
 */

namespace CoG\StupidMQBundle\Watcher;

use CoG\StupidMQBundle\Logger\AbstractLogger;

/**
 * AbstractWatcher
 *
 * @author pierre
 */
abstract class AbstractWatcher extends AbstractLogger implements WatcherInterface
{

    protected function getOptions(array $opt=array()) {
        return array_merge( $this->defaultOptions(), $opt );
    }

    protected function defaultOptions() {
        return array(
            'max-leftime' => self::UNLIMITED,
            'max-message' => self::UNLIMITED,
            'process-timeout' => self::UNLIMITED,
            'sleep' => 1,
        );
    }

    protected function shouldStop( array $opt ) {
        if(
            $opt['max-message'] != self::UNLIMITED &&
            isset($opt['message-treated']) &&
            $opt['message-treated'] >= $opt['max-message']
        ) {

            return true;
        }

        if(
            $opt['max-lifetime'] != self::UNLIMITED &&
            (time()-$opt['lifetime-start']) >= $opt['max-lifetime']
        ) {

            return true;
        }

        return false;
    }
}
