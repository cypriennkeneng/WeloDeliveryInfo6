<?php declare(strict_types=1);

namespace Welo\DeliveryInfo6\Storefront\Subscriber;

use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Welo\DeliveryInfo6\Service\Configuration;

class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Welo\DeliveryInfo6\Service\Configuration
     */
    private $configuration;
    
    /**
     * ProductSubscriber constructor.
     *
     * @param \Welo\DeliveryInfo6\Service\Configuration $configuration
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
     * @param \Shopware\Storefront\Page\Product\ProductPageLoadedEvent $event
     * @throws \Exception
     */
    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        $weloDeliveryInfo6rmation = $this->configuration->getDeliveryInfo6rmation($event->getPage()->getProduct()->getId());
        $event->getPage()->getProduct()->assign(['weloDeliveryInfo6rmation' => $weloDeliveryInfo6rmation]);
    }
}
