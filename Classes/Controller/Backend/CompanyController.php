<?php

namespace Extcode\Contacts\Controller\Backend;

/*
 * This file is part of the package extcode/contacts.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Contacts\Domain\Model\Company;
use Extcode\Contacts\Domain\Model\Dto\Demand;
use Extcode\Contacts\Domain\Repository\CompanyRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

class CompanyController extends ActionController
{
    /**
     * @var CompanyRepository
     */
    private ?CompanyRepository $companyRepository = null;

    /**
     * @var int
     */
    protected $pageId;

    public function injectCompanyRepository(CompanyRepository $companyRepository): void
    {
        $this->companyRepository = $companyRepository;
    }

    protected function initializeAction(): void
    {
        $this->pageId = (int)GeneralUtility::_GP('id');

        $frameworkConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        $persistenceConfiguration = [
            'persistence' => [
                'storagePid' => $this->pageId,
            ],
        ];
        $this->configurationManager->setConfiguration(array_merge($frameworkConfiguration, $persistenceConfiguration));
    }

    public function listAction(ServerRequest $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $currentPage = (int) $queryParams['id'] ?? 1;

        $demand = $this->createDemandObject($queryParams);

        $companies = $this->companyRepository->findDemanded($demand);

        $itemsPerPage = $this->settings['itemsPerPage'] ?? 25;
        $arrayPaginator = new QueryResultPaginator(
            $companies,
            $currentPage,
            $itemsPerPage
        );
        $pagination = new SimplePagination($arrayPaginator);
        $this->view->assignMultiple(
            [
                'demand' => $demand,
                'companies' => $companies,
                'paginator' => $arrayPaginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
            ]
        );

        return $this->htmlResponse();
    }

    /**
     * @param Company $company
     *
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("company")
     */
    public function showAction(Company $company): void
    {
        $this->view->assign('company', $company);
    }

    protected function createDemandObject(array $params): Demand
    {
        $demand = GeneralUtility::makeInstance(Demand::class);

        if (isset($params['filter'])) {
            $filter = $params['filter'];
            if (!empty($filter['searchString'])) {
                $demand->setSearchString($filter['searchString']);
            }
        }

        return $demand;
    }
}
