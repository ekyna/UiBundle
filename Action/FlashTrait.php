<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Action;

use Ekyna\Bundle\UiBundle\Service\FlashHelper;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Contracts\Translation\TranslatableInterface;

use function implode;

/**
 * Trait FlashTrait
 * @package Ekyna\Bundle\UiBundle\Action
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
trait FlashTrait
{
    private FlashHelper $flashHelper;

    /**
     * @required
     */
    public function setFlashHelper(FlashHelper $flashHelper): void
    {
        $this->flashHelper = $flashHelper;
    }

    /**
     * @param string|TranslatableInterface $message
     */
    protected function addFlash($message, string $type): void
    {
        $this->flashHelper->addFlash($message, $type);
    }

    protected function addFlashFromEvent(ResourceEventInterface $event): void
    {
        $this->flashHelper->fromEvent($event);
    }

    protected function addFlashFromViolationList(ConstraintViolationListInterface $list): void
    {
        if (0 === $list->count()) {
            return;
        }

        $messages = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($list as $violation) {
            $messages[] = $violation->getMessage();
        }

        $this->addFlash(implode('<br>', $messages), 'danger');
    }
}
