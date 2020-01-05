<?php

namespace Welo\DeliveryInfo6\Service;

use Exception;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
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
     * CustomFieldService constructor.
     *
     * @param ContainerInterface $container
     * @param EntityRepository   $customFieldSetRepository
     */
    public function __construct(
        $container,
        EntityRepository $customFieldSetRepository
    ) {
        $this->container = $container;
        $this->customFieldSetRepository = $customFieldSetRepository;
        $this->customFieldSetId = Uuid::randomHex();
        $this->productFieldId = Uuid::randomHex();
    }

    public function install(InstallContext $context): void
    {
        $this->upsertCustomField($context->getContext());
    }

    public function update(UpdateContext $context): void
    {
        $this->upsertCustomField($context->getContext());
    }

    public function uninstall(UninstallContext $context): void
    {
        $this->deactivateCustomField($context->getContext());
    }

    public function activate(ActivateContext $context): void
    {
        $this->upsertCustomField($context->getContext());
    }

    public function deactivate(DeactivateContext $context): void
    {
        $this->deactivateCustomField($context->getContext());
    }

    public function upsertCustomField(Context $context)
    {
        try {
            $this->customFieldSetRepository->upsert([[
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
                'customFields' => [
                    [
                        'id' => $this->productFieldId,
                        'name' => 'welo_delivery_information',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT,
                            'customFieldPosition' => 1,
                            'label' => [
                                'en-GB' => 'Delivery information',
                                'de-DE' => 'Lieferinformationen',
                                'fr-FR' => 'Informations sur la livraison'
                            ]
                        ],
                        'active' => true
                    ]
                ],
                'relations' => [
                    [
                        'id' => $this->productFieldId,
                        'entityName' => $this->container->get(ProductDefinition::class)->getEntityName()
                    ]
                ]
            ]], $context);
        } catch (Exception $e) {
            // @todo Handle Exception
        }
    }

    public function deactivateCustomField(Context $context)
    {
        try {
            $this->customFieldSetRepository->upsert([[
                 'id' => $this->customFieldSetId,
                 'name' => 'welo',
                 'config' => [
                     'label' => [
                         'de-DE' => 'Lieferinformationen',
                         'en-GB' => 'Delivery information',
                         'fr-FR' => 'Informations sur la livraison'
                     ]
                 ],
                 'customFields' => [
                     [
                         'id' => $this->productFieldId,
                         'name' => 'welo_delivery_information',
                         'type' => CustomFieldTypes::TEXT,
                         'config' => [
                             'componentName' => 'sw-field',
                             'customFieldType' => CustomFieldTypes::TEXT,
                             'customFieldPosition' => 1,
                             'label' => [
                                 'en-GB' => 'Delivery information',
                                 'de-DE' => 'Lieferinformationen',
                                 'fr-FR' => 'Informations sur la livraison'
                             ]
                         ],
                         'active' => false
                     ]
                 ],
                 'relations' => [
                     [
                         'id' => $this->productFieldId,
                         'entityName' => $this->container->get(ProductDefinition::class)->getEntityName()
                     ]
                 ]
             ]], $context);
        } catch (Exception $e) {
            // @todo Handle Exception
        }
    }
}
