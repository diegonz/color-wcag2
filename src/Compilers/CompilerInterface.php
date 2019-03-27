<?php

namespace Diegonz\ColorWcag2\Compilers;

/**
 * Interface CompilerInterface
 */
interface CompilerInterface
{
    /**
     * Compile a template with given data.
     *
     * @param $template
     * @param $data
     *
     * @return string
     */
    public function compile($template, $data): string;
}
