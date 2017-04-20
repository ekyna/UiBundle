<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Event;

use Ekyna\Bundle\UiBundle\Model\Modal;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ModalEvent
 * @package Ekyna\Bundle\CoreBundle\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ModalEvent extends Event
{
    public const MODAL_RESPONSE = 'ekyna_ui.modal.response';

    private Modal $modal;


    /**
     * Constructor.
     *
     * @param Modal $modal
     */
    public function __construct(Modal $modal)
    {
        $this->modal = $modal;
    }

    /**
     * Returns the modal.
     *
     * @return Modal
     */
    public function getModal(): Modal
    {
        return $this->modal;
    }
}
