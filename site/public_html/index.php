<?php

declare(strict_types=1);

session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once $_SERVER["DOCUMENT_ROOT"] . '/../app/inc/main.php';

if (filter_input(INPUT_GET, 'logout') === 'yes') {
	unset($_SESSION[constant('cAppKey')]);
	basic_redir($GLOBALS['home_url']);
}

$params = [
	'sr' => filter_input(INPUT_GET, 'sr', FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 1]]) ?? 0,
	'format' => '.html',
	'post' => $_POST ?? null,
	'get' => $_GET ?? null,
];

$btn_save = filter_input(INPUT_POST, 'btn_save') !== null;
$btn_remove = filter_input(INPUT_POST, 'btn_remove') !== null;

$strCanal = '';
$dispatcher = new dispatcher(true);
$dispatcher->add_route('GET', '/(index(\.json|\.xml|\.html)).*?', 'function:basic_redir', null, $GLOBALS['home_url']);
$dispatcher->add_route('GET', '/?', 'setup_controller:display', null, $params);

if (!$dispatcher->exec()) {
	basic_redir($GLOBALS['home_url']);
}
