<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service;

use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Class FlashHelper
 * @package Ekyna\Bundle\UiBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FlashHelper
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * Creates a flash message.
     */
    public function addFlash(TranslatableMessage|string $message, string $type): void
    {
        if (!$bag = $this->getFlashBag()) {
            return;
        }

        $bag->add($type, $message);
    }

    /**
     * Creates flash messages from the resource event.
     */
    public function fromEvent(ResourceEventInterface $event): void
    {
        if (!$bag = $this->getFlashBag()) {
            return;
        }

        foreach ($event->getMessages() as $message) {
            $bag->add($message->getType(), $message);
        }
    }

    private function getFlashBag(): ?FlashBagInterface
    {
        try {
            return $this->requestStack->getSession()->getFlashBag();
        } catch (SessionNotFoundException) {
        }

        return null;
    }
}
