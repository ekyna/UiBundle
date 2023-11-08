<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Model;

use Ekyna\Bundle\UiBundle\Form\Util\FormUtil;
use Ekyna\Component\Table\View\TableView;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatableInterface;

use function array_filter;
use function array_replace;
use function array_unshift;
use function explode;
use function implode;
use function in_array;
use function is_null;
use function is_string;
use function preg_match;
use function sprintf;

/**
 * Class Modal
 * @package Ekyna\Bundle\UiBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 * @see     http://nakupanda.github.io/bootstrap3-dialog/#available-options
 */
class Modal
{
    public const MODAL_LIST    = 'list';
    public const MODAL_CREATE  = 'create';
    public const MODAL_UPDATE  = 'update';
    public const MODAL_DELETE  = 'delete';
    public const MODAL_CONFIRM = 'confirm';

    public const TYPE_DEFAULT = 'type-default';
    public const TYPE_INFO    = 'type-info';
    public const TYPE_PRIMARY = 'type-primary';
    public const TYPE_SUCCESS = 'type-success';
    public const TYPE_WARNING = 'type-warning';
    public const TYPE_DANGER  = 'type-danger';

    public const SIZE_NORMAL = 'size-normal';
    public const SIZE_SMALL  = 'size-small';
    public const SIZE_WIDE   = 'size-wide';    // size-wide is equal to modal-lg
    public const SIZE_LARGE  = 'size-large';

    public const CONTENT_HTML  = 'html';
    public const CONTENT_FORM  = 'form';
    public const CONTENT_TABLE = 'table';
    public const CONTENT_DATA  = 'data';
    public const CONTENT_TWIG  = 'twig';

    public const BTN_SUBMIT = [
        'id'           => 'submit',
        'label'        => 'button.apply',
        'trans_domain' => 'EkynaUi',
        'icon'         => 'glyphicon glyphicon-ok',
        'cssClass'     => 'btn-success',
        'autospin'     => true,
    ];

    public const BTN_CONFIRM = [
        'id'           => 'submit',
        'label'        => 'button.confirm',
        'trans_domain' => 'EkynaUi',
        'icon'         => 'glyphicon glyphicon-remove',
        'cssClass'     => 'btn-danger',
    ];

    public const BTN_CANCEL = [
        'id'           => 'close',
        'label'        => 'button.cancel',
        'trans_domain' => 'EkynaUi',
        'icon'         => 'glyphicon glyphicon-remove',
        'cssClass'     => 'btn-default',
    ];

    public const BTN_CLOSE = [
        'id'           => 'close',
        'label'        => 'button.close',
        'trans_domain' => 'EkynaUi',
        'icon'         => 'glyphicon glyphicon-remove',
        'cssClass'     => 'btn-default',
    ];

    protected static ?OptionsResolver $buttonOptionsResolver = null;

    protected string                               $type      = self::TYPE_DEFAULT;
    protected string                               $size      = self::SIZE_WIDE;
    protected bool                                 $condensed = false;
    protected ?string                              $cssClass  = null;
    protected TranslatableInterface|string|null    $title     = null;
    protected ?string                              $domain    = null;
    protected string|array|null|TableView|FormView $content   = null;
    protected array                                $vars      = [];
    protected string                               $contentType;
    protected array                                $buttons   = [];

    public function __construct(TranslatableInterface|string|null $title = null)
    {
        $this->setTitle($title);

        $this->setVars([
            'form_template' => '@EkynaUi/Modal/form.html.twig',
        ]);
    }

    public function setType(string $type): Modal
    {
        static::validateType($type);

        $this->type = $type;

        return $this;
    }

    public function setSize(string $size): Modal
    {
        static::validateSize($size);

        $this->size = $size;

        return $this;
    }

    public function setCondensed(bool $condensed): Modal
    {
        $this->condensed = $condensed;

        return $this;
    }

    public function setCssClass(?string $class): Modal
    {
        $this->cssClass = $class;

        return $this;
    }

