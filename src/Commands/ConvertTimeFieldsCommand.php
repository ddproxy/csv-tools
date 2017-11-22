<?php

namespace Commands;

use DateTime;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertTimeFieldsCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('convert-time')
            ->addArgument('input', InputArgument::REQUIRED, 'Input filename to convert')
            ->addArgument('output', InputArgument::REQUIRED, 'Output filename to save as')
            ->addArgument('columns', InputArgument::REQUIRED, 'Comma Separated Values of what columns to convert')
            ->addArgument('format', InputArgument::REQUIRED, 'Comma Separated Values of what columns to convert')
            ->setDescription('Convert time fields to ISO-8601');
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
        $format = $input->getArgument('format');

        $interpreter->addObserver(function (array $row) use (&$i, &$headers, &$outputFile, $columns, $format) {
            if ($i === 0) {
                $headers = $row;
                fputcsv($outputFile, $headers);
            } else {
                # map row data
                $rowData = [];
                $outputRow = [];
                foreach ($headers as $key => $header) {
                    $rowData[$header] = $row[$key];
                }
                foreach ($rowData as $key => $row) {
                    if (in_array($key, $columns)) {
                        $date = DateTime::createFromFormat($format, $row);
                        if ($date) {
                            $row = $date->format('Y-m-d H:i:s');
                        }
                    }
                    $outputRow[$key] = $row;
                }
                fputcsv($outputFile, $outputRow);
            }
            $i++;
        });
        $lexer->parse($input->getArgument('input'), $interpreter);
    }
}