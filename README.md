# ServiceManager
A dependency and config manager for PHP

The manager uses config to define services. Services are keyed by a hash of the arguments given, which means you may have multiple instances of a service with different arguments. nice.

# Usage
your config passed into the ServiceManager constructor will look like this:

```PHP
$config = array(
    'myConfig'=>array(
        'foo'=>'bar'
    ),
    'ServiceManager' => array(
        'additionalConfigs' => array(
            __DIR__.'/template.config.php' // load in and combine more configs.
        ),
        'factories' => array(
            'myService' => function( $serviceManager, $someArg = null ) { // using this service with different someArg arguments will create different instances of this service
                if ($someArg == null) {
                    $someArg = __NAMESPACE__; // if you want to set some default...
                }
                $config = $serviceManager->getConfig('myService', $someArg);
                return new YourService($config);
            }
        )
    )
);
```

Pass that config into ServiceManager

```PHP
$manager = new ServiceManager($config); // for factories, ServiceManager looks within the data at the 'ServiceManager' key of the config.
```

Get your service:

```PHP
$service = $manager->get('myService');
```

The service manager also manages config, allowing you to easily get config like so:
```PHP
$manager->getConfig('myConfig','foo'); // bar
$manager->getConfig('myConfig','foobooo'); // NULL
```
