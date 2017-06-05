#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Commands\ExtractCommand;
use Goodby\CSV;
use Symfony\Component\Console\Application;


$application = new Application();

$application
    ->add(new ExtractCommand())
    ->getApplication()
    ->setDefaultCommand('extract', true);

$application->run();