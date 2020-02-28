<?php
	add_shortcode( 'stray-incidents-table', 'stray_incidents_table' );	
	function stray_incidents_table() {
		if (!is_admin()) {
			include 'stray-incidents-table-all.php';
			include 'stray-incidents-table-for-first-recheck.php';
			include 'stray-incidents-table-for-second-recheck.php';
			include 'stray-incidents-table-for-police.php';
		}
	}
	
	function get_incident_status( $post ) {
		$suffix = "";
		$status = "";
		
		if ( get_post_meta ( $post->ID, 'second_recheck_date', true ) ) {
			$suffix = "_3";
		}
		else if ( get_post_meta ( $post->ID, 'first_recheck_date', true ) ) {
			$suffix = "_2";
		}
				
		if ( get_post_meta( $post->ID, 'imposed_fine', true ) ) {
			$status = '<span class="status imposed-fine">Βεβαίωση προστίμου</span>';
		}
		else if (
			get_post_meta( $post->ID, 'appropriate_housing' . $suffix, true ) == "Ναι" &&
			get_post_meta( $post->ID, 'appropriate_water_food' . $suffix, true ) == "Ναι" &&
			get_post_meta( $post->ID, 'short_leash_or_on_permanent_leash' . $suffix, true ) == "Όχι" &&
			get_post_meta( $post->ID, 'vaccinated_in_the_past_year' . $suffix, true ) == "Ναι" &&
			get_post_meta( $post->ID, 'chipped' . $suffix, true ) == "Ναι"
			) {
				$status = '<span class="status good">Καλή</span>';
			}
		else if (
			get_post_meta( $post->ID, 'appropriate_housing' . $suffix, true ) == "Ναι" &&
			get_post_meta( $post->ID, 'appropriate_water_food' . $suffix, true ) == "Ναι" &&
			get_post_meta( $post->ID, 'short_leash_or_on_permanent_leash' . $suffix, true ) == "Όχι"
			) {
				$status = '<span class="status medium">Μέτρια</span>';
			}
		else if (
			get_post_meta( $post->ID, 'short_leash_or_on_permanent_leash' . $suffix, true ) == "Ναι" ||
				(
					get_post_meta( $post->ID, 'appropriate_housing' . $suffix, true ) == "Όχι" ||
					get_post_meta( $post->ID, 'appropriate_water_food' . $suffix, true ) == "Όχι" 
				)			
			) {
				$status = '<span class="status bad">Κακή</span>';
			}
		return $status;
	}