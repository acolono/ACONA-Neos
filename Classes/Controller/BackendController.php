<?php

declare(strict_types=1);

namespace Ao\Acona\Controller;

use Ao\Acona\Service\AconaService;
use Ao\Acona\Service\ApiService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Exception\NoSuchArgumentException;
use Neos\Flow\Mvc\FlashMessage\FlashMessageService;
use Neos\Flow\Security\Context;
use Neos\Fusion\View\FusionView;
use Neos\Neos\Controller\Module\AbstractModuleController;
use Neos\Neos\Service\LinkingService;
use Neos\Neos\TypeConverter\NodeConverter;


/**
 * @Flow\Scope("singleton")
 */
class BackendController extends AbstractModuleController
{
    /**
     * @var FusionView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

    /**
     * @Flow\Inject
     * @var Context
     */
    protected $securityContext;

    /**
     * @Flow\Inject
     * @var LinkingService
     */
    protected $linkingService;

    /**
     * @Flow\Inject
     * @var ApiService
     */
    protected $apiService;

    /**
     * @Flow\Inject
     * @var AconaService
     */
    protected $aconaService;

    /**
     * @Flow\Inject
     * @var FlashMessageService
     */
    protected $flashMessageService;

    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = [
        'html' => FusionView::class,
    ];

    /**
     * Allow invisible nodes to be redirected to
     *
     * @return void
     * @throws NoSuchArgumentException
     */
    protected function initialize(): void
    {
        // use this constant only if available (became available with patch level releases in Neos 4.0 and up)
        if (defined(NodeConverter::class . '::INVISIBLE_CONTENT_SHOWN')) {
            $this->arguments->getArgument('node')->getPropertyMappingConfiguration()->setTypeConverterOption(NodeConverter::class, NodeConverter::INVISIBLE_CONTENT_SHOWN, true);
        }
    }

    public function indexAction(string $domain = '')
    {
        $ping = $this->apiService->ping();
        $scores = [];
        if(!empty($domain)) {
          $scores = $this->aconaService->getLatestSuccessScores($domain);
        }

        $this->view->assignMultiple(
            [
                'ping' => $ping,
                'domain' => $domain,
                'scores' => $scores
            ]
        );
    }
}
