(function () {

    let colorWcag2 = (function () {

        let colorHandler = {

            level: '$LEVEL$',
            type: '$TYPE$',

            parseHexString: function (hexColorString) {
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
            },

            calculateRelativeLuminance: function (color) {
                color = this.parseHexString(color);

                let red = parseInt(color.substring(0, 2), 16);
                let green = parseInt(color.substring(2, 4), 16);
                let blue = parseInt(color.substring(4, 6), 16);

                let transformColor = function (color) {
                    return color <= 0.03928
                        ? color / 12.92
                        : ((color + 0.055) / 1.055) ** 2.4;
                };

                return 0.2126 * transformColor(red) + 0.7152 * transformColor(green) + 0.0722 * transformColor(blue);
            },

            calculateContrastRatio: function (color1, color2) {
                let luminanceColor1 = this.calculateRelativeLuminance(color1);
                let luminanceColor2 = this.calculateRelativeLuminance(color2);

                return luminanceColor1 > luminanceColor2
                    ? ((luminanceColor1 + 0.05) / (luminanceColor2 + 0.05))
                    : ((luminanceColor2 + 0.05) / (luminanceColor1 + 0.05));
            },

            evaluateColorContrast: function (color1, color2) {
                let ratio = this.calculateContrastRatio(color1, color2);
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
            },

            contrast: function (color1, color2) {
                let results = this.evaluateColorContrast(color1, color2);

                return results[this.level][this.type];
            },

            isDark: function (color) {
                let results = this.evaluateColorContrast(color, '#ffffff');

                return results[this.level][this.type];
            },

            isLight: function (color) {
                let results = this.evaluateColorContrast(color, '#000000');

                return results[this.level][this.type];
            },

            setLevel: function (targetLevel) {
                this.level = targetLevel;
            },

            setType: function (targetType) {
                this.type = targetType;
            },
        };

        return {
            parseHexString: function (hexColorString) {
                return colorHandler.parseHexString(hexColorString);
            },
            calculateRelativeLuminance: function (color) {
                return colorHandler.calculateRelativeLuminance(color);
            },
            calculateContrastRatio: function (color1, color2) {
                return colorHandler.calculateContrastRatio(color1, color2);
            },
            evaluateColorContrast: function (color1, color2) {
                return colorHandler.evaluateColorContrast(color1, color2);
            },
            contrast: function (color1 ,color2) {
                return colorHandler.contrast(color1, color2);
            },
            isDark: function (color) {
                return colorHandler.isDark(color);
            },
            isLight: function (color) {
                return colorHandler.isLight(color);
            },
            setLevel: function (level) {
                colorHandler.setLevel(level);
            },
            setType: function (type) {
                colorHandler.setType(type);
            }
        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return colorWcag2;
        });
    } else if (typeof module === 'object' && module.exports) {
        module.exports = colorWcag2;
    } else {
        window.$NAMESPACE$ = colorWcag2;
    }

}).call(this);
