<?php

namespace AsnawiSaharuddin\Unleash;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use AsnawiSaharuddin\Unleash\Exceptions\HttpException;
use AsnawiSaharuddin\Unleash\Exceptions\InvalidServerResponseException;
use AsnawiSaharuddin\Unleash\Exceptions\RequestTimeoutException;
use AsnawiSaharuddin\Unleash\Exceptions\ServerUnavailableException;
use AsnawiSaharuddin\Unleash\Exceptions\UnknownException;
use AsnawiSaharuddin\Unleash\Strategies\StrategyFactory;

class Unleash
{
    /** @var HttpClient */
    private $client;

    /** @var Feature[] | null */
    private $features = null;

    /** @var string */
    private $appName;

    /** @var string */
    private $instanceId;

    public function __construct(string $url, string $appName, string $instanceId)
    {
        $this->appName = $appName;
        $this->instanceId = $instanceId;

        $this->client = new HttpClient([
            'base_uri' => $url,
            'timeout' => 5, // 5 seconds
            'connect_timeout' => 2, // 2 seconds
        ]);
    }

    /**
     * @return array|null
     */
    public function getFeatures(): ?array
    {
        if (is_null($this->features)) {
            $this->fetchFeatures();
        }

        return $this->features;
    }

    public function getFeature(string $name): ?Feature
    {
        $features = $this->getFeatures();

        foreach ($features as $feature) {
            if ($feature->getName() === $name && $feature->isEnabled()) {
                return $feature;
            }
        }

        return null;
    }

    public function isFeatureEnabled(string $name) {
        $features = $this->getFeatures();

        foreach ($features as $feature) {
            if ($feature->getName() === $name && $feature->isEnabled()) {
                return true;
            }
        }

        return false;
    }

    public function isEnabled(string $name, array $context = []) {
        $context = new Context($context);

        if (!$this->isFeatureEnabled($name)) {
            return false;
        }
        
        $strategies = $this->getFeature($name)
            ->getStrategies();

        foreach ($strategies as $strategy) {
            $strategyInstance = StrategyFactory::make($strategy);

            if (!is_null($strategyInstance)) {
                return $strategyInstance->isEnabled($context);
            }
        }

        return false;
    }

    private function fetchFeatures()
    {
        $this->features = [];

        $headers = [
            'UNLEASH-INSTANCEID' => $this->instanceId,
            'UNLEASH-APPNAME' => $this->appName,
        ];

        try {
            $response = $this->client->get('client/features', [
                'headers' => $headers,
            ]);
        } catch (ClientException $e) {
            throw new HttpException($e->getMessage(), $e->getCode());
        } catch (ConnectException $e) {
            throw new ServerUnavailableException($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            throw new RequestTimeoutException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new UnknownException($e->getMessage(), $e->getCode());
        }

        $data = json_decode($response->getBody(), true);

        if (!is_array($data) || !isset($data['features'])) {
            throw new InvalidServerResponseException();
        }

        foreach ($data['features'] as $feature) {
            array_push($this->features, new Feature($feature));
        }
    }
}