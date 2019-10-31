<?php declare(strict_types=1);

namespace Welo\DeliveryInfo6;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Welo\DeliveryInfo6\Installer\CustomFieldInstaller;
use Welo\DeliveryInfo6\Service\CustomFieldService;

class WeloDeliveryInfo6 extends Plugin
{
    public function install(InstallContext $context): void
    {
        $this->getInstaller()->install($context);
    }
    
    public function update(UpdateContext $context): void
    {
        $this->getInstaller()->update($context);
    }
    
    public function activate(ActivateContext $context): void
    {
        $this->getInstaller()->activate($context);
    }
    
    public function deactivate(DeactivateContext $context): void
    {
        $this->getInstaller()->deactivate($context);
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
        return new CustomFieldService($this->container, $this->container->get('custom_field_set.repository'));
    }
}
