<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Extension;

use Ekyna\Bundle\UiBundle\Form\Util\FormUtil;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Select2Extension
 * @package Ekyna\Bundle\UiBundle\Form\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Select2Extension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('select2', true)
            ->setAllowedTypes('select2', ['bool', 'array']);
    }

    /**
     * @inheritDoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (true === $options['expanded']) {
            return;
        }

        if (false === $select2 = $options['select2']) {
            return;
        }

        FormUtil::addClass($view, 'select2');

        if (true === $select2) {
            $select2 = [];
        }

        $select2 = array_replace([
            'allowClear'  => true,
            'placeholder' => $view->vars['placeholder'] ?? null,
        ], $select2);

        if ($options['required']) {
            $select2['allowClear'] = false;
        }

        if (!$select2['allowClear'] && empty($select2['placeholder'])) {
            $select2['placeholder'] = 'Choose';
        }

        foreach ($select2 as $key => $value) {
            $view->vars['attr']['data-' . $this->dasherize($key)] = (string)$value;
        }
    }

    /**
     * Dasherizes the given text.
     *
     * @param string $text
     *
     * @return string
     */
    public function dasherize(string $text): string
    {
        return trim(strtolower(preg_replace(['/([A-Z])/', '/[-_\s]+/'], ['-$1', '-'], $text)), ' -');
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [ChoiceType::class];
    }
}
