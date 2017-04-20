<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Model;

use ArrayAccess;

/**
 * Class KeyValueContainer
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Bart van den Burg <bart@burgov.nl>
 * @see https://github.com/Burgov/KeyValueFormBundle/blob/master/KeyValueContainer.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class KeyValueContainer implements ArrayAccess
{
    private array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}
