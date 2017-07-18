<?php

namespace Commands;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('convert')
            ->addArgument('input', InputArgument::REQUIRED, 'Input filename to convert')
            ->addArgument('output', InputArgument::REQUIRED, 'Output filename to save as')
            ->addArgument('from',
                InputArgument::REQUIRED,
                'File type (csv, tsv) - will convert to the opposing format')
            ->setDescription('Compare two files');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new LexerConfig();
        switch ($input->getArgument('from')) {
            case 'tsv':
                $config->setDelimiter("\t");
                $outputDelimiter = ",";
                break;
            case 'csv':
                $config->setDelimiter(",");
                $outputDelimiter = "\t";
                break;
            default:
                throw new \Exception("You must define your 'from' argument as either csv or tsv");
        }

        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        $i = 0;
        $headers = [];

        $outputFile = fopen($input->getArgument('output'), 'w');

        $interpreter->addObserver(function (array $row) use (&$i, &$headers, &$outputFile, $outputDelimiter) {
            if ($i === 0) {
                $headers = $row;
                fputcsv($outputFile, $headers, $outputDelimiter);
            } else {
                # map row data
                $rowData = [];
                foreach ($headers as $key => $header) {
                    $rowData[$header] = $row[$key];
                }
                fputcsv($outputFile, $rowData, $outputDelimiter);
            }
            $i++;
        });
        $lexer->parse($input->getArgument('input'), $interpreter);
    }
}