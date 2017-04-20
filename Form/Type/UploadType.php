<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Ekyna\Bundle\UiBundle\Form\DataTransformer\UploadableToNullTransformer;
use Ekyna\Component\Resource\Model\UploadableInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

use function Symfony\Component\Translation\t;

/**
 * Class UploadType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', Type\FileType::class, [
            'label'        => t('field.file', [], 'EkynaUi'),
            'required'     => false,
            'admin_helper' => 'FILE_UPLOAD',
        ]);

        if ($options['js_upload']) {
            $builder->add('key', Type\HiddenType::class);
        }

        if ($options['rename_field']) {
            $builder->add('rename', Type\TextType::class, [
                'label'        => t('field.rename', [], 'EkynaUi'),
                'required'     => $options['required'],
                'admin_helper' => 'FILE_RENAME',
                'attr'         => [
                    'class'      => 'file-rename',
                ],
            ]);
        }

        if ($options['unlink_field']) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();
                    /** @var UploadableInterface $data */
                    $data = $event->getData();

                    if (null !== $data && null !== $data->getPath()) {
                        $form->add('unlink', Type\CheckboxType::class, [
                            'label'        => t('field.unlink', [], 'EkynaUi'),
                            'required'     => false,
                            'admin_helper' => 'FILE_UNLINK',
                            'attr'         => [
                                'align_with_widget' => true,
                            ],
                        ]);
                    }
                }
            );
        }

        $builder->addModelTransformer(new UploadableToNullTransformer());
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (array_key_exists('file_path', $options) && !empty($filePath = $options['file_path'])) {
            $data = $form->getData();
            $currentPath = null;
            $currentName = null;
            if (null !== $data) {
                $accessor = PropertyAccess::createPropertyAccessor();
                if ($currentPath = $accessor->getValue($data, $filePath)) {
                    $currentName = pathinfo($currentPath, PATHINFO_BASENAME);
                }
            }
            $view->vars['current_file_path'] = $currentPath;
            $view->vars['current_file_name'] = $currentName;
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['js_upload']) {
            $view->children['key']->vars['attr']['data-target'] = $view->children['file']->vars['id'];
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label'          => t('field.file', [], 'EkynaUi'),
                'data_class'     => UploadableInterface::class,
                'file_path'      => 'path',
                'rename_field'   => true,
                'unlink_field'   => false,
                'js_upload'      => true,
                'error_bubbling' => false,
            ])
            ->setAllowedTypes('file_path', ['null', 'string'])
            ->setAllowedTypes('rename_field', 'bool')
            ->setAllowedTypes('unlink_field', 'bool')
            ->setAllowedTypes('js_upload', 'bool');
    }

    public function getBlockPrefix(): string
    {
        return 'ekyna_upload';
    }
}
