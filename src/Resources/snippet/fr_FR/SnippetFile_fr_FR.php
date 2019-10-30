<?php declare(strict_types=1);

namespace Welo\DeliveryInfo6\Resources\snippet\fr_FR;

use Shopware\Core\Framework\Snippet\Files\SnippetFileInterface;

class SnippetFile_fr_FR implements SnippetFileInterface
{
    public function getName(): string
    {
        return 'storefront.fr-FR';
    }

    public function getPath(): string
    {
        return __DIR__ . '/storefront.fr-FR.json';
    }

    public function getIso(): string
    {
        return 'fr-FR';
    }

    public function getAuthor(): string
    {
        return 'Web Loupe';
    }

    public function isBase(): bool
    {
        return false;
    }
}
