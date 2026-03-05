<?php

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

Commands::run($argv);