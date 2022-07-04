<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Validator\Constraints;

use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Validator\Constraint;

/**
 * Class Configuration
 * @package Ekyna\Bundle\UiBundle\Validator\Constraints
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Configuration extends Constraint
{
    public ArrayNode $definition;
    public string    $root = 'config';

    public function getDefaultOption(): ?string
    {
        return 'definition';
    }

    public function getRequiredOptions(): array
    {
        return ['definition'];
    }
}
