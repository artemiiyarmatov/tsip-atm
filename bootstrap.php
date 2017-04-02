<?php

namespace core;

// Set all site encoding to utf-8
mb_internal_encoding("UTF-8");
header("Content-type: text/html; charset=utf-8");

// Include defines
$sep = DIRECTORY_SEPARATOR;
include_once(dirname(__FILE__) . "{$sep}config{$sep}define.php");

// Load core classes
include_once(BASE_PATH . "core{$sep}Logger.php");
include_once(BASE_PATH . "core{$sep}PDOConnector.php");
include_once(BASE_PATH . "core{$sep}Site.php");
include_once(BASE_PATH . "core{$sep}Identity.php");

/**
 * Global variables in all site system:
 * - $db       - PDO database connector
 * - $site     - Site parts
 * - $identity - Identity specimen
 */
$PDOConnector = new PDOConnector();
$db = $PDOConnector->Connect();

$site = new Site();

session_start();
$identity = null;
if (isset($_SESSION["Identity"])) {
	$identity = $_SESSION["Identity"];
    $identity->db = $db;
}
