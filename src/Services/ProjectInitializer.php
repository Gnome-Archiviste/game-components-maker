<?php
declare(strict_types=1);

namespace App\Services;


use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ProjectInitializer
 */
class ProjectInitializer
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * ProjectInitializer constructor.
     *
     * @param Filesystem        $fileSystem
     * @param \Twig_Environment $twig
     */
    public function __construct(Filesystem $fileSystem, \Twig_Environment $twig)
    {
        $this->fileSystem = $fileSystem;
        $this->twig = $twig;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param string $projectDir
     * @param string $projectName
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function execute(string $projectDir, string $projectName): void
    {
        if ($this->fileSystem->exists("./projects/{$projectName}")) {
            $this->output->writeln("Project {$projectName} already exist.");
            return;
        }

        $this->generateDirectory($projectDir, $projectName);

        $this->generateConfigFiles($projectDir, $projectName);
    }

    /**
     * @param string $projectDir
     * @param string $projectName
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    private function generateDirectory(string $projectDir, string $projectName): void
    {
        foreach (['build', 'fonts', 'icons', 'images', 'output'] as $dir) {
            $this->fileSystem->mkdir("{$projectDir}{$projectName}/{$dir}");
            $this->output->writeln(
                "Creating projects/{$projectName}/{$dir}",
                OutputInterface::OUTPUT_NORMAL | OutputInterface::VERBOSITY_VERBOSE
            );
        }
    }

    /**
     * @param string $projectDir
     * @param string $projectName
     *
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    private function generateConfigFiles(string $projectDir, string $projectName): void
    {
        $data = ['projectName' => $projectName];

        $this->generateFile(
            "{$projectDir}{$projectName}/config.yaml",
            'config.yaml.twig',
            $data,
            "Creating projects/{$projectName}/config.yaml"
        );

        $this->generateFile(
            "{$projectDir}{$projectName}/{$projectName}.yaml",
            'base.yaml.twig',
            $data,
            "Creating projects/{$projectName}/{$projectName}.yaml"
        );
    }

    /**
     * @param $fileName
     * @param $template
     * @param $data
     * @param $msg
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    private function generateFile($fileName, $template, $data, $msg): void
    {
        $this->fileSystem->appendToFile(
            $fileName,
            $this->twig->load($template)->render($data)
        );

        $this->output->writeln(
            $msg,
            OutputInterface::OUTPUT_NORMAL | OutputInterface::VERBOSITY_VERBOSE
        );
    }


}
