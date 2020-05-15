<?php

namespace Welo\DeliveryInfo6\Service;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomFieldService
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EntityRepository */
    protected $customFieldSetRepository;

    /** @var string */
    protected $customFieldSetId;

    /** @var string */
    protected $productFieldId;
    /**
     * @var EntityRepositoryInterface
     */
    private $customFieldRepository;

    /**
     * CustomFieldService constructor.
     *
     * @param ContainerInterface $container
     * @param EntityRepositoryInterface $customFieldRepository
     * @param EntityRepositoryInterface $customFieldSetRepository
     */
    public function __construct(
        $container,
        EntityRepositoryInterface $customFieldRepository,
        EntityRepositoryInterface $customFieldSetRepository
    ) {
        $this->container = $container;
        $this->customFieldSetRepository = $customFieldSetRepository;
        $this->customFieldRepository = $customFieldRepository;
        $this->customFieldSetId = Uuid::randomHex();
        $this->productFieldId = Uuid::randomHex();
    }

    public function install(InstallContext $context): void
    {
        $this->addCustomFields($context->getContext());
    }

    public function uninstall(UninstallContext $context): void
    {
        $this->removeCustomFields($context->getContext());
    }

    private function addCustomFields(Context $context): void
    {
        $customFieldIds = $this->getCustomFieldIds($context);

        if ($customFieldIds->getTotal() !== 0) {
            return;
        }

        $this->customFieldSetRepository->create([[
            'id' => $this->customFieldSetId,
            'name' => 'welo',
            'config' => [
                'label' => [
                    'de-DE' => 'Lieferinformationen',
                    'en-GB' => 'Delivery information',
                    'fr-FR' => 'Informations sur la livraison'
                ],
                'translated' => true
            ],
            'relations' => [
                [
                    'id' => $this->productFieldId,
                    'entityName' => $this->container->get(ProductDefinition::class)->getEntityName()
                ]
            ],
            'customFields' => [
                [
                    'id' => $this->productFieldId,
                    'name' => 'welo_delivery_information',
                    'type' => CustomFieldTypes::TEXT,
                    'config' => [
                        'componentName' => 'sw-text',
                        'customFieldType' => 'text',
                        'customFieldPosition' => 1,
                        'label' => [
                            'en-GB' => 'Delivery information',
                            'de-DE' => 'Lieferinformationen',
                            'fr-FR' => 'Informations sur la livraison'
                        ]
                    ],
                    'active' => true
                ]
            ]
        ]], $context);
    }

    private function removeCustomFields(Context $context)
    {
        $customFieldIds = $this->getCustomFieldIds($context);

        if ($customFieldIds->getTotal() == 0) {
            return;
        }

        $ids = array_map(static function ($id) {
            return ['id' => $id];
        }, $customFieldIds->getIds());

        $this->customFieldRepository->delete($ids, $context);


        $customFieldSetIds = $this->getCustomFieldSetIds($context);

        if ($customFieldSetIds->getTotal() == 0) {
            return;
        }

        $ids = array_map(static function ($id) {
            return ['id' => $id];
        }, $customFieldSetIds->getIds());

        $this->customFieldSetRepository->delete($ids, $context);
    }

    private function getCustomFieldIds(Context $context): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new ContainsFilter('name', 'welo'));

        return $this->customFieldRepository->searchIds($criteria, $context);
    }

    private function getCustomFieldSetIds(Context $context): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'welo_delivery_information'));

        return $this->customFieldSetRepository->searchIds($criteria, $context);
    }
}
