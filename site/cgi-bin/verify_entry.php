<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__) . "/../public_html/";
$_SERVER["HTTP_HOST"] = "systemlocal.local";
putenv('SERVER_PORT=80');
putenv('SERVER_PROTOCOL=http');

// $_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__) . "/../html/";
// putenv('SERVER_PORT=443');
// putenv('SERVER_PROTOCOL=https');

putenv('SERVER_NAME=' . $_SERVER["HTTP_HOST"]);
putenv('SCRIPT_NAME=index.php');
set_include_path($_SERVER["DOCUMENT_ROOT"]  . PATH_SEPARATOR . get_include_path());
require_once($_SERVER["DOCUMENT_ROOT"] . "../app/inc/main.php");

$order = new setup_controller();
$order->display();