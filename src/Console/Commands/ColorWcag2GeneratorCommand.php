<?php

namespace Diegonz\ColorWcag2\Console\Commands;

use Diegonz\ColorWcag2\Generators\GeneratorInterface as Generator;

use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;

/**
 * Class ColorWcag2GeneratorCommand
 */
class ColorWcag2GeneratorCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'color-wcag2:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Javascript WCAG2 color contrast handler';

    /**
     * Config
     *
     * @var Config
     */
    protected $config;

    /**
     * The generator instance.
     *
     * @var \Diegonz\ColorWcag2\Generators\GeneratorInterface
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @param Config    $config
     * @param Generator $generator
     */
    public function __construct(Config $config, Generator $generator)
    {
        $this->config = $config;
        $this->generator = $generator;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $filePath = $this->generator->compile(
                $this->getTemplatePath(),
                $this->getTemplateData(),
                $this->getFileGenerationPath()
            );

            $this->info("Created: {$filePath}");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get path to the template file.
     *
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return $this->config->get('color-wcag2.template');
    }

    /**
     * Get the data for the template.
     *
     * @return array
     */
    protected function getTemplateData(): array
    {
        $level = $this->getOptionOrConfig('level');
        $type = $this->getOptionOrConfig('type');
        $namespace = $this->getOptionOrConfig('namespace');

        return compact('level', 'type', 'namespace');
    }


    /**
     * Get the path where the file will be generated.
     *
     * @return string
     */
    protected function getFileGenerationPath(): string
    {
        $path = $this->getOptionOrConfig('path');
        $filename = $this->getOptionOrConfig('filename');

        return $path.'/'.$filename.'.js';
    }

    /**
     * Get an option value either from console input, or the config files.
     *
     * @param $key
     *
     * @return array|mixed|string
     */
    protected function getOptionOrConfig($key)
    {
        if ($option = $this->option($key)) {
            return $option;
        }

        return $this->config->get("color-wcag2.{$key}");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            [
                'level',
                'l',
                InputOption::VALUE_OPTIONAL,
                sprintf('WCAG2 level (default: "%s")',
                    $this->config->get('color-wcag2.level')),
            ],
            [
                'type',
                't',
                InputOption::VALUE_OPTIONAL,
                sprintf('Text type (default: "%s")',
                    $this->config->get('color-wcag2.type')),
            ],
            [
                'path',
                'p',
                InputOption::VALUE_OPTIONAL,
                sprintf('Path to the javascript assets directory (default: "%s")',
                    $this->config->get('color-wcag2.path')),
            ],
            [
                'filename',
                'f',
                InputOption::VALUE_OPTIONAL,
                sprintf('Filename of the javascript file (default: "%s")', $this->config->get('color-wcag2.filename')),
            ],
            [
                'namespace',
                null,
                InputOption::VALUE_OPTIONAL,
                sprintf('Javascript namespace for the functions (think _.js) (default: "%s")',
                    $this->config->get('color-wcag2.namespace')),
            ],
        ];
    }
}
