<?php
namespace Welo\DeliveryInfo6\Service;

use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\System\SystemConfig\SystemConfigService;

/**
 * Class Config
 *
 * @author Steven Thorne <shopware@webloupe.de>
 * @copyright Copyright (c) 2017-2019 Web Loupe
 * @package DeliveryInfo6\Service
 * @version   1
 */
class Configuration
{
    /**
     * @var \Shopware\Core\System\SystemConfig\SystemConfigService
     */
    private $systemConfigService;
    
    public const WELO_CONFIG_DOMAIN = 'WeloDeliveryInfo6.config.';
    
    const WELODeliveryInfo6DATA = 'WeloDeliveryInfo6Data';
    const WELODeliveryInfo6COLOR = 'WeloDeliveryInfo6Color';
    const WELODeliveryInfo6MARINGINTOP = 'WeloDeliveryInfo6MarginTop';
    const WELODeliveryInfo6FONTSIZE = 'WeloDeliveryInfo6FontSize';
    const DEFAULTCOLOR = '#2ecc71';
    const DEFAULTMARGIN = 15;
    const DEFAULTFONTSIZE = 13;
    
    /**
     * Configuration constructor.
     *
     * @param \Shopware\Core\System\SystemConfig\SystemConfigService $systemConfigService
     */
    public function __construct(SystemConfigService $systemConfigService) {
        $this->systemConfigService = $systemConfigService;
    }
    
    /**
     * @param \Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity $productEntity
     * @return array|string
     * @throws \Exception
     */
    public function getDeliveryInformation(SalesChannelProductEntity $productEntity = null)
    {
        $deliveryData = [];
        if (null === $productEntity) {
            return [];
        }
    
        $customFields = $productEntity->getCustomFields();
        
        $customFields['welo']['welo_delivery_information'];
        $this->dumb($productEntity->getId());
        
        
        if (true === empty($deliveryData)) {
            $deliveryData = $this->getConfigByKey(self::WELODeliveryInfo6DATA);
            $deliveryData = array_unique(array_filter(explode(PHP_EOL, trim($deliveryData))));
        }
        
        return $deliveryData;
    }
    
    /**
     * @param $key
     * @return bool|mixed
     * @throws \Exception
     */
    public function getConfigByKey($key)
    {
        return $this->systemConfigService->get(self::WELO_CONFIG_DOMAIN . $key);
    }
    
    /**
     * Helper method for debugging
     * @param $data
     */
    function dumb($data){
        highlight_string("<?php\n " . var_export($data, true) . "?>");
        echo '<script>document.getElementsByTagName("code")[0].getElementsByTagName("span")[1].remove() ;document.getElementsByTagName("code")[0].getElementsByTagName("span")[document.getElementsByTagName("code")[0].getElementsByTagName("span").length - 1].remove() ; </script>';
        die();
    }
}
