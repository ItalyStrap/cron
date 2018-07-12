<?php
/**
 * Cron_Factory
 *
 * @version 0.0.1-alpha
 *
 * @package ItalyStrap\Cron
 */

namespace ItalyStrap\Cron;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ItalyStrap\Config\Config;

/**
 * Class Cron_Factory
 */
class Cron_Factory {

	/**
	 * Create Cron object
	 */
	public static function create( Config $config ) {

		static $cron = null;

		if ( null === $cron ) {
			$cron = new Cron( $config );
		}

		return $cron;
	}
}

