<?php

namespace Commands;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtractCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('extract')
            ->addArgument('file', InputArgument::REQUIRED, 'File to extract from')
            ->addArgument('output', InputArgument::REQUIRED, 'File to save to')
            ->addArgument('columns', InputArgument::REQUIRED, 'Comma Separated Values of what columns to extract')
            ->setDescription('Extract columns from csv file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new LexerConfig();
        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        $i = 0;
        $headers = [];

        $outputFile = fopen($input->getArgument('output'), 'w');
        $columns = explode(',', $input->getArgument('columns'));
        fputcsv($outputFile, $columns);
        $interpreter->addObserver(function (array $row) use (&$i, &$headers, &$outputFile, $columns) {
            if ($i === 0) {
                $headers = $row;
            } else {
                # map row data
                $rowData = [];
                $outputRow = [];
                foreach ($headers as $key => $header) {
                    $rowData[$header] = $row[$key];
                }
                foreach($columns as $column) {
                    $outputRow[$column] = $rowData[$column];
                }
                fputcsv($outputFile, $outputRow);
            }
            $i++;
        });
        $lexer->parse($input->getArgument('file'), $interpreter);
    }
}