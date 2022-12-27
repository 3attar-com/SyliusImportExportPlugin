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

    private function addCustomerData(CustomerInterface $customer): void
    {
        try {
            $this->addDataForResource($customer, 'Gender', $customer->getGender());
            $this->addDataForResource($customer, 'First_name', $customer->getFirstName());
            $this->addDataForResource($customer, 'Last_name', $customer->getLastName());
            $this->addDataForResource($customer, 'Email', $customer->getEmail());
            $this->addDataForResource($customer, 'Phone_number', $customer->getPhoneNumber());
            if ($customer->getUser()) {
                $this->addDataForResource($customer, 'Enabled', $customer->getUser()->isEnabled() ? 'Yes':'No' );
                $this->addDataForResource($customer, 'Verified', $customer->getUser()->isVerified() ? 'Yes':'No' );
            } else  {
                $this->addDataForResource($customer, 'Enabled', 'No' );
                $this->addDataForResource($customer, 'Verified', 'No');
            }
        } catch (EntityNotFoundException $ex) {
            return;
        }

    }
}
