<?php
// error_reporting(E_ALL);
// ini_set("display_errors", "1");

require __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Inc/database.php";

use App\Commands\Github;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new Github($mysqli));

$application->run();

// Token : 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
