<?php declare(strict_types=1);

namespace Welo\DeliveryInfo6\Storefront\Subscriber;

use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Welo\DeliveryInfo6\Service\Configuration;

class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * ProductSubscriber constructor.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded'
        ];
    }

    /**
     * @param ProductPageLoadedEvent $event
     * @throws \Exception
     */
    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        //$this->dumb($event->getPage()->getProduct()->getCustomFields());
        $weloDeliveryInformation = $this->configuration->getDeliveryInformation($event->getPage()->getProduct());
        $event->getPage()->getProduct()->assign(['weloDeliveryInformation' => $weloDeliveryInformation]);
        //$this->dumb($weloDeliveryInformation);
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
