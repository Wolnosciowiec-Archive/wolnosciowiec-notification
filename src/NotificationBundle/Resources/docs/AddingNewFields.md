Adding new fields
=================

The message is created by the factory and have to implement the MessageInterface which enforces
to implement basic fields such as `group name`, the `id` and the `content`.

#### First step - create an entity

Create an entity that would extend the `Message` or just implement the `MessageInterface`.
Please also remember that it need to implement `Serializable` interface like it its already in `Message`, and te naming of the fields
is snake case.

#### Second step - register the entity

To allow using the entity by the *Notification application* we need to register it.

In your config (by default its notification.yml) please add a new position to the section:

```
allowed_entities:
        Message: "NotificationBundle\\Model\\Entity\\Message"
        YourEntityName: "MyExtraBundle\\Model\\Entity\\MessageWithDancingHorse"
```

#### Third step - add definition to the serializer

*Notification application* is using `JMS Serializer` and YAML schema. 
You need to register a new YAML definition file for your entity, and of course register your bundle to the `JMS Serializer`.
Configuration of the serializer is in `config.yml`.

Example:
```
MyExtraBundle\\Model\\Entity\\MessageWithDancingHorse:
    exclusion_policy: ALL
    properties:
        content:
            type: string
            expose: true
        id:
            type: string
            expose: true
        groupName:
            type: string
            expose: true
        couldBeTruncated:
            type: boolean
            expose: true
        yeeeaaHaaa:
            type: boolean
            expose: true
```