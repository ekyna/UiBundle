<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\DataTransformer;

use Ekyna\Component\Resource\Model\Anniversary;
use Symfony\Component\Form\DataTransformerInterface;

use function array_replace;
use function is_array;

/**
 * Class AnniversaryToArrayModelTransformer
 * @package Ekyna\Bundle\UiBundle\Form\DataTransformer
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AnniversaryToArrayModelTransformer implements DataTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if ($value instanceof Anniversary) {
            return [
                'month' => (string)$value->getMonth(),
                'day'   => (string)$value->getDay(),
            ];
        }

        return [
            'month' => null,
            'day'   => null,
        ];
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if (!is_array($value)) {
            return null;
        }

        $value = array_replace([
            'month' => null,
            'day'   => null,
        ], $value);

        return new Anniversary(
            (int)$value['month'],
            (int)$value['day'],
        );
    }
}
