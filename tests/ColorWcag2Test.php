<?php
/**
 * Created by PhpStorm.
 * User: hal9k
 * Date: 24/03/19
 * Time: 21:45.
 */

namespace Diegonz\Tests\ColorWcag2;

use PHPUnit\Framework\TestCase;
use Diegonz\ColorWcag2\ColorWcag2;

/**
 * Class ColorWcag2Test.
 */
class ColorWcag2Test extends TestCase
{
    /**
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public function testParseHexString(): void
    {
        $result = ColorWcag2::parseHexString('#cdcdcd');
        $this->assertEquals('cdcdcd', $result);

        $result = ColorWcag2::parseHexString('#fff');
        $this->assertEquals('ffffff', $result);
    }

    /**
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public function testCalculateLuminance(): void
    {
        $result = ColorWcag2::calculateRelativeLuminance('#ffffff');
        $this->assertEquals(100, \round($result * 100, 2));

        $result = ColorWcag2::calculateRelativeLuminance('#000000');
        $this->assertEquals(0, \round($result * 100, 2));

        $result = ColorWcag2::calculateRelativeLuminance('#0000ff');
        $this->assertEquals(7.22, \round($result * 100, 2));

        $result = ColorWcag2::calculateRelativeLuminance('#ff0000');
        $this->assertEquals(21.26, \round($result * 100, 2));

        $result = ColorWcag2::calculateRelativeLuminance('#fff000');
        $this->assertEquals(83.58, \round($result * 100, 2));
    }

    /**
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public function testCalculateContrastRatio(): void
    {
        $result = ColorWcag2::calculateContrastRatio('#ff0000', '#00ff00');
        $this->assertEquals(2.91, \round($result, 2));

        $result = ColorWcag2::calculateContrastRatio('#fff000', '#000fff');
        $this->assertEquals(7.05, \round($result, 2));

        $result = ColorWcag2::calculateContrastRatio('#ffffff', '#000000');
        $this->assertEquals(21, \round($result, 2));
    }

    /**
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public function testEvaluateColorContrast(): void
    {
        $result = ColorWcag2::evaluateColorContrast('#fff000', '#000fff');
        $this->assertTrue($result['AA']['Large']);
        $this->assertTrue($result['AA']['Medium_Bold']);
        $this->assertTrue($result['AA']['Normal']);
        $this->assertTrue($result['AAA']['Large']);
        $this->assertTrue($result['AAA']['Medium_Bold']);
        $this->assertTrue($result['AAA']['Normal']);

        $result = ColorWcag2::evaluateColorContrast('#fff000', '#ff0000');
        $this->assertTrue($result['AA']['Large']);
        $this->assertTrue($result['AA']['Medium_Bold']);
        $this->assertFalse($result['AA']['Normal']);
        $this->assertFalse($result['AAA']['Large']);
        $this->assertFalse($result['AAA']['Medium_Bold']);
        $this->assertFalse($result['AAA']['Normal']);
    }

    /**
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public function testContrast()
    {
        // #637fa8 LIGHT / #5b77a0 DARK

        $this->assertFalse(ColorWcag2::contrast('#637fa8', '#ffffff'));
        $this->assertTrue(ColorWcag2::contrast('#5b77a0', '#ffffff'));
    }

    /**
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public function testIsDark()
    {
        $this->assertFalse(ColorWcag2::isDark('#637fa8'));
    }

    /**
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public function testIsLight()
    {
        $this->assertTrue(ColorWcag2::isLight('#5b77a0'));
    }
}
