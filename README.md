# ServiceManager
A dependency and config manager for PHP

The manager uses config to define services. Services are keyed by a hash of the arguments given, which means you may have multiple instances of a service with different arguments. nice.

# Usage
your config passed into the ServiceManager constructor will look like this:

```PHP
'ServiceManager' => array(
    'additionalConfigs' => array(
        __DIR__.'/template.config.php' // load in and combine more configs.
    ),
    'factories' => array(
        'eventConsumer' => function( $serviceManager, $eventName = null ) { // using this service with different eventName arguments will create different instances of this service
            if ($eventName == null) {
                $eventName = __NAMESPACE__;
            }
            $config = array_merge($serviceManager->getConfig('events', 'consumer', 'default'), $serviceManager->getConfig('events', 'consumer', $eventName));
            return new YourService($config);
        }
    )
)
```
