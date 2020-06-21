<?php
/**
Plugin Name: Stray Incidents
Version: 0.01
Author: Byron Dokimakis
Author URI: https://b.dokimakis.gr
Text Domain: stray-incidents
*/

include 'stray-incidents-form.php';
include 'stray-incidents-table.php';
include 'stray-incidents-areas.php';

add_action('wp_enqueue_scripts', 'stray_incidents_enqueue_scripts');
function stray_incidents_enqueue_scripts() {
	wp_enqueue_style( 'stray-incidents', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
	
	if (is_page('forma-peristatikou')) {
		wp_enqueue_style( 'stray-incidents-form', plugin_dir_url( __FILE__ ) . 'assets/css/form.css', array(), time() );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'assets/css/jquery-ui.css' );
		wp_enqueue_script( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@9' );
		wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD45Wfs3NIsezA_SEE8k-hH9j13enJRLfY' );
		
		wp_enqueue_script( 'stray-incidents-form-fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery'), '1.0.0' );
		wp_enqueue_style( 'stray-incidents-form-fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css');
		
		wp_enqueue_script( 'stray-incidents-form-dropzone', plugin_dir_url( __FILE__ ) . 'assets/js/dropzone.js', array( 'jquery' ), '1.0.0' );
		wp_enqueue_style( 'stray-incidents-form-dropzone', plugin_dir_url( __FILE__ ) . 'assets/css/dropzone.css' );
		
		wp_enqueue_script( 'stray-incidents-form', plugin_dir_url( __FILE__ ) . 'assets/js/form.js', array( 'jquery' ), time(), true );
		$params = array(
		  'ajaxurl' => admin_url('admin-ajax.php'),
		  'ajax_nonce' => wp_create_nonce('stray-incidents-form'),
		);
		wp_localize_script( 'stray-incidents-form', 'ajax_object', $params );
	}
	
	if (is_page('perioxes')) {
		wp_enqueue_script( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@9' );
		wp_enqueue_script( 'stray-incidents-areas', plugin_dir_url( __FILE__ ) . 'assets/js/areas.js', array( 'jquery' ), time(), true );
		
		$params = array(
		  'ajaxurl' => admin_url('admin-ajax.php'),
		  'ajax_nonce' => wp_create_nonce('stray-incidents-areas'),
		);
		wp_localize_script( 'stray-incidents-areas', 'ajax_object', $params );
	}
	
	if (is_front_page()) {
		wp_enqueue_style( 'stray-incidents-table', plugin_dir_url( __FILE__ ) . 'assets/css/table.css' );
		wp_enqueue_script( 'datatables', 'https://nightly.datatables.net/js/jquery.dataTables.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'datatables', 'https://nightly.datatables.net/css/jquery.dataTables.css' );
		wp_enqueue_script( 'stray-incidents-table', plugin_dir_url( __FILE__ ) . 'assets/js/table.js', array( 'jquery' ), '1.0.0', true );
	}
}