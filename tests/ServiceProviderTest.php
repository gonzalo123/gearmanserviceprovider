<?php
use Symfony\Component\HttpFoundation\Request;
use Injector\InjectorServiceProvider;
use G\GearmanServiceProvider;
use Silex\Application;
use G\Gearman\Client;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    public function testClient()
    {
        $gearmanClient = $this->getMock('\GearmanClient');
        $gearmanClient
            ->expects($this->any())
            ->method('returnCode')
            ->willReturn(0);

        $gearmanClient
            ->expects($this->exactly(1))
            ->method('doNormal')
            ->willReturnCallback(function ($name, $workload, $unique) {
                return strrev(json_decode($workload, true));
            });

        $app = $this->getSilexApplication($gearmanClient);

        $app->get("/", function (Client $client, Application $app) {
            return $app->json($client->doNormal("worker.example", "Gonzalo"));
        });

        $request = Request::create('/');
        $actual = json_decode($app->handle($request)->getContent(), true);
        $this->assertEquals("olaznoG", $actual);
    }

    private function getSilexApplication(GearmanClient $gearmanClient)
    {
        $app = new Application(['debug' => true]);

        $app->register(new InjectorServiceProvider([
            'G\Gearman\Client' => 'gearmanClient',
            'G\Gearman\Tasks'  => 'gearmanTasks',
        ]));

        $app->register(new GearmanServiceProvider($gearmanClient));

        return $app;
    }
}