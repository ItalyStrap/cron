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

use ItalyStrap\Config\Config;

/**
 * Class Cron
 * https://github.com/WPBP/CronPlus/blob/master/cronplus.php
 * https://github.com/devgeniem/wp-cron-runner/blob/master/plugin.php
 * https://github.com/wpsmith/Cron/blob/master/src/WP_Cron.php
 */
class Cron {

	private $config;
	private $plugin;
	private $cron;

	/**
	 * Constructor
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
		// $this->plugin = $this->config['plugin'];
		// $this->cron = $this->plugin['cron'];
	}

	/**
	 * register
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function register( $activate = true ) {

		$filter_activate = sprintf(
			'activate_%s',
			$this->plugin['plugin_basename']
		);

		// deactivate_local-strategy-store-locator/index.php
		// activate_local-strategy-store-locator/index.php
		if ( current_filter() === $filter_activate || $activate ) {
			$this->schedule_event();
		} else {
			$this->unschedule_event();
		}

	}

	/**
	 * Acrtivate
	 */
	public function schedule_event() {

		// debug( 'Activated' );
		if ( wp_next_scheduled( $this->cron['hook'] ) ) {
			return;
		}

		wp_schedule_event(
			time(),
			$this->cron['recurrence_key'],
			$this->cron['hook']
		);

		// debug( 'Activated' );
		// if ( ! wp_next_scheduled( $this->cron['hook'] ) ) {
		// 	wp_schedule_event(
		// 		time(),
		// 		$this->cron['recurrence_key'],
		// 		$this->cron['hook']
		// 	);
		// }
	}

	/**
	 * Deactivate
	 */
	public function unschedule_event() {

		// debug( 'Deactivated' );
		$timestamp = wp_next_scheduled( $this->cron['hook'] );
		wp_unschedule_event( $timestamp, $this->cron['hook'] );
	}

	/**
	 * [schedule description]
	 *
	 * @param  array  $schedules [description]
	 *
	 * @return [type]            [description]
	 */
	public function schedules( array $schedules ) {

		$schedules[ $this->cron['recurrence_key'] ] = $this->cron['recurrence_value'];

		return $schedules;
	}
}
