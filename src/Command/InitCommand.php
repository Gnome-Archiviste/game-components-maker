<?php
declare(strict_types=1);

namespace App\Command;

use App\Services\ProjectInitializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 */
class InitCommand extends Command
{
    use LockableTrait;

    /**
     * @var ProjectInitializer
     */
    private $initializer;

    /**
     * InitCommand constructor.
     *
     * @param ProjectInitializer $initializer
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(ProjectInitializer $initializer)
    {
        $this->initializer = $initializer;
        parent::__construct();
    }

    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this
            ->setName('init')
            ->setDescription('Initialise a new project. Will create directory and files. 
                Don\'t use spaces and special characters')
            ->addArgument('projectName', InputArgument::REQUIRED, 'Year to run (default = This year + 1)');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @noinspection PhpMissingParentCallCommonInspection
     * @return int|null|void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \LogicException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('Command already running');

            return;
        }

        $projectName = $input->getArgument('projectName');
        $projectDir = 'projects/';

        $output->writeln("Beginning generating project files for {$projectName}.");

        $this->initializer->setOutput($output);
        $this->initializer->execute($projectDir, $projectName);

        $output->writeln("Project {$projectName} generated.");
    }

}
