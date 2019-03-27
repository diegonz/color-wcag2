<?php

/**
 * Package configuration.
 */
return [
    /**
     * WCAG2 Conformance level
     * https://www.w3.org/TR/UNDERSTANDING-WCAG20/conformance.html
     *
     * Level A:     The minimum level of conformance
     * Level AA:    For Level AA conformance, must satisfy all the Level A
     *              and Level AA Success Criteria
     * Level AAA:   For Level AAA conformance, must satisfy all the Level A,
     *              Level AA and Level AAA Success Criteria
     */
    'level' => 'AA',

    /**
     * Contrast ratio conformance
     *
     * AA Large        - ratio >= 3;
     * AA Medium Bold  - ratio >= 3;
     * AA Normal       - ratio >= 4.5;
     * AAA Large       - ratio >= 4.5;
     * AAA Medium Bold - ratio >= 4.5;
     * AAA Normal      - ratio >= 7;
     */
    'type'  => 'Normal',

    /*
     * The destination path for the javascript file.
     */
    'path' => 'public/js',

    /*
     * The destination javascript file name.
     */
    'filename' => 'color-wcag2',

    /*
     * The namespace for the helper functions. By default this will bind them to
     * `window.colorWcag2`.
     */
    'namespace' => 'colorWcag2',

    /*
     * The path to the template `color-wcag2.js` file. This is the file that contains
     * the ported helper Laravel url/route functions and the route data to go
     * with them.
     */
    'template' => 'vendor/diegonz/color-wcag2/resources/js/color-wcag2.js',
];
