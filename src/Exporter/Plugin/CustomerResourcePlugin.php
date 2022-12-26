<?php

declare(strict_types=1);

namespace FriendsOfSylius\SyliusImportExportPlugin\Exporter\Plugin;

use App\Entity\Addressing\Address;
use App\Entity\Customer\Customer;
use App\Entity\Product\Product;
use App\Entity\Promotion\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use FriendsOfSylius\SyliusImportExportPlugin\Exporter\ORM\Hydrator\HydratorInterface;
use FriendsOfSylius\SyliusImportExportPlugin\Service\AddressConcatenationInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PromotionCoupon;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Promotion\Model\PromotionCouponInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class CustomerResourcePlugin extends ResourcePlugin
{
    /** @var AddressConcatenationInterface */
    private $addressConcatenation;

    /** @var HydratorInterface */
    private $orderHydrator;

    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(
        RepositoryInterface           $repository,
        PropertyAccessorInterface     $propertyAccessor,
        EntityManagerInterface        $entityManager
    )
    {
        parent::__construct($repository, $propertyAccessor, $entityManager);

        $this->entityManager = $entityManager;

    }

    /**
     * {@inheritdoc}
     */
    public function init(array $idsToExport): void
    {

        parent::init($idsToExport);

        /** @var OrderInterface $resource */
        foreach ($this->resources as $resource) {
            $items = $this->addCustomerData($resource);
        }
    }

    private function addCustomerData(CustomerInterface $resource): void
    {
   //     dd($resource);
        $customer =$resource;
        try {
            $this->addDataForResource($resource, 'Gender', $customer->getGender());
            $this->addDataForResource($resource, 'First_name', $customer->getFirstName());
            $this->addDataForResource($resource, 'Last_name', $customer->getLastName());
            $this->addDataForResource($resource, 'Email', $customer->getEmail());
            $this->addDataForResource($resource, 'Phone_number', $customer->getPhoneNumber());
            $this->addDataForResource($resource, 'Enabled', $customer->getUser()->isEnabled());
            $this->addDataForResource($resource, 'Verified' , $customer->getUser()->isVerified());

        } catch (EntityNotFoundException $ex) {
            return;
        }
    }
}
