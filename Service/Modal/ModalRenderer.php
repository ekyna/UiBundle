<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service\Modal;

use Ekyna\Bundle\UiBundle\Event\ModalEvent;
use Ekyna\Bundle\UiBundle\Model\Modal;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class ModalRenderer
 * @package Ekyna\Bundle\UiBundle\Service\Modal
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ModalRenderer
{
    protected Environment              $twig;
    protected TranslatorInterface      $translator;
    protected EventDispatcherInterface $dispatcher;
    protected array                    $config;


    /**
     * Constructor.
     *
     * @param Environment              $twig
     * @param TranslatorInterface      $translator
     * @param EventDispatcherInterface $dispatcher
     * @param array                    $config
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        EventDispatcherInterface $dispatcher,
        array $config
    ) {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->dispatcher = $dispatcher;

        $this->config = array_replace([
            'template' => '@EkynaUi/Modal/modal.xml.twig',
            'charset'  => 'UTF-8',
        ], $config);
    }

    /**
     * Renders and returns the modal response.
     *
     * @param Modal       $modal
     * @param string|null $template
     *
     * @return Response
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function render(Modal $modal, string $template = null): Response
    {
        // Translations
        $modal->setTitle($this->translator->trans($modal->getTitle()));
        $buttons = $modal->getButtons();
        foreach ($buttons as &$button) {
            $button['label'] = $this->translator->trans($button['label'], [], $button['trans_domain']);
        }
        $modal->setButtons($buttons);

        // Event
        $this->dispatcher->dispatch(new ModalEvent($modal), ModalEvent::MODAL_RESPONSE);

        if (empty($template)) {
            $template = $this->config['template'];
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $content = $this->twig->render($template, ['modal' => $modal]);

        $response = new Response();
        $response->setContent($content);

        $response->headers->set(
            'Content-Type',
            'application/xml; charset=' . strtolower($this->config['charset']),
            true
        );

        return $response;
    }

    /**
     * Returns the translator.
     *
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
}
