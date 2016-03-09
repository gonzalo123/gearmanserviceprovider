Gearman Service provider for Silex
==================================

Silex aplication example:

```php
use G\Gearman\Client;
use G\GearmanServiceProvider;
use Silex\Application;

$app = new Application();

$client = new \GearmanClient();
$client->addServers("localhost:4730");
$app->register(new GearmanServiceProvider($client));

$app->get("/", function (Client $client) {
    return "Hello " . $client->doNormal("worker.example", "Gonzalo");
});

$app->run();
```

worker example

```php
use G\Gearman\Builder;

$worker = Builder::createWorker();

$worker->on("worker.example", function ($response, \GearmanJob $job) {
    echo "Response: {$response} unique: {$response}\n";

    return strrev($response);
});

$worker->run();
```