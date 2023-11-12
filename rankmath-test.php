<?php
/**
 * RankMathTest
 *
 * @copyright Copyright © 2023 Taha EL MAHDAOUI. All rights reserved.
 * @author    taha.elmahdaoui.te@gmail.com
 */

/*
Plugin Name: Rankmath Test
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: mac
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
Text Domain: rankmath-test
*/

class RankMathTest {

	const TABLE = 'rankmath';

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ] );
		$this->register_scripts();
		add_action( 'rest_api_init', [ $this, 'add_api_endpoint' ] );
	}

	public function register_scripts() {
		wp_register_script( 'rankmath-test-core', plugin_dir_url( __FILE__ ) . '/build/index.js', [ 'wp-element' ], '1.0.0' );
	}

	public function add_dashboard_widget() {
		wp_enqueue_script( 'rankmath-test-core' );

		wp_add_dashboard_widget( 'rankmath_test_dashboard_id', 'Rank Math', [ $this, 'dashboard_widget' ] );
	}

	public function dashboard_widget( $post, $args ) {
		echo <<<EOF
<div id="rankmath-test-widget"></div>
EOF;
	}

	public function add_api_endpoint() {
		register_rest_route( 'rankmath/v1', 'data', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_data' ],
		] );
	}

	public function get_data( $args ) {
		global $wpdb, $table_prefix;
		$interval    = $args['interval'] ?? '7-days';
		$interval    = htmlspecialchars( $interval );
		$currentDate = new DateTime();
		$date        = new DateTime();

		$sql = "SELECT name, COUNT(*) as val FROM {$table_prefix}" . self::TABLE;
		switch ( $interval ) {
			case '7-days':
				$date = $date->sub( new DateInterval( "P7D" ) );
				break;
			case '15-days':
				$date = $date->sub( new DateInterval( "P15D" ) );
				break;
			case '30-days':
				$date = $date->sub( new DateInterval( "P30D" ) );
				break;
		}

		$sql .= " WHERE date BETWEEN '{$date->format('Y-m-d')}' AND '{$currentDate->format('Y-m-d')}' GROUP BY name, DATE_FORMAT(date, '%Y-%m-%d')";

		$results = $wpdb->get_results( $sql );

		return $results;
	}
}

new RankMathTest();
