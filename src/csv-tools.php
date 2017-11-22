#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Commands\ConvertCommand;
use Commands\ExtractCommand;
use Commands\ValidateCommand;
use Commands\ConvertTimeFieldsCommand;
use Goodby\CSV;
use Symfony\Component\Console\Application;


$application = new Application();

$application
    ->add(new ExtractCommand())
    ->getApplication()
    ->add(new ConvertCommand())
    ->getApplication()
    ->add(new ValidateCommand())
    ->getApplication()
    ->add(new ConvertTimeFieldsCommand())
    ->getApplication()
    ->setDefaultCommand('extract');

$application->run();