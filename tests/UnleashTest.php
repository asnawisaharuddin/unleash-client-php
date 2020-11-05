<?php

declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;

class UnleashTest extends \PHPUnit\Framework\TestCase
{
    private $mockHandler;

    private $client;

    private $defaultFeatures;

    protected function setUp()
    {
        parent::setUp();

        $this->defaultFeatures = [
            "version" => 1,
            "features" => [
                [
                    "name" => "es_report",
                    "description" => "",
                    "enabled" => true,
                    "strategies" => [
                        [
                            "name" => "userWithId",
                            "parameters" => [
                                "userIds" => "asnawi@terato.com,asnawi@terato2.com"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->mockHandler = new MockHandler();

        $handlerStack = \GuzzleHttp\HandlerStack::create($this->mockHandler);
        $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $unleash = new AsnawiSaharuddin\Unleash\Unleash('', '', '');
        $reflection = new ReflectionClass($unleash);
        $reflectionProperty = $reflection->getProperty('client');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($unleash, $httpClient);

        $this->client = $unleash;
    }

    public function testShouldMapFeaturesToFeatureInstance()
    {
        $this->mockHandler->append(
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($this->defaultFeatures))
        );

        $features = $this->client->getFeatures();

        $this->assertEquals(
            1,
            count($features)
        );

        foreach ($features as $feature) {
            $this->assertEquals(
                true,
                ($feature instanceof \AsnawiSaharuddin\Unleash\Feature)
            );
        }
    }

    public function testFeatureEnableShouldReturnTrueIfFeatureIsEnabled()
    {
        $this->mockHandler->append(
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($this->defaultFeatures))
        );

        $enabled = $this->client->isFeatureEnabled('es_report');

        $this->assertEquals(true, $enabled);
    }

    public function testFeatureEnableShouldReturnFalseIfFeatureIsDisabled()
    {
        $this->defaultFeatures['features'][0]['enabled'] = false;

        $this->mockHandler->append(
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($this->defaultFeatures))
        );

        $enabled = $this->client->isFeatureEnabled('es_report');

        $this->assertEquals(false, $enabled);
    }

    public function testUserWithIdStrategyShouldReturnFalseIfFeatureIsDisabled()
    {
        $this->defaultFeatures['features'][0]['enabled'] = false;

        $this->mockHandler->append(
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($this->defaultFeatures))
        );

        $enabled = $this->client->isEnabled('es_report', ['userId' => 'asnawi@terato.com']);

        $this->assertEquals(false, $enabled);
    }

    public function testUserWithIdStrategyShouldReturnFalseIfUserIdNotIncludedInList()
    {
        $this->mockHandler->append(
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($this->defaultFeatures))
        );

        $enabled = $this->client->isEnabled('es_report', ['userId' => 'asnawi3@terato.com']);

        $this->assertEquals(false, $enabled);
    }

    public function testUserWithIdStrategyShouldReturnTrueIfUserIsIncludedInList()
    {
        $this->mockHandler->append(
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($this->defaultFeatures))
        );

        $enabled = $this->client->isEnabled('es_report', ['userId' => 'asnawi3@terato.com']);

        $this->assertEquals(false, $enabled);
    }
}