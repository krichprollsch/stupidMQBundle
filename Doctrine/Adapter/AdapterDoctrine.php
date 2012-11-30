<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  29/11/12 15:53
 */

namespace CoG\StupidMQBundle\Doctrine\Adapter;

use CoG\StupidMQ\Adapter\AdapterPdoMysql;
use CoG\StupidMQ\Exception\RuntimeException;

/**
 * AdapterDoctrine
 *
 * @author pierre
 */
class AdapterDoctrine extends AdapterPdoMysql
{

    public function __construct( \Doctrine\DBAL\Connection $con, $tablename ) {

        $con = $con->getWrappedConnection();
        if(!($con instanceof \PDO)) {
            throw new RuntimeException('Doctrine DBAL wrapped connection is not instance of \PDO');
        }

        parent::__construct($con, $tablename);
    }

}
