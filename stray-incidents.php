<?php
/**
Plugin Name: Stray Incidents
Version: 0.01
Author: Byron Dokimakis
Author URI: https://b.dokimakis.gr
Text Domain: stray-incidents
*/

add_action('wp_enqueue_scripts', 'stray_incidents_enqueue_scripts');
function stray_incidents_enqueue_scripts() {
	wp_enqueue_style( 'stray-incidents', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
	
	if (is_page('form')) {
		wp_enqueue_style( 'stray-incidents-form', plugin_dir_url( __FILE__ ) . 'assets/css/form.css' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
		wp_enqueue_script( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@9' );
		wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD45Wfs3NIsezA_SEE8k-hH9j13enJRLfY' );
		wp_enqueue_script( 'stray-incidents-form', plugin_dir_url( __FILE__ ) . 'assets/js/form.js', array( 'jquery' ), '1.0.0', true );
	}
	
	if (is_front_page()) {
		wp_enqueue_style( 'stray-incidents-table', plugin_dir_url( __FILE__ ) . 'assets/css/table.css' );
		wp_enqueue_script( 'datatables', 'https://nightly.datatables.net/js/jquery.dataTables.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'datatables', 'https://nightly.datatables.net/css/jquery.dataTables.css' );
		wp_enqueue_script( 'stray-incidents-table', plugin_dir_url( __FILE__ ) . 'assets/js/table.js', array( 'jquery' ), '1.0.0', true );
	}
}

include 'stray-incident-form.php';
include 'stray-incidents-table.php';