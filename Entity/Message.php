<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  29/11/12 16:29
 */

namespace CoG\StupidMQBundle\Entity;

use CoG\StupidMQ\Message as SMQMessage;

/**
 * Message
 *
 * @author pierre
 */
class Message extends SMQMessage
{
    protected $queue;
    protected $createdAt;
    protected $updatedAt;

}
