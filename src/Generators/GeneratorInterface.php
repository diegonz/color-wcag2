<?php

namespace Diegonz\ColorWcag2\Generators;

use Illuminate\Filesystem\Filesystem;
use Diegonz\ColorWcag2\Compilers\CompilerInterface as Compiler;

/**
 * Interface GeneratorInterface.
 */
interface GeneratorInterface
{
    /**
     * Create a new template generator instance.
     *
     * @param $compiler   \Diegonz\ColorWcag2\Compilers\CompilerInterface
     * @param $filesystem \Illuminate\Filesystem\Filesystem
     */
    public function __construct(Compiler $compiler, Filesystem $filesystem);

    /**
     * Compile the template.
     *
     * @param $templatePath
     * @param $templateData
     * @param $filePath
     *
     * @return string
     */
    public function compile($templatePath, array $templateData, $filePath): string;
}
