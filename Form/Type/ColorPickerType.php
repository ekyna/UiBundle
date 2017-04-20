<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ColorPickerType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 * @see     https://itsjavi.com/bootstrap-colorpicker/
 */
class ColorPickerType extends AbstractType
{
    /**
     * @var array
     */
    private array $colorsMap = [];


    /**
     * Constructor.
     *
     * @param array $colors
     */
    public function __construct(array $colors = [])
    {
        foreach ($colors as $color) {
            $this->colorsMap['#' . $color] = '#' . $color;
        }
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $pickerOptions = [
            'component' => '.input-group-btn',
        ];
        if (!empty($view->vars['value'])) {
            $pickerOptions['color'] = $view->vars['value'];
        }

        if (!empty($this->colorsMap)) {
            $pickerOptions['colorSelectors'] = $this->colorsMap;
        }

        if ($options['doubleSize']) {
            $pickerOptions['customClass'] = 'colorpicker-2x';
            $pickerOptions['sliders'] = [
                'saturation' => [
                    'maxLeft' => 200,
                    'maxTop'  => 200,
                ],
                'hue'        => [
                    'maxTop' => 200,
                ],
                'alpha'      => [
                    'maxTop' => 200,
                ],
            ];
        }

        $view->vars = array_replace($view->vars, [
            'pickerOptions' => $pickerOptions,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'doubleSize' => true,
            ])
            ->addAllowedTypes('doubleSize', 'bool');
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix(): string
    {
        return 'ekyna_color_picker';
    }
}
