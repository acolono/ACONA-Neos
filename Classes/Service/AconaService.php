<?php

namespace Ao\Acona\Service;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class AconaService {

    /**
     * @Flow\Inject
     * @var ApiService
     */
    protected $apiService;

    public function getLatestSuccessScores(string $domain) : array
    {
        return $this->apiService->callLatestSuccessScores($domain);
    }

    public function getLatestRecommendations(string $uri) : array
    {
        return $this->apiService->callLatestRecommendations($uri);
    }

    public function getSuccessScoreHistory(string $uri) : array
    {
        return $this->apiService->callSuccessStoreHistory($uri);
    }
}