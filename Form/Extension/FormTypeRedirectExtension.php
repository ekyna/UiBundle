<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormTypeRedirectExtension
 * @package Ekyna\Bundle\UiBundle\Form\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FormTypeRedirectExtension extends AbstractTypeExtension
{
    protected RequestStack $requestStack;


    /**
     * Constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Abort if not enabled
        if (!(array_key_exists('_redirect_enabled', $options) && $options['_redirect_enabled'] === true)) {
            return;
        }

        // Retrieve the _redirect path from request (GET)
        $redirectPath = null;
        if (null !== $request = $this->requestStack->getCurrentRequest()) {
            $redirectPath = $request->query->get('_redirect');
        }

        // Add the hidden field
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();

                // Only "root" form
                if (null !== $form->getParent()) {
                    return;
                }

                $form->add('_redirect', HiddenType::class, ['mapped' => false]);
            }
        );

        // Sets the data
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($redirectPath, $request) {
                $form = $event->getForm();

                // Only "root" form
                if (null !== $form->getParent()) {
                    return;
                }

                // If form has been posted => retrieve _redirect path from request (POST)
                if (null === $redirectPath && null !== $request) {
                    $data = $request->request->get($form->getName(), ['_redirect' => null]);
                    if (array_key_exists('_redirect', $data)) {
                        $redirectPath = $data['_redirect'];
                    }
                }

                $form->get('_redirect')->setData($redirectPath);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(['_redirect_enabled'])
            ->setAllowedTypes('_redirect_enabled', 'bool');
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
