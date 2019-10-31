<?php

namespace Welo\DeliveryInfo6\Service;

use Exception;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\CustomField\CustomFieldTypes;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomFieldService
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EntityRepository */
    protected $customFieldSetRepository;
    
    /** @var string */
    protected $customFieldSetId;
    
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
        $this->customFieldSetId = 'cfc5bddd41594779a00cd4aa31885530';
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
            $weloProductFieldId = '14cf2e774a67a3b3374b187948046038';
            $this->customFieldSetRepository->upsert([[
                 'id' => $this->customFieldSetId,
                 'name' => 'welo',
                 'config' => [
                     'label' => [
                         'en-GB' => 'Welo'
                     ]
                 ],
                'customFields' => [
                    [
                        'id' => $weloProductFieldId,
                        'name' => 'welo_delivery_information',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'componentName' => 'sw-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 1,
                            'label' => [
                                'en-GB' => 'Delivery information',
                                'de-DE' => 'Lieferinformationen'
                            ]
                        ],
                        'active' => true
                    ]
                ],
                'relations' => [
                    [
                        'id' => $weloProductFieldId,
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
            $weloProductFieldId = '14cf2e774a67a3b3374b187948046038';
            $this->customFieldSetRepository->upsert([[
                 'id' => $this->customFieldSetId,
                 'name' => 'welo',
                 'config' => [
                     'label' => [
                         'en-GB' => 'Welo'
                     ]
                 ],
                 'customFields' => [
                     [
                         'id' => $weloProductFieldId,
                         'name' => 'welo_delivery_information',
                         'type' => CustomFieldTypes::TEXT,
                         'config' => [
                             'componentName' => 'sw-field',
                             'customFieldType' => 'text',
                             'customFieldPosition' => 1,
                             'label' => [
                                 'en-GB' => 'Delivery information',
                                 'de-DE' => 'Lieferinformationen'
                             ]
                         ],
                         'active' => false
                     ]
                 ],
                 'relations' => [
                     [
                         'id' => $weloProductFieldId,
                         'entityName' => $this->container->get(ProductDefinition::class)->getEntityName()
                     ]
                 ]
             ]], $context);
        } catch (Exception $e) {
            // @todo Handle Exception
        }
    }
}
