<?php

namespace Ao\Acona\Service;

use GuzzleHttp\Exception\ClientException;
use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Neos\Flow\Exception;
use Psr\Http\Message\ResponseInterface;

/**
 *
 * @Flow\Scope("singleton")
 */
class ApiService
{
    /**
     * Indication critical.
     */
    const INDICATION_CRITICAL = 'red';

    /**
     * Indication warning.
     */
    const INDICATION_WARNING = 'yellow';

    /**
     * Indication OK.
     */
    const INDICATION_OK = 'green';

    /**
     * All indications.
     */
    const INDICATION_ALL = [
        self::INDICATION_CRITICAL,
        self::INDICATION_WARNING,
        self::INDICATION_OK,
    ];

    /**
     * @Flow\InjectConfiguration("endpoints")
     * @var array
     */
    protected $endpoints;

    /**
     * @Flow\InjectConfiguration("apiToken")
     * @var string
     */
    protected $apiToken;

    /**
     * @Flow\InjectConfiguration("apiUrl")
     * @var string
     */
    protected $apiUrl;

    /**
     * @param string $uri
     * @param array $indication
     * @return mixed
     * @throws Exception
     * @throws GuzzleException
     */
    public function callLatestRecommendations(string $uri, array $indication = self::INDICATION_ALL)
    {
        $data = [
            'url' => $uri,
            'indication' => implode(',', $indication),
        ];

        return $this->callService($data, $this->endpoints['recommendationsLatest']);
    }

    /**
     * @param string $domain
     * @return mixed
     * @throws Exception
     * @throws GuzzleException
     */
    public function callLatestSuccessScores(string $domain)
    {
        $data = [
            'domain' => $domain,
        ];

        return $this->callService($data, $this->endpoints['scoresLatest']);
    }

    /**
     * @param string $uri
     * @return mixed
     * @throws Exception
     * @throws GuzzleException
     */
    public function callSuccessStoreHistory(string $uri)
    {
        $data = [
            'url' => $uri,
            'from_date' => date('Y-m-d', strtotime(date('Y-m-d') . '-6 months')),
        ];

        return $this->callService($data, $this->endpoints['successScores']);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function ping() : bool
    {
        try {
            $this->callService([], '', 'GET');
            return true;
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * @param array $query
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    private function callService(array $data, string $endpoint, string $method = 'POST')
    {
        $token = $this->apiToken;
        $headers = [];
        if (!empty($token)) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        $client = new Client();

        /** @var ResponseInterface $response */
        $response = $client->request(
            $method,
            $this->apiUrl . $endpoint,
            [
                'headers' => $headers,
                'form_params' => $data
            ]
        );

        if ($response instanceof ResponseInterface && $response->getStatusCode() === 200) {
            return json_decode((string) $response->getBody()->getContents());
        }

        throw new Exception(sprintf('Calling acona endpoint %s failed', $this->apiUrl . $endpoint));
    }
}