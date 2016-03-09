<?php
namespace G;

use G\Gearman\Client;
use G\Gearman\Tasks;
use Injector\InjectorServiceProvider;
use Silex\Application;
use Silex\ServiceProviderInterface;

class GearmanServiceProvider implements ServiceProviderInterface
{
    private $client;

    public function __construct(\GearmanClient $client = null)
    {
        if (is_null($client)) {
            $client = new \GearmanClient();
            $client->addServers("localhost:4730");
        }
        $this->client = $client;
    }

    public function register(Application $app)
    {
        $app->register(new InjectorServiceProvider([
            'G\Gearman\Client' => 'gearmanClient',
            'G\Gearman\Tasks'  => 'gearmanTasks',
        ]));

        $app['gearmanClient'] = function () use ($app) {
            $client = new Client($this->client);

            $client->onSuccess(function ($response) {
                return $response;
            });

            return $client;
        };

        $app['gearmanTasks'] = function () use ($app) {
            return new Tasks($this->client);
        };
    }

    public function boot(Application $app)
    {
    }
}