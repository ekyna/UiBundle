<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Ekyna\Bundle\UiBundle\Validator\Constraints\Configuration;
use InvalidArgumentException;
use Symfony\Component\Config\Definition as Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

/**
 * Class ConfigurationType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $definition = $options['definition'];

        if ($definition instanceof Node\ArrayNode) {
            foreach ($definition->getChildren() as $child) {
                $this->addNodeType($builder, $child);
            }
        } else {
            $this->addNodeType($builder, $definition);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label'       => t('field.config', [], 'EkynaUi'),
                'definition'  => null,
                'root'        => 'config',
                'constraints' => function (Options $options) {
                    return [
                        new Configuration([
                            'definition' => $options['definition'],
                            'root'       => $options['root'],
                        ]),
                    ];
                },
            ])
            ->setAllowedTypes('definition', Node\ArrayNode::class)
            ->setAllowedTypes('root', 'string');
    }

    /**
     * Adds the node to the form.
     */
    private function addNodeType(FormBuilderInterface $builder, Node\NodeInterface $node): void
    {
        $class = get_class($node);

        switch ($class) {
            case Node\ArrayNode::class:
                /** @noinspection PhpParamsInspection */
                $this->addArrayNodeType($builder, $node);
                break;
            case Node\IntegerNode::class:
                /** @noinspection PhpParamsInspection */
                $this->addIntegerNodeType($builder, $node);
                break;
            case Node\FloatNode::class:
            case Node\NumericNode::class:
                /** @noinspection PhpParamsInspection */
                $this->addNumericNodeType($builder, $node);
                break;
            case Node\EnumNode::class:
                /** @noinspection PhpParamsInspection */
                $this->addEnumNodeType($builder, $node);
                break;
            case Node\BooleanNode::class:
                /** @noinspection PhpParamsInspection */
                $this->addBooleanNodeType($builder, $node);
                break;
            case Node\ScalarNode::class:
                /** @noinspection PhpParamsInspection */
                $this->addScalarNodeType($builder, $node);
                break;
            //case Node\VariableNode::class:
            default:
                throw new InvalidArgumentException('Unsupported node type.');
        }
    }

    private function addArrayNodeType(FormBuilderInterface $builder, Node\ArrayNode $node): void
    {
        if (empty($node->getChildren())) {
            return;
        }

        $sub = $builder->create($node->getName(), null, [
            'label'    => $this->getNodeLabel($node),
            'compound' => true,
        ]);

        foreach ($node->getChildren() as $child) {
            $this->addNodeType($sub, $child);
        }

        $builder->add($sub);
    }

    private function addIntegerNodeType(FormBuilderInterface $builder, Node\IntegerNode $node): void
    {
        $builder->add($node->getName(), Type\IntegerType::class, [
            'label'    => $this->getNodeLabel($node),
            'required' => $node->isRequired(),
        ]);
    }

    private function addNumericNodeType(FormBuilderInterface $builder, Node\NumericNode $node): void
    {
        $builder->add($node->getName(), Type\NumberType::class, [
            'label'    => $this->getNodeLabel($node),
            'required' => $node->isRequired(),
        ]);
    }

    private function addEnumNodeType(FormBuilderInterface $builder, Node\EnumNode $node): void
    {
        $choices = $node->getValues();

        /*$choices = array_combine(array_map(function ($value) {
            return ucfirst($value);
        }, $choices), $choices);*/

        $options = [
            'label'    => $this->getNodeLabel($node),
            'required' => $node->isRequired(),
            'choices'  => $choices,
            'select2'  => false,
        ];

        if (null !== $default = $node->getDefaultValue()) {
            $options['prefered_choice'] = $default;
        }

        $builder->add($node->getName(), Type\ChoiceType::class, $options);
    }

    private function addBooleanNodeType(FormBuilderInterface $builder, Node\BooleanNode $node): void
    {
        $options = [
            'label' => $this->getNodeLabel($node),
            'attr'  => [
                'align_with_widget' => true,
            ],
        ];

        if ($node->getDefaultValue()) {
            $options['attr']['checked'] = true;
        }

        $builder->add($node->getName(), Type\CheckboxType::class, $options);
    }

    private function addScalarNodeType(FormBuilderInterface $builder, Node\ScalarNode $node): void
    {
        $builder->add($node->getName(), Type\TextType::class, [
            'label'    => $this->getNodeLabel($node),
            'required' => $node->isRequired(),
        ]);
    }

    private function getNodeLabel(Node\NodeInterface $node): string
    {
        /** @var Node\BaseNode $node */
        if (!empty($label = $node->getInfo())) {
            return $label;
        }

        return ucfirst($node->getName());
    }
}
