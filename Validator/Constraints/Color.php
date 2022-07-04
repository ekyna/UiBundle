<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Color
 * @package Ekyna\Bundle\UiBundle\Validator\Constraints
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Color extends Constraint
{
    public string $invalidCode   = 'color.invalid_code';
    public string $unknownFormat = 'color.unknown_format';
}
