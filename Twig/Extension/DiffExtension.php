<?php

namespace WebtownPHP\Bundle\FineDiffBundle\Twig\Extension;

use GorHill\FineDiff\FineDiff;
use WebtownPHP\Bundle\FineDiffBundle\DependencyInjection\Configuration;

class DiffExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $defaultGranularity;

    /**
     * DiffExtension constructor.
     * @param array $defaultGranularity
     */
    public function __construct(array $defaultGranularity)
    {
        $this->defaultGranularity = $defaultGranularity;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('renderDiff', [$this, 'getDiff'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('renderHtmlTextDiff', [$this, 'getHtmlTextDiff'], ['is_safe' => ['html']]),
        ];
    }

    public function getDiff($old, $new, $granularity = null)
    {
        $granularity = $granularity
            ? $this->getGranularity($granularity)
            : $this->defaultGranularity;
        $opcodes = FineDiff::getDiffOpcodes($old, $new, $granularity);
        $html = FineDiff::renderDiffToHTMLFromOpcodes($old, $opcodes);

        return $html;
    }

    public function getHtmlTextDiff($old, $new, $granularity = null)
    {
        $old = strip_tags($old);
        $new = strip_tags($new);

        return $this->getDiff($old, $new, $granularity);
    }

    protected function getGranularity($name)
    {
        $granularities = Configuration::getGranularities();

        if (!array_key_exists($name, $granularities)) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown granularity: `%s`. There are these: `%s`',
                $name,
                implode('`, `', array_keys($granularities))
            ));
        }

        return $granularities[$name];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'webtown_php_fine_diff.twig_extension';
    }
}
