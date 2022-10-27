<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\DataTransformer;

use Ekyna\Component\Resource\Model\DateRange;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class StringToDateRangeTransformer
 * @package Ekyna\Bundle\UiBundle\Form\DataTransformer
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class StringToDateRangeTransformer implements DataTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return DateRange::fromString((string)$value);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if ($value instanceof DateRange) {
            return $value->toString();
        }

        return null;
    }
}
