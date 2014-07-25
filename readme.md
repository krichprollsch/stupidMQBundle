StupidMQBundle
==============

Bundle to support stupidMessageQueue into Symfony application.

Installation
------------

Add requirement into you `composer.json` :

    "cog/stupidmqbundle":"2.4.*"

Then enable the bundle into `app/AppKernel.php` :

    new CoG\StupidMQBundle\CoGStupidMQBundle()

Optionnaly, if you are using Doctrine Orm, you can use it to easily install message queue table :

    php app/console doctrine:schema:update --force

This command will create for you a new table `cog_stupidmq` to store your messages.

Creating a message queue
-------------------------

Edit your `services.xml` to add your own queues :

    <!-- Messages Queues -->
    <service id="my.queue" class="%cog_stupidmq.queue.class%">
        <argument type="service" id="cog_stupidmq.channel" />
        <argument>my.queue</argument>
        <tag name="cog_stupidmq.queue"/>
    </service>

Adding message into the queue
-----------------------------

```php
$queue = $this->getContainer()->get('my.queue');
$queue->publish('This is a message !');
```

Adding a worker
---------------

To consume messages, you have to create a worker class like this :

```php
<?php

namespace My\Bundle\Worker;

use CoG\StupidMQBundle\Worker\WorkerInterface;

class MyWorker implements WorkerInterface
{
    public function execute( $message ) {
        var_dump($message);
        /* you ca give a feedback using \CoG\StupidMQBundle\Feeback\Feedback */
        return Feedback::create(
            MessageInterface::STATE_DONE,
            'here is my feedback'
        );

        /* or just return a boolean */
        return true;
    }

    public function getSubscribedQueues() {
        return array(
            'my.queue'
        );
    }

    public function getName() {
        return 'my.worker';
    }
}
```

Then you should register your worker, edit your `services.xml` :

    <!-- Message Worker -->
    <service id="my.worker" class="My\Bundle\Worker\MyWorker">
        <tag name="cog_stupidmq.worker"/>
    </service>


Running command
---------------

StupidMQBundle is coming with useful command to process messages :

    $ php app/console cog:stupidmq:watch -m1

These command will watch your queue until a message have to be processed, then it will run a subprocess in order to treat it.

Command help :

    $ php app/console cog:stupidmq:watch --help
