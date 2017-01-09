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