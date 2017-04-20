<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use function preg_match;
use function sprintf;

/**
 * Class UriVoter
 * @package Ekyna\Bundle\UiBundle\Service\Menu
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UriVoter implements VoterInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritDoc
     */
    public function matchItem(ItemInterface $item): ?bool
    {
        if (null === $request = $this->requestStack->getMainRequest()) {
            return null;
        }

        $uri = $item->getUri();

        if (empty($uri) || '/' === $uri) {
            return null;
        }

        if (preg_match(sprintf('#^%s#', $uri), $request->getPathInfo())) {
            return true;
        }

        return null;
    }
}
