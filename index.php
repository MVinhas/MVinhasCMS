<?php
require_once 'config/conf.php';


$head = new \controllers\HeadController;
$head->index();

$header = new \controllers\HeaderController;
$header->index();

config\Dispatcher::dispatch();

$footer = new \controllers\FooterController;
$footer->index();
