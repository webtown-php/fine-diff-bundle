<?php

namespace WebtownPHP\Bundle\FineDiffBundle\Tests\Twig\Extension;

use GorHill\FineDiff\FineDiff;
use WebtownPHP\Bundle\FineDiffBundle\Twig\Extension\DiffExtension;

class DiffExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array  $defaultGranularity
     * @param string $overrideGranularityName
     * @param string $old
     * @param string $new
     * @param string $response
     *
     * @dataProvider getTexts
     */
    public function testGetDiff($defaultGranularity, $overrideGranularityName, $old, $new, $response)
    {
        $extension = new DiffExtension($defaultGranularity);
        $diff = $extension->getDiff($old, $new, $overrideGranularityName);

        $this->assertEquals($response, $diff);
    }

    public function getTexts()
    {
        return [
            [
                FineDiff::$characterGranularity,
                null,
                '',
                '',
                ''
            ],
            [
                FineDiff::$characterGranularity,
                null,
                'test',
                'test',
                'test',
            ],
            [
                FineDiff::$characterGranularity,
                null,
                'test1',
                'test2',
                'test<del>1</del><ins>2</ins>',
            ],
            [
                FineDiff::$characterGranularity,
                'word',
                'test1 word',
                'test2 word',
                '<del>test1 </del><ins>test2 </ins>word',
            ],
            [
                FineDiff::$characterGranularity,
                'sentence',
                'test1 word',
                'test2 word',
                '<del>test1 word</del><ins>test2 word</ins>',
            ],
        ];
    }

    /**
     * @param array  $defaultGranularity
     * @param string $overrideGranularityName
     * @param string $old
     * @param string $new
     * @param string $response
     *
     * @dataProvider getTexts
     */
    public function testGetHtmlTextDiff($defaultGranularity, $overrideGranularityName, $old, $new, $response)
    {
        $extension = new DiffExtension($defaultGranularity);
        $diff = $extension->getHtmlTextDiff($old, $new, $overrideGranularityName);

        $this->assertEquals($response, $diff);
    }

    public function getHtmls()
    {
        return [
            [
                FineDiff::$characterGranularity,
                null,
                '',
                '',
                ''
            ],
            [
                FineDiff::$characterGranularity,
                null,
                'test',
                'test',
                'test',
            ],
            [
                FineDiff::$characterGranularity,
                null,
                '<b>test</b>',
                '<i>test</i>',
                'test',
            ],
            [
                FineDiff::$characterGranularity,
                null,
                'test1',
                'test2',
                'test<del>1</del><ins>2</ins>',
            ],
            [
                FineDiff::$characterGranularity,
                null,
                'Lorem <b>ipsum</b> dolor set amet',
                'Lorem ipsum dolor sit amet',
                'Lorem ipsum dolor s<del>e</del><ins>i</ins>t amet',
            ],
        ];
    }
}
