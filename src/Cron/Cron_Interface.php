<?php
/**
 * Cron
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

/**
 * Class Cron
 */
interface Cron {

	/**
	 * register
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function register( $activate = true );

	/**
	 * Acrtivate
	 */
	public function schedule_event();

	/**
	 * Deactivate
	 */
	public function unschedule_event();

	/**
	 * [schedule description]
	 *
	 * @param  array  $schedules [description]
	 *
	 * @return [type]            [description]
	 */
	public function schedules( array $schedules );
}
