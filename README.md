# csv-tools
PHP CSV Toolkit for commandline

## Install
Clone the repository and install dependencies with composer.

`composer install`

## Commands
Commands can be run by command line with php.
`php ./src/csv-tools.php <command>`

### Validate
Validate a csv file and attempt to resolve issues with structure (number of columns).

`php ./src/csv-tools.php validate <file> <output>`

### Extract
Extract columns from a csv file and produce a second CSV with just those columns.

`php ./src/csv-tools.php extract <file> <output> <columns>`

### Convert
Extract a csv or tsv file and write to an output file.


`php ./src/csv-tools.php convert <input> <output> <from>`

<from> can be either 'csv' or 'tsv'