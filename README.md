Gearman Service provider for Silex
==================================

Silex aplication example:

```php
use G\Gearman\Client;
use G\GearmanServiceProvider;
use Silex\Application;

$app = new Application();

$app->register(new GearmanServiceProvider());

$app->get("/", function (Client $client) {
    return "Hello " . $client->doNormal("worker.example", "Gonzalo");
});

$app->run();
```

worker example

```php
use G\Gearman\Builder;

$worker = Builder::createWorker();

$worker->on("worker.example", function ($response) {
    return strrev($response);
});

$worker->run();
```
