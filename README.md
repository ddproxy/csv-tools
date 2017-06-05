# csv-tools
PHP CSV Toolkit for commandline

## Install
Clone the repository and install dependencies with composer.

`composer install`

## Commands
Commands can be run by command line with php.
`php ./src/csv-tools.php <command>`

### Extract
Extract columns from a csv file and produce a second CSV with just those columns.

`php ./src/csv-tools.php extract <file> <output> <columns>`
