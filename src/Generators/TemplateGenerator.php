<?php

namespace Diegonz\ColorWcag2\Generators;

use Illuminate\Filesystem\Filesystem;
use Diegonz\ColorWcag2\Compilers\CompilerInterface as Compiler;

/**
 * Class TemplateGenerator.
 */
class TemplateGenerator implements GeneratorInterface
{
    /**
     * The compiler instance.
     *
     * @var \Diegonz\ColorWcag2\Compilers\CompilerInterface
     */
    protected $compiler;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new template generator instance.
     *
     * @param $compiler   \Diegonz\ColorWcag2\Compilers\CompilerInterface
     * @param $filesystem \Illuminate\Filesystem\Filesystem
     */
    public function __construct(Compiler $compiler, Filesystem $filesystem)
    {
        $this->compiler = $compiler;

        $this->filesystem = $filesystem;
    }

    /**
     * Compile the template.
     *
     * @param $templatePath
     * @param $templateData
     * @param $filePath
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function compile($templatePath, array $templateData, $filePath): string
    {
        $template = $this->filesystem->get($templatePath);

        $compiled = $this->compiler->compile($template, $templateData);

        $this->makeDirectory(dirname($filePath));

        $this->filesystem->put($filePath, $compiled);

        return $filePath;
    }

    public function makeDirectory($directory): void
    {
        if (! $this->filesystem->isDirectory($directory)) {
            // TODO: Change directory permissions to 755 ¿?
            $this->filesystem->makeDirectory($directory, 0777, true);
        }
    }
}
