<?php

declare(strict_types=1);

namespace FriendsOfSylius\SyliusImportExportPlugin\Controller;

use FriendsOfSylius\SyliusImportExportPlugin\Service\CustomRequest;
use FriendsOfSylius\SyliusImportExportPlugin\Service\Date\DateHandler;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrderRequestConfigurationFactory extends AbstractController implements RequestConfigurationFactoryInterface
{

    /** @var ParametersParserInterface */
    private $parametersParser;

    /**
     * @var string
     *
     * @psalm-var class-string<RequestConfiguration>
     */
    private $configurationClass;

    /** @var array */
    private $defaultParameters;

    /**
     * @psalm-param class-string<RequestConfiguration> $configurationClass
     */
    private $requestConfigFactory;

    private $EXPORT_TIME_LIMIT;

    public function __construct(ParametersParserInterface $parametersParser, array $defaultParameters = [] ,string $EXPORT_TIME_LIMIT)
    {
        $this->parametersParser = $parametersParser;
        $this->configurationClass = "Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration";
        $this->defaultParameters = $defaultParameters;
        $this->requestConfigFactory = new RequestConfigurationFactory($this->parametersParser, $this->configurationClass, $this->defaultParameters);
        $this->EXPORT_TIME_LIMIT = $EXPORT_TIME_LIMIT;
    }

    public function create(MetadataInterface $metadata, Request $request): RequestConfiguration
    {
        
        if ($this->EXPORT_TIME_LIMIT == "true")
            $request = $this->prepareRequest($request);
            
         return $this->requestConfigFactory->create($metadata, $request);
    }

    public function prepareRequest($request): Request
    {
        $criteria = $request->query->get('criteria');

        $to = (($criteria == null) ? "" : $criteria['date']['to']['date']);
        $from =(($criteria == null) ? "" : $criteria['date']['from']['date']);

        $date = new DateHandler($from, $to);
        $newDates = $date->handel();

        $criteria['date']['to']['date'] = $newDates['to'];
        $criteria['date']['from']['date'] = $newDates['from'];
        $request->query->set('criteria', $criteria);
        return $request;
    }

}
