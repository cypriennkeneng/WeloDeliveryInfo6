<?php declare(strict_types=1);

namespace Welo\DeliveryInfo6;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Welo\DeliveryInfo6\Service\CustomFieldService;

class WeloDeliveryInfo6 extends Plugin
{
    public function install(InstallContext $context): void
    {
        $this->getInstaller()->install($context);
    }
    
    public function uninstall(UninstallContext $context): void
    {
        if ($context->keepUserData()) {
            return;
        }
        $this->getInstaller()->uninstall($context);
    }
    
    public function getInstaller()
    {
        return new CustomFieldService(
            $this->container,
            $this->container->get('custom_field.repository'),
            $this->container->get('custom_field_set.repository')
        );
    }
}
