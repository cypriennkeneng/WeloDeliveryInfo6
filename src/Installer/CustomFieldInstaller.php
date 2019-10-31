<?php

declare(strict_types=1);

namespace Welo\DeliveryInfo6\Installer;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\CustomField\CustomFieldTypes;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomFieldInstaller implements InstallerInterface
{
    public const DELIVERY_INFORMATION      = 'welo_delivery_information';

    /** @var EntityRepositoryInterface */
    private $customFieldSetRepository;
    
    /** @var ContainerInterface */
    protected $container;

    /** @var array */
    private $customField;
    
    /** @var array */
    private $relation;
    
    public function __construct(
        $container,
        EntityRepository $customFieldSetRepository
    ) {
        $this->container = $container;
        $this->customFieldSetRepository = $customFieldSetRepository;
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
//        $this->deactivateCustomField($context->getContext());
    }

    public function activate(ActivateContext $context): void
    {
        $this->upsertCustomField($context->getContext());
    }

    public function deactivate(DeactivateContext $context): void
    {
//        $this->deactivateCustomField($context->getContext());
    }

    private function upsertCustomField(Context $context): void
    {
        $this->customFieldSetRepository->upsert([[
             'customFields' => [
                 [
                     'id' => '14cf2e774a67a3b3374b187948046038',
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
                     'id' => '14cf2e774a67a3b3374b187948046038',
                     'entityName' => $this->container->get(ProductDefinition::class)->getEntityName()
                 ]
             ]
         ]], $context);
    }

    private function deactivateCustomField(Context $context): void
    {
        $this->customFieldSetRepository->upsert([[
             'customFields' => [
                 [
                     'id' => '14cf2e774a67a3b3374b187948046038',
                     'name' => self::DELIVERY_INFORMATION,
                     'type' => CustomFieldTypes::TEXT,
                     'config' => [
                         'componentName' => 'sw-field',
                         'customFieldType' => 'text',
                         'customFieldPosition' => 1,
                         'label' => [
                             'en-GB' => 'Delivery information',
                             'de-DE' => 'Lieferinformationen'
                         ]
                     ]
                 ]
             ],
             'relations' => [
                 [
                     'id' => '14cf2e774a67a3b3374b187948046038',
                     'entityName' => $this->container->get(ProductDefinition::class)->getEntityName()
                 ]
             ]
         ]], $context);
    }
    
    private function getCustomField()
    {
        return [
            [
                'id' => '14cf2e774a67a3b3374b187948046038',
                'name' => self::DELIVERY_INFORMATION,
                'type' => CustomFieldTypes::TEXT,
                'config' => [
                    'componentName' => 'sw-field',
                    'customFieldType' => 'text',
                    'customFieldPosition' => 1,
                    'label' => [
                        'en-GB' => 'Delivery information',
                        'de-DE' => 'Lieferinformationen'
                    ]
                ]
            ]
        ];
    }
    
    private function getRelation()
    {
        return [
            [
                'id' => '14cf2e774a67a3b3374b187948046038',
                'entityName' => $this->container->get(ProductDefinition::class)->getEntityName()
            ]
        ];
    }
}
