<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Validator\Constraints;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class ConfigurationValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ConfigurationValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var Configuration $constraint */

        try {
            (new Processor())->process($constraint->definition, [$constraint->root => $value]);
        } catch (Exception $e) {
            $this
                ->context
                ->buildViolation($e->getMessage())
                ->addViolation();
        }
    }
}
