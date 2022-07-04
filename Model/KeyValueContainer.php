<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Model;

use ArrayAccess;

/**
 * Class KeyValueContainer
 * @package Ekyna\Bundle\UiBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class KeyValueContainer implements ArrayAccess
{
    public function __construct(private array $data = [])
    {
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }
}
