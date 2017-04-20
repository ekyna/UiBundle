<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Model;

use Symfony\Contracts\Translation\TranslatableInterface;

/**
 * Class UiButton
 * @package Ekyna\Bundle\UiBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UiButton
{
    private TranslatableInterface $label;
    private array                 $options;
    private array                 $attributes;
    private int                   $priority;

    public function __construct(
        TranslatableInterface $label,
        array                 $options = [],
        array                 $attributes = [],
        int                   $priority = 0
    ) {
        $this->label = $label;
        $this->options = $options;
        $this->attributes = $attributes;
        $this->priority = $priority;
    }

    public function getLabel(): TranslatableInterface
    {
        return $this->label;
    }

    public function setLabel(TranslatableInterface $label): UiButton
    {
        $this->label = $label;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options = []): UiButton
    {
        $this->options = $options;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes = []): UiButton
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): UiButton
    {
        $this->priority = $priority;

        return $this;
    }
}
