<?php

namespace Odiseo\Bundle\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Finder\Finder;

/**
 * Clear and the cache and logs.
 */
class CacheClearCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('odiseo:cache:clear')
            ->setDescription('Clears the cache')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command clears the application cache.
EOF
            )
            ->addOption(
                'also-logs',
                null,
                InputOption::VALUE_OPTIONAL,
                'You need clear logs?',
                true
            )
            ->addOption(
                'all-env',
                null,
                InputOption::VALUE_OPTIONAL,
                'You need clear both env?',
                false
            )

        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheDir = $this->getContainer()->getParameter('kernel.cache_dir');
        $logsDir = $this->getContainer()->getParameter('kernel.logs_dir');

        $output->write("Clearing the cache...");

        $commands = [
            "rm -rf ".$cacheDir,
            "mkdir ".$cacheDir,
            "chmod -R 777 ".$cacheDir
        ];

        if($input->getOption('also-logs'))
        {
            $commands = array_merge($commands, [
                "rm -rf ".$logsDir,
                "mkdir ".$logsDir,
                "chmod -R 777 ".$logsDir
            ]);
        }

        foreach ($commands as $command)
        {
            exec($command, $commandOutput);
        }

        $output->writeln("Done.");
    }
}
