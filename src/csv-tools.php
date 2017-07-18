#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Commands\ConvertCommand;
use Commands\ExtractCommand;
use Goodby\CSV;
use Symfony\Component\Console\Application;


$application = new Application();

$application
    ->add(new ExtractCommand())
    ->getApplication()
    ->add(new ConvertCommand())
    ->getApplication()
    ->setDefaultCommand('extract');

$application->run();