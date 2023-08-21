<?php
// error_reporting(E_ALL);
// ini_set("display_errors", "1");

require __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Inc/database.php";

use App\Commands\User;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new User($mysqli));

$application->run();
