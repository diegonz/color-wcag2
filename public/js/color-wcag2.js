// TODO: Implement Javascript version
(function () {

    let colorwcag2 = (function () {

        let parseHexString = function (hexColorString) {
            let color = hexColorString.replace('#', '');
            let isValidHexString = function (str) {
                let hex = parseInt(str, 16);
                return (hex.toString(16) === str)
            };
            if (!isValidHexString(color)) {
                return undefined;
            }
            if (color.length === 3) {
                color = color.charAt(0) + color.charAt(0) + color.charAt(1) + color.charAt(1) + color.charAt(2) + color.charAt(2);
            } else if (color.length !== 6) {
                return undefined;
            }

            return color;
        };

        let calculateRelativeLuminance = function (color) {
            color = parseHexString(color);

            let red = parseInt(color.substring(0, 2), 16);
            let green = parseInt(color.substring(2, 4), 16);
            let blue = parseInt(color.substring(4, 6), 16);

            let transformColor = function (color) {
                return color <= 0.03928
                    ? color / 12.92
                    : ((color + 0.055) / 1.055) ** 2.4;
            };

            return 0.2126 * transformColor(red) + 0.7152 * transformColor(green) + 0.0722 * transformColor(blue);
        };

        let calculateContrastRatio = function (color1, color2) {
            let luminanceColor1 = calculateRelativeLuminance(color1);
            let luminanceColor2 = calculateRelativeLuminance(color2);

            return luminanceColor1 > luminanceColor2
                ? ((luminanceColor1 + 0.05) / (luminanceColor2 + 0.05))
                : ((luminanceColor2 + 0.05) / (luminanceColor1 + 0.05));
        };

        let evaluateColorContrast = function (color1, color2) {
            let ratio = calculateContrastRatio(color1, color2);
            return {
                'ratio': ratio,
                'AA': {
                    'Large': ratio >= 3,
                    'Medium_Bold': ratio >= 3,
                    'Normal': ratio >= 4.5
                },
                'AAA': {
                    'Large': ratio >= 4.5,
                    'Medium_Bold': ratio >= 4.5,
                    'Normal': ratio >= 7,
                },
            };
        };

        let isDark = function (color) {

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return colorwcag2;
        });
    } else if (typeof module === 'object' && module.exports) {
        module.exports = colorwcag2;
    } else {
        window.colorwcag2 = colorwcag2;
    }

}).call(this);
