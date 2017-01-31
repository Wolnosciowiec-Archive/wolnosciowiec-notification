Wolnościowiec Notification
==========================

API gateway for sending notifications, with queue support.

Features:
- Ready to use application that sends notifications with just one REST call
- Queue support (Redis or SQLite3)
- Sync and async interfaces
- Builtin Twitter and Facebook support
- Easily extensible (Queue storage could be easily added and switched, also the senders could be easily added eg. support for mail sending)
- Written in Symfony 3 should be understandable for most PHP programmers

## How it works?

The application is taking as input ANY TYPE of message that implements the _Message interface_.
_Message interface_ is a definition of minimum required fields and a mark for a class that its an input message.

_Messenger_ is a message sending implementation that is able to handle specific message types, and possibly the basic message type.

To create a custom message format you have to:
1. Create a new class that implements **MessageInterface** or extends **Message**
2. Register it in your **notification.yml** under `allowed_entities` section
3. Make create a **Messenger**, in other words a **MESSAGE HANDLER, A SENDER** that implements **MessengerInterface**
4. Register your **Messenger** in **notification.yml** under `enabled_messengers` section, add the configuration in `messengers` section

To retrieve a configuration in your **Messenger** you have to inject the `MessengerConfigurationProvider` service using the container

## Installation

```
cp app/config/notification.yml.dist app/config/notification.yml
nano app/config/notification.yml
composer install --dev
```

## Configuration

Edit the configuration in `app/config/notification.yml` (if it does not exists then just create the file)

```
notification:
    queue: "notificationbundle.queue.redis"
    queue_parameters:
        scheme: tcp
        host: localhost
        port: 6379

    allowed_entities:
        Message: "NotificationBundle\\Model\\Entity\\Message"

    enabled_messengers:
        "notificationbundle.messenger.twitter":
            class: NotificationBundle\Messenger\TwitterMessenger
            groups:
                - short_content_update_notification

    messengers:
        twitter:
            consumer_key: aaa
            consumer_secret: bbb
            access_token: ccc
            access_token_secret: ddd

        facebook:
            app_id: xxx
            app_secret: yyy
```

## Usage

Example HTTP request:

```
POST /message/queue/add?message_type=Message

{
    "message":    "This will go on to Twitter",
    "group_name": "short_content_update_notification",
    "could_be_truncated": true
}
```

```
GET /message/queue/process
```