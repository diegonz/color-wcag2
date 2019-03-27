<?php

namespace Diegonz\ColorWcag2;

use Diegonz\ColorWcag2\Exceptions\ColorException;

/**
 * Class ColorWcag2.
 */
class ColorWcag2
{
    /**
     * @var string
     */
    protected static $level = 'AA';

    /**
     * @var string
     */
    protected static $type = 'Normal';

    /**
     * @return string
     */
    public static function getLevel(): string
    {
        return self::$level;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::$type;
    }

    /**
     * @param string $level
     *
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    protected static function setLevel(string $level): void
    {
        if (! \in_array($level, ['AAA', 'AA'], true)) {
            throw new ColorException('Wrong WCAG level [AAA, AA]');
        }
        self::$level = $level;
    }

    /**
     * @param string $type
     *
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    protected static function setType(string $type): void
    {
        if (! \in_array($type, ['Large', 'Medium_Bold', 'Normal'], true)) {
            throw new ColorException('Wrong type [Large, Medium_Bold, Normal]');
        }
        self::$type = $type;
    }

    /**
     * Simple hexadecimal color string parsing.
     *
     * @param string $hexadecimalColor
     *
     * @return string
     * @throws ColorException
     */
    public static function parseHexString(string $hexadecimalColor): string
    {
        // Strip # sign if present
        $color = \str_replace('#', '', $hexadecimalColor);

        if (! \ctype_xdigit($color)) {
            throw new ColorException('Invalid HEX color string ['.$color.']');
        }

        // Ensure 6 digit format
        $length = \strlen($color);
        if ($length === 3) {
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
        } elseif ($length !== 6) {
            throw new ColorException('HEX color needs to be 6 or 3 digits long');
        }

        return $color;
    }

    /**
     * Calculates the luminosity of an given RGB color,
     * the color code must be in the format of RRGGBB
     * the luminosity equations are from the WCAG 2 requirements
     * http://www.w3.org/TR/WCAG20/#relativeluminancedef.
     *
     * @param $color
     *
     * @return float
     * @throws ColorException
     */
    public static function calculateRelativeLuminance(string $color): float
    {
        $color = self::parseHexString($color);

        $red = \hexdec(\substr($color, 0, 2)) / 255;
        $green = \hexdec(\substr($color, 2, 2)) / 255;
        $blue = \hexdec(\substr($color, 4, 2)) / 255;

        $transform = function (&$color) {
            $color = $color <= 0.03928
                ? $color / 12.92
                : (($color + 0.055) / 1.055) ** 2.4;
        };

        $transform($red);
        $transform($green);
        $transform($blue);

        return 0.2126 * $red + 0.7152 * $green + 0.0722 * $blue;
    }

    /**
     * Calculates the luminosity ratio of two colors
     * the luminosity ratio equations are from the WCAG 2 requirements
     * http://www.w3.org/TR/WCAG20/#contrast-ratiodef.
     *
     * @param $color1
     * @param $color2
     *
     * @return float|int
     * @throws ColorException
     */
    public static function calculateContrastRatio(string $color1, string $color2)
    {
        $luminosityColor1 = self::calculateRelativeLuminance($color1);
        $luminosityColor2 = self::calculateRelativeLuminance($color2);

        return $luminosityColor1 > $luminosityColor2
            ? (($luminosityColor1 + 0.05) / ($luminosityColor2 + 0.05))
            : (($luminosityColor2 + 0.05) / ($luminosityColor1 + 0.05));
    }

    /**
     * Returns an array with the results of the color contrast analysis
     * it returns a key for each level (AA and AAA, both for normal and large or bold text)
     * it also returns the calculated contrast ratio
     * the ratio levels are from the WCAG 2 requirements
     * http://www.w3.org/TR/WCAG20/#visual-audio-contrast
     * http://www.w3.org/TR/WCAG20/#larger-scaledef.
     *
     * @param $color1
     * @param $color2
     *
     * @return mixed
     * @throws ColorException
     */
    public static function evaluateColorContrast(string $color1, string $color2)
    {
        $ratio = self::calculateContrastRatio($color1, $color2);
        $results = [
            'ratio' => $ratio,
            'AA'    => [
                'Large'       => $ratio >= 3,
                'Medium_Bold' => $ratio >= 3,
                'Normal'      => $ratio >= 4.5,
            ],
            'AAA'   => [
                'Large'       => $ratio >= 4.5,
                'Medium_Bold' => $ratio >= 4.5,
                'Normal'      => $ratio >= 7,
            ],
        ];

        return $results;
    }

    /**
     * Returns true if given color has enough contrast
     * when compared to white (#ffffff) to accomplish
     * WCAG 2 AAA level requirements for medium bold size.
     * https://www.w3.org/TR/WCAG20/#visual-audio-contrast-contrast.
     *
     * @param string $color
     *
     * @return bool
     * @throws ColorException
     */
    public static function isDark(string $color): bool
    {
        $results = self::evaluateColorContrast($color, '#ffffff');

        return $results[self::$level][self::$type];
    }

    /**
     * Returns true if given color has enough contrast
     * when compared to black (#000000) to accomplish
     * WCAG 2 AAA level requirements for medium bold size.
     * https://www.w3.org/TR/WCAG20/#visual-audio-contrast-contrast.
     *
     * @param string $color
     *
     * @return bool
     * @throws ColorException
     */
    public static function isLight(string $color): bool
    {
        $results = self::evaluateColorContrast($color, '#000000');

        return $results[self::$level][self::$type];
    }

    /**
     * Static factory.
     *
     * @param string $level
     * @param string $type
     *
     * @return \Diegonz\ColorWcag2\ColorWcag2
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public static function factory(string $level, string $type): self
    {
        self::setLevel($level);
        self::setType($type);

        return new static;
    }

    /**
     * Static Laravel factory loading settings from package config.
     *
     * @return \Diegonz\ColorWcag2\ColorWcag2
     * @throws \Diegonz\ColorWcag2\Exceptions\ColorException
     */
    public static function laravelFactory(): self
    {
        self::setLevel(config('color-wcag2.level'));
        self::setType(config('color-wcag2.type'));

        return new static;
    }
}