    public function setTitle(TranslatableInterface|string|null $title): Modal
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): TranslatableInterface|string|null
    {
        return $this->title;
    }

    public function setDomain(?string $domain): Modal
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Sets the content.
     *
     * @param mixed $content
     *
     * @return Modal
     */
    public function setContent($content): Modal
    {
        if ($content instanceof FormView) {
            $this->setForm($content);
        } elseif ($content instanceof TableView) {
            $this->setTable($content);
        } elseif (is_array($content)) {
            $this->setData($content);
        } elseif (is_string($content)) {
            $this->setHtml($content);
        } else {
            throw new InvalidArgumentException(
                'Expected content as FormView, TableView, array (data) or string (html).'
            );
        }

        return $this;
    }

    private function assertContentUndefined(): void
    {
        if (!is_null($this->content)) {
            throw new LogicException('Content is already defined.');
        }
    }

    public function setForm(FormView $form, string $template = null): Modal
    {
        $this->assertContentUndefined();

        FormUtil::addClass($form, 'modal-form');

        $this->contentType = self::CONTENT_FORM;
        $this->content = $form;

        if (!empty($template)) {
            $this->setVars([
                'form_template' => $template,
            ]);
        }

        return $this;
    }

    public function setTable(TableView $table): Modal
    {
        $this->assertContentUndefined();

        $this->contentType = self::CONTENT_TABLE;
        $this->content = $table;

        return $this;
    }

    public function setData(array $data): Modal
    {
        $this->assertContentUndefined();

        $this->contentType = self::CONTENT_DATA;
        $this->content = $data;

        return $this;
    }

    public function setHtml(string $html): Modal
    {
        $this->assertContentUndefined();

        $this->contentType = self::CONTENT_HTML;
        $this->content = $html;

        return $this;
    }

    public function setTemplate(string $template, array $parameters = []): Modal
    {
        $this->assertContentUndefined();

        $this->contentType = self::CONTENT_TWIG;
        $this->content = $template;

        $this->setVars($parameters);

        return $this;
    }

    /**
     * Returns the content.
     *
     * @return FormView|TableView|array|string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    public function setVars(array $vars, bool $purge = false): Modal
    {
        if ($purge) {
            $this->vars = $vars;
        } else {
            $this->vars = array_replace($this->vars, $vars);
        }

        return $this;
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setButtons(array $buttons): Modal
    {
        $this->buttons = [];

        $resolver = static::getButtonOptionsResolver();

        foreach ($buttons as $options) {
            $this->buttons[] = $resolver->resolve($options);
        }

        return $this;
    }

    public function addButton(array $options, bool $prepend = false): Modal
    {
        $resolver = static::getButtonOptionsResolver();

        $options = $resolver->resolve($options);

        if ($prepend) {
            array_unshift($this->buttons, $options);
        } else {
            $this->buttons[] = $options;
        }

        return $this;
    }

    public function removeButton(string $id): Modal
    {
        $this->buttons = array_filter($this->buttons, function (array $button) use ($id) {
            return $button['id'] !== $id;
        });

        return $this;
    }

    public function getButtons(): array
    {
        return $this->buttons;
    }

    public function getConfig(): array
    {
        $classes = $this->cssClass ? explode(' ', $this->cssClass) : [];

        return [
            'size'      => $this->size,
            'type'      => $this->type,
            'cssClass'  => implode(' ', $classes),
            'condensed' => $this->condensed,
        ];
    }

    protected static function getButtonOptionsResolver(): OptionsResolver
    {
        if (null !== static::$buttonOptionsResolver) {
            return static::$buttonOptionsResolver;
        }

        $resolver = new OptionsResolver();
        $resolver
            ->setDefaults([
                'id'           => null,
                'icon'         => null,
                'label'        => null,
                'trans_domain' => null,
                'action'       => null,
                'autospin'     => null,
                'cssClass'     => 'btn-default',
                'hotkey'       => null,
            ])
            ->setAllowedTypes('id', 'string')
            ->setAllowedTypes('icon', ['null', 'string'])
            ->setAllowedTypes('label', 'string')
            ->setAllowedTypes('trans_domain', ['null', 'string'])
            ->setAllowedTypes('action', ['null', 'string'])
            ->setAllowedTypes('autospin', ['null', 'bool'])
            ->setAllowedTypes('cssClass', 'string')
            ->setAllowedTypes('hotkey', ['null', 'int'])
            ->setAllowedValues('action', function ($value) {
                if (null === $value) {
                    return true;
                }

                return preg_match('~^function\s?\((dialog)?\)\s?{[^}]+}$~', $value);
            });

        return static::$buttonOptionsResolver = $resolver;
    }

    public static function validateType(string $type, bool $throwException = true): bool
    {
        if (in_array($type, [
            self::TYPE_DEFAULT,
            self::TYPE_INFO,
            self::TYPE_PRIMARY,
            self::TYPE_SUCCESS,
            self::TYPE_WARNING,
            self::TYPE_DANGER,
        ])) {
            return true;
        }

        if ($throwException) {
            throw new InvalidArgumentException(sprintf('Invalid modal type "%s".', $type));
        }

        return false;
    }

    public static function validateSize(string $size, bool $throwException = true): bool
    {
        if (in_array($size, [
            self::SIZE_NORMAL,
            self::SIZE_SMALL,
            self::SIZE_WIDE,
            self::SIZE_LARGE,
        ])) {
            return true;
        }

        if ($throwException) {
            throw new InvalidArgumentException(sprintf('Invalid modal size "%s".', $size));
        }

        return false;
    }
}
