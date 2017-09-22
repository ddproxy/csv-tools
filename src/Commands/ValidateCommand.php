<?php

namespace Commands;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('validate')
            ->addArgument('file', InputArgument::REQUIRED, 'File to extract from')
            ->addArgument('output', InputArgument::REQUIRED, 'File to save to')
            ->setDescription('Validate a csv file structure');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new LexerConfig();
        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        $interpreter->unstrict();
        $i = 0;
        $headers = [];

        $outputFile = fopen($input->getArgument('output'), 'w');
        $interpreter->addObserver(function (array $row) use (&$i, &$headers, &$outputFile, $output) {
            if ($i === 0) {
                $headers = $row;
                fputcsv($outputFile, $headers);
            } else {
                if (count($headers) !== count($row)) {
                    $output->writeln(sprintf("Too many columns on line %s: %s", (string)$i, print_r($row, true)));
                    return;
                }
                # map row data
                $rowData = [];
                foreach ($headers as $key => $header) {
                    $rowData[$header] = trim($row[$key]);
                }
                fputcsv($outputFile, $rowData);
            }
            $i++;
        });
        $lexer->parse($input->getArgument('file'), $interpreter);
    }
}