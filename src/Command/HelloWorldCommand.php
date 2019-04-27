<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('app:helloworld')
            ->setDescription('Writing words: Hello world')
            ->setHelp('This is HELP by Dexodus');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $section1 = $output->section();
        $outputStyle = new OutputFormatterStyle('red', 'yellow', array('bold'));
        $output->getFormatter()->setStyle('fire', $outputStyle);

        $section1->writeln('<info>Hello world</info>');
        $section1->writeln('<comment>Hello world</comment>');
        $section1->writeln([
            '<fire>                                                     </fire>',
            '<fire>                      FIRE!!!!!                      </fire>',
            '<fire>                                                     </fire>',
        ]);
        $section1->writeln([
            '<bg=yellow;options=bold>foo</>'
        ]);
        $section1->writeln([
            '<error>                                                     </error>',
            '<error>          This is big fatal error? Nope...           </error>',
            '<error>                                                     </error>',
        ]);
        $output->writeln('<options=bold,underscore>foo</>');

    }
}