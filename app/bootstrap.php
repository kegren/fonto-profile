<?php
/**
 * Part of Fonto Framework
 *
 * Creates a new application
 */


/**
 * Includes files
 */
include APPPATH . 'helpers' . EXT;
require SYSCOREAPPPATH . 'App' . EXT;

/**
 * Namespaces alias
 */
use Fonto\Core\Application\App as App;


/**
 * Runs application
 */
$app = new App();
$app->setAppName('Demo')
	->setup()
	->run();