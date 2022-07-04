<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service\Geo;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Countries;
use Throwable;

/**
 * Class UserCountryGuesser
 * @package Ekyna\Bundle\UiBundle\Service\Geo
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @TODO Caching
 */
class UserCountryGuesser
{
    private array   $results = [];
    private ?Client $client  = null;

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * Returns the user country iso code (alpha-2).
     */
    public function getUserCountry(string $default = 'US'): string
    {
        if (null === $request = $this->requestStack->getMainRequest()) {
            return $default;
        }

        if (null === $ip = $request->getClientIp()) {
            return $default;
        }

        if (isset($this->results[$ip])) {
            return $this->results[$ip];
        }

        $client = $this->getClient();

        try {
            $response = $client->request('GET', 'https://ip2c.org/', [
                'query'   => ['ip' => $ip],
                'stream'  => true,
                'timeout' => 0.3,
            ]);
        } catch (Throwable) {
            return $default;
        }

        if (200 !== $response->getStatusCode()) {
            return $default;
        }

        $content = $response->getBody()->getContents();

        $result = explode(';', $content);
        if ('1' !== $result[0]) {
            return $default;
        }

        if (empty($code = $result[1]) || !Countries::exists($code)) {
            $code = $default;
        }

        return $this->results[$ip] = $code;
    }

    private function getClient(): Client
    {
        if ($this->client) {
            return $this->client;
        }

        return $this->client = new Client();
    }
}
