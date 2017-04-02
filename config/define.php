<?php
$sep = DIRECTORY_SEPARATOR;

/**
 * This file sets directives such as base path to site.
 */
$site_path = dirname(__FILE__, 2);	// Local site path

// Define alias for directory separator
define("SEP", DIRECTORY_SEPARATOR);
// Define base site path
define("BASE_PATH", $site_path . $sep);
define("VIEW_PATH", $site_path . "{$sep}view{$sep}");
define("MODEL_PATH", $site_path . "{$sep}model{$sep}");
