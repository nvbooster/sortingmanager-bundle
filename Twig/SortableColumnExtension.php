<?php
namespace nvbooster\SortingManagerBundle\Twig;

use nvbooster\SortingManager\Control;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class SortableColumnExtension extends \Twig_Extension
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var \Twig_Environment
     */
    protected $env;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param RequestStack        $stack
     * @param TranslatorInterface $translator
     */
    public function __construct(RequestStack $stack, TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
        $this->request = $stack->getMasterRequest();
    }

    /**
     * {@inheritdoc}
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('sortable_column', [$this, 'renderColumnHeader'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        ];
    }

    /**
     * @param \Twig_Environment $env
     * @param Control           $control
     * @param string            $name
     * @param array             $options
     *
     * @return string
     */
    public function renderColumnHeader(\Twig_Environment $env, Control $control, $name, $options = array())
    {
        if ($control->isColumnSortable($name)) {
            $options = array_merge($control->getColumnOptions($name), $options);
            $classes = [$this->escape($env, $options['column_sortable_class'])];
            if ($control->isColumnSorted($name)) {
                $classes[] = $this->escape($env, $control->getColumnSortOrder($name) > 0 ? $options['column_ascend_class'] : $options['column_descend_class']);
            }
            $link = $this->buildLink([
                $control->getSortByParam() => $name,
                $control->getSortOrderParam() => $control->getColumnSortOrder($name) > 0 ? -1 : 1
            ]);
            $label = $this->escape($env, $this->translator ? $this->translator->trans($options['label'], array(), $options['translation_domain']) : $options['label']);

            return '<a href="' . $link . '" class="' . implode(' ', $classes) . '">' . $label . '</a>';
        } else {
            return ucfirst($name);
        }
    }

        /**
     * @param array $params
     *
     * @return string
     */
    protected function buildLink($params = array())
    {
        $params = array_merge(
            $this->request->query->all(),
            $params
        );

        return '?' . Request::normalizeQueryString(implode('&', $this->plainparams($params)));
    }

    /**
     * @param array $params
     */
    protected function plainparams($params = array(), $parent = false)
    {
        $parts = array();
        foreach ($params as $k => $v) {
            $name = $parent ? $parent . '[' . (is_int($k) ? '' : $k) . ']' : $k;
            if (is_array($v)) {
                foreach ($this->plainparams($v, $name) as $part) {
                    $parts[] = $part;
                }
            } else {
                $parts[] = $name . '=' . $v;
            }
        }

        return $parts;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Twig_Extension::initRuntime()
     */
    public function initRuntime(\Twig_Environment $env)
    {
        parent::initRuntime($env);
        $this->env = $env;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function escape($string)
    {
        return twig_escape_filter($this->env, $string);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'sortingmanager_twigextension';
    }
}
