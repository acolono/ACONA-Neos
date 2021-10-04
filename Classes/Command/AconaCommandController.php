<?php
namespace Ao\Acona\Command;

use Neos\Flow\Annotations as Flow;
use Ao\Acona\Service\AconaService;
use Neos\Flow\Cli\CommandController;

/**
 *
 * @Flow\Scope("singleton")
 */
class AconaCommandController extends CommandController
{

    /**
     * @Flow\Inject
     * @var AconaService
     */
    protected $aconaService;

    /**
     * @param string $domain
     */
    public function latestSuccessScoresCommand(string $domain)
    {
        \Neos\Flow\var_dump($this->aconaService->getLatestSuccessScores($domain));
    }

    /**
     * @param string $uri
     */
    public function latestRecommendationsCommand(string $uri)
    {
        \Neos\Flow\var_dump($this->aconaService->getLatestRecommendations($uri));
    }

    /**
     * @param string $uri
     */
    public function successScoreHistoryCommand(string $uri)
    {
        \Neos\Flow\var_dump($this->aconaService->getSuccessScoreHistory($uri));
    }
}