<?php
add_shortcode( 'stray-incidents-table', 'stray_incidents_table' );	
function stray_incidents_table() {
	if (!is_admin()) {
		stray_incidents_table_generator("Όλα τα περιστατικά", array(
			'orderby' => 'title',
			'status' => 'publish',
			'order'   => 'DESC',
			'orderby'=> 'title',
			'posts_per_page' => -1
		), false);
		
		stray_incidents_table_generator("Περιπτώσεις για πρώτο επανέλεγχο", array(
			'orderby' => 'title',
			'status' => 'publish',
			'order'   => 'DESC',
			'orderby'=> 'title',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'date',
					'value' => date('Y-m-d', strtotime("-1 month")),
					'compare' => '<',
					'type' => 'DATE'
				),
				array(
					'key' => 'first_recheck_date',
					'value' => " ",
					'compare' => '='
				),
			)
		));
		
		stray_incidents_table_generator("Περιπτώσεις για δεύτερο επανέλεγχο",  array(
			'orderby' => 'title',
			'status' => 'publish',
			'order'   => 'DESC',
			'orderby'=> 'title',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'first_recheck_date',
					'value' => date('Y-m-d', strtotime("-1 month")),
					'compare' => '<',
					'type' => 'DATE'
				),
				array(
					'key' => 'second_recheck_date',
					'value' => " ",
					'compare' => '='
				),
				array(
					'key' => 'complete_compliance',
					'value' => "0",
					'compare' => '='
				),
			)
		));
		
		stray_incidents_table_generator("Περιπτώσεις ελέγχου με αστυνομία", array(
			'orderby' => 'title',
			'status' => 'publish',
			'order'   => 'DESC',
			'orderby'=> 'title',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'first_recheck_date',
					'value' => date('Y-m-d', strtotime("-1 month")),
					'compare' => '<',
					'type' => 'DATE'
				),
				array(
					'key' => 'second_recheck_date',
					'value' => " ",
					'compare' => '='
				),
				array(
					'key' => 'imposed_fine',
					'value' => "1",
					'compare' => '='
				),
			)
		));
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

function stray_incidents_table_generator( $title, $args, $separator = true ) {

	$query = new WP_Query( $args );

	$posts = $query->posts;
	?>
	<?php if ( $separator ) : ?><hr style="border-width: 10px; margin: 40px 0 40px;"><?php endif; ?>
	<div class="stray-incidents-wrapper">
		<h4><?php echo $title; ?></h4><hr>
		<table class="stray-incidents-table table-table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Ημερομηνία Εντοπισμού</th>
					<th>Περιοχή</th>
					<th>Ονοματεπώνυμο Ιδιοκτήτη</th>
					<th>Κατάσταση</th>
					<th></th>
				</tr>
			</thead>
			<tbody>

			<?php foreach ($posts as $post): ?>
				
				<?php
					$status = get_incident_status($post);
					$date = get_post_meta( $post->ID, 'date', true );
				?>
				
				<tr>
					<td><?php echo $post->post_title; ?></td>
					<td><?php echo $date ? date("d/m/Y", strtotime($date)) : ""; ?></td>
					<td><?php echo get_post_meta( $post->ID, 'area', true ); ?></td>
					<td><?php echo get_post_meta( $post->ID, 'owner_firstname', true ) . " " . get_post_meta( $post->ID, 'owner_lastname', true ); ?></td>
					<td><?php echo $status; ?></td>
					<td><a target="_blank" href="/form?post_id=<?php echo $post->ID; ?>">Λεπτομέρειες</a></td>
				</tr>
			
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php
}