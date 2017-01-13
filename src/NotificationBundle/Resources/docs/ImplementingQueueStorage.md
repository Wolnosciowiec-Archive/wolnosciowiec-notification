Implementing queue storage
==========================

You don't have to use the `Redis` or the `Doctrine` to store the queue data, you could use the 
`RabbitMQ` or other applications. It's pretty easy.

#### First step - initial service

Your queue has to be a Symfony service implementing the `QueueInterface`.

#### Second step - register

When you already have a proper service you can register it in the configuration file.

```
notification:
    queue: "mysuperextrabundle.queue.rabbitmq"
    queue_parameters:
        host: localhost
```

#### Third step - use "queue_parameters"

The initial structure is created, the queue is registered, so to extract
the parameters from `queue_parameters` from the configuration file into your service
you can use the `@notificationbundle.services.configuration.queue` service
which is a data provider for queue handlers.

Just inject the `@notificationbundle.services.configuration.queue` via container
and use it's methods to retrieve configuration keys, it's simple.