<?php
session_start();

require_once 'config/conf.php';

$site = new \controllers\SiteController;
$site->index();

include 'debug.php';