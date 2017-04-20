<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\DataTransformer;

use Ekyna\Component\Resource\Model\UploadableInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class UploadableToNullTransformer
 * @package Ekyna\Bundle\UiBundle\Form\DataTransformer
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UploadableToNullTransformer implements DataTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if ($value instanceof UploadableInterface && $value->getUnlink()) {
            return null;
        }

        return $value;
    }
}
