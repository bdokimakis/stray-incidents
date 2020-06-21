<?php
ob_start();

add_shortcode( 'stray-incidents-areas', 'stray_incidents_areas' );
	
function stray_incidents_areas() {
		
	if ( !current_user_can( "edit_others_posts" ) ) {
		wp_exit("Not allowed here.");
	}
	?>
				
	<form id="stray-incidents-areas" autocomplete="off">
		<div class="form-group row">
			<label for="areas" class="col-sm-12 col-form-label">Παρακαλούμε εισάγετε τις περιοχές, χωρισμένες με κόμμα.</label> 
			<div class="col-sm-12">
				<textarea id="areas" name="areas" cols="40" rows="5" class="form-control"><?php echo implode(',', unserialize(get_option('stray_incidents_areas'))); ?></textarea>
			</div>
		</div>
		

		<div class="form-group row">
			<div class="col-sm-12 text-center">
				<button name="submit" type="submit" class="btn btn-primary">Αποθήκευση</button>
			</div>
		</div>
		
		<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('stray-incidents-areas'); ?>"/>
	</form>
<?php
}

add_action( 'wp_ajax_stray_incidents_areas_update', 'stray_incidents_areas_update' );
function stray_incidents_areas_update() {
	check_ajax_referer( 'stray-incidents-areas', 'security' );
	
	if (!is_array(explode(',', $_POST['areas']))) {
		wp_send_json_error( 'Προέκυψε σφάλμα δεδομένων. Παρακαλούμε εισάγετε τις περιοχές, χωρισμένες με κόμμα.' );
	}
	$trimed = array_map('trim', explode(',', $_POST['areas']));
	if (!update_option("stray_incidents_areas", serialize($trimed))) {
		wp_send_json_error( 'Προέκυψε κάποιο σφάλμα. Παρακαλούμε φορτώστε εκ νέου τη σελίδα και προσπαθήστε ξανά.' );
	}
	wp_send_json( array( 'success' => true ) );	
}