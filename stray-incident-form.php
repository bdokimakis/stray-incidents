<?php
ob_start();

add_shortcode( 'stray-incident-form', 'stray_incident_form' );
	
function stray_incident_form() {
		
	$post_id = '';
	
	if ( isset( $_GET['post_id'] ) ) {
		if ( !get_post_status( $_GET['post_id'] ) || get_post_status( $_GET['post_id'] ) != "publish" ) {
			header("Location:/form");
			wp_exit();
		}
		else {
			$post_id = $_GET['post_id'];
			$temp_meta = get_post_meta( $post_id );
			foreach ( $temp_meta as $key => $value ) {
				$meta[$key] = $value[0];
			}
		}
	}
		
	$post_title = get_the_title($post_id);
	
	$title = $post_id ? 'Λεπτομέρειες περιστατικού: ' . $post_title : "Νέο περιστατικό";
		
	$disabled = ( $post_id == '' || current_user_can( "edit_post", $post_id ) ? "" : "disabled" );
	?>
	
	<h4 class="text-center mb-4"><?php echo $title; ?></h4><hr>
	
	<?php if ( !$post_id && !current_user_can("publish_posts")) : ?>
		<div class="alert alert-warning">Παρακαλούμε <strong><a href="/wp-login.php">συνδεθείτε</a></strong> για να μπορέσετε να προσθέσετε καταχώρηση.</div>
	<?php else: ?>
		<?php if ( !is_user_logged_in() ): ?>
			<div class="alert alert-warning">Παρακαλούμε <strong><a href="/wp-login.php">συνδεθείτε</a></strong> για να μπορέσετε να επεργαστείτε την καταχώρηση.</div>
		<?php elseif ( $disabled ) : ?>
			<div class="alert alert-danger">Δεν έχετε δικαίωμα να επεξεργαστείτε τη συγκεκριμένη καταχώρηση.</div>
		<?php endif; ?>
		
		<form id="stray-incident-form" autocomplete="off">
			<div class="form-group row">
				<label class="col-sm-4 col-form-label" for="date">Κωδικός περιστατικού</label> 
				<div class="col-sm-8">
					<input $disabled id="post_title" name="post_title" type="text" value="<?php echo $post_title; ?>" class="form-control" placeholder="Συμπληρώστε μόνο αν θέλετε να θέσετε συγκεκριμένο κωδικό περιστατικού">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 col-form-label" for="date">Ημερομηνία αρχικού εντοπισμού / ελέγχου</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="date" name="date" type="text" value="<?php echo ( $meta['date'] ? date("d/m/Y", strtotime($meta['date'])) : "" ) ?>" class="form-control datepicker">
				</div>
			</div>
			
			<div class="form-group row">
				<label for="area" class="col-sm-4 col-form-label required">Περιοχή</label> 
				<div class="col-sm-8">
					<select <?php echo $disabled; ?> id="area" name="area" class="custom-select" required>
						<option></option>
						<option value="perioxi1">Περιοχή 1</option>
						<option value="perioxi2">Περιοχή 2</option>
						<option value="perioxi3">Περιοχή 3</option>
					  </select> 
				</div>
			</div>
			
			<div class="form-group row">
				<label for="lat" class="col-sm-4 col-form-label required">Γεωγραφικό πλάτος (lat)</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="lat" name="lat" type="text" value="<?php echo $meta['lat']; ?>" class="form-control" required>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="lng" class="col-sm-4 col-form-label required">Γεωγραφικό μήκος (lng)</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="lng" name="lng" type="text" value="<?php echo $meta['lng']; ?>" class="form-control" required>
				</div>
			</div>
			
			<?php if ( $meta['lat'] && $meta['lng'] ) : ?>
				<div class="row">
					<div class="col-sm-8 offset-4 mb-3 text-center">
						<button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#myModal" data-lat='<?php echo $meta['lat']; ?>' data-lng='<?php echo $meta['lng']; ?>'>Χάρτης</button>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="form-group row">
				<label class="col-sm-4">Εντοπίστηκε ιδιοκτήτης;</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-show="#owner_wrapper" name="owner_found" id="owner_found_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['owner_found'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="owner_found_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-hide="#owner_wrapper" name="owner_found" id="owner_found_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['owner_found'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="owner_found_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			</div>
			
			<div class="form-group row hidden" id="owner_wrapper">
				<label for="owner_lastname" class="col-sm-4 col-form-label">Επώνυμο</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_lastname" name="owner_lastname" type="text" value="<?php echo $meta['owner_lastname']; ?>" class="form-control">
				</div>

				<label for="owner_firstname" class="col-sm-4 col-form-label">Όνομα</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_firstname" name="owner_firstname" type="text" value="<?php echo $meta['owner_firstname']; ?>" class="form-control">
				</div>

				<label for="owner_area" class="col-sm-4 col-form-label">Περιοχή διαμονής</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_area" name="owner_area" type="text" value="<?php echo $meta['owner_area']; ?>" class="form-control">
				</div>

				<label for="owner_phone" class="col-sm-4 col-form-label">Τηλέφωνο</label> 
				<div class="col-sm-8">
				  <input <?php echo $disabled; ?> id="owner_phone" name="owner_phone" type="text" value="<?php echo $meta['owner_phone']; ?>" class="form-control">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4">Φύλο</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="gender" id="gender_0" type="radio" class="custom-control-input" value="Αρσενικό" <?php echo ( $meta['gender'] == "Αρσενικό" ? "checked" : "" ); ?> > 
						<label for="gender_0" class="custom-control-label">Αρσενικό</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="gender" id="gender_1" type="radio" class="custom-control-input" value="Θηλυκό" <?php echo ( $meta['gender'] == "Θηλυκό" ? "checked" : "" ); ?> > 
						<label for="gender_1" class="custom-control-label">Θηλυκό</label>
					</div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 required">Κατάλληλο κατάλυμα</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing" id="appropriate_housing_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_housing'] == "Ναι" ? "checked" : "" ); ?> required> 
						<label for="appropriate_housing_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing" id="appropriate_housing_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_housing'] == "Μερικώς" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_1" class="custom-control-label">Μερικώς</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing" id="appropriate_housing_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_housing'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_2" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4 required">Κατάλληλο νερό / τροφή</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food" id="appropriate_water_food_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_water_food'] == "Ναι" ? "checked" : "" ); ?> required> 
						<label for="appropriate_water_food_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food" id="appropriate_water_food_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_water_food'] == "Μερικώς" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_1" class="custom-control-label">Μερικώς</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food" id="appropriate_water_food_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_water_food'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_2" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4 required">Κοντή αλυσίδα ή μόνιμα δεμένο</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="short_leash_or_on_permanent_leash" id="short_leash_or_on_permanent_leash_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['short_leash_or_on_permanent_leash'] == "Ναι" ? "checked" : "" ); ?> required> 
						<label for="short_leash_or_on_permanent_leash_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="short_leash_or_on_permanent_leash" id="short_leash_or_on_permanent_leash_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['short_leash_or_on_permanent_leash'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="short_leash_or_on_permanent_leash_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4 required">Εμβόλια τρέχοντος έτους</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="vaccinated_in_the_past_year" id="vaccinated_in_the_past_year_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['vaccinated_in_the_past_year'] == "Ναι" ? "checked" : "" ); ?> required> 
						<label for="vaccinated_in_the_past_year_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="vaccinated_in_the_past_year" id="vaccinated_in_the_past_year_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['vaccinated_in_the_past_year'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="vaccinated_in_the_past_year_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4 required">Ηλεκτρονική σήμανση (τσιπ)</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-show=".chip_number" name="chipped" id="chipped_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['chipped'] == "Ναι" ? "checked" : "" ); ?> required> 
						<label for="chipped_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-hide=".chip_number" name="chipped" id="chipped_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['chipped'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="chipped_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
		  
				<label for="chip_number" class="col-sm-4 col-form-label hidden chip_number">Αριθμός τσιπ</label> 
				<div class="col-sm-8 hidden chip_number">
					<input <?php echo $disabled; ?> id="chip_number" name="chip_number" type="text" value="<?php echo $meta['chip_number']; ?>" class="form-control">
				</div>
				
				<label for="photos" class="col-sm-4 col-form-label">Φωτογραφίες</label> 
				<input type="hidden" class="photos-files" name="photos-files" id="photos-files" value='<?php echo $meta['photos-files']; ?>'>
				<div class="col-sm-8">
					<div id="photos" class="dropzone">
					  <div class="dz-message">
						<button type="button" class="dz-button">Αφήστε αρχεία πάνω σε αυτό το πλαίσιο ή κάντε κλικ για να επιλέξετε.</button><br>
					  </div>
					</div>
				</div>
				
				<label for="comments" class="col-sm-4 col-form-label">Σχόλια</label> 
				<div class="col-sm-8">
					<textarea <?php echo $disabled; ?> id="comments" name="comments" cols="40" rows="5" class="form-control"><?php echo $meta['comments']; ?></textarea>
				</div>
			</div>
			
			<hr><br>
			
			<h5>Πρώτος επανέλεγχος</h5>
			
			<hr>
			
			<div class="form-group row">
				<label for="first_recheck_date" class="col-sm-4 col-form-label">Ημερομηνία ΠΡΩΤΟΥ επανελέγχου</label> 
				<div class="col-sm-8">
				<input <?php echo $disabled; ?> id="first_recheck_date" name="first_recheck_date" type="text" value="<?php echo ( $meta['first_recheck_date'] ? date("d/m/Y", strtotime($meta['first_recheck_date'])) : "" ) ?>" class="form-control datepicker">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4">Το σκυλί ΔΕΝ βρέθηκε</label> 
				<div class="col-sm-8">
				<div class="custom-control custom-checkbox custom-control-inline">
					<input <?php echo $disabled; ?> type="hidden" value="0" name="dog_not_found">
					<input <?php echo $disabled; ?> name="dog_not_found" id="dog_not_found_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['dog_not_found'] ? "checked" : "" ); ?> > 
					<label for="dog_not_found_0" class="custom-control-label"></label>
				</div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4">Ίδιος με αρχικό έλεγχο</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-checkbox custom-control-inline">
						<input <?php echo $disabled; ?> type="hidden" value="0" name="owner_same_as_first">
						<input <?php echo $disabled; ?> data-hide="#owner_2_wrapper" name="owner_same_as_first" id="owner_same_as_first_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['owner_same_as_first'] ? "checked" : "" ); ?> > 
						<label for="owner_same_as_first_0" class="custom-control-label"></label>
				  </div>
				</div>
			</div>
			
			<div class="form-group row" id="owner_2_wrapper">
				<label for="owner_2_lastname" class="col-sm-4 col-form-label">Επώνυμο</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_2_lastname" name="owner_2_lastname" type="text" value="<?php echo $meta['owner_2_lastname']; ?>" class="form-control">
				</div>

				<label for="owner_2_firstname" class="col-sm-4 col-form-label">Όνομα</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_2_firstname" name="owner_2_firstname" type="text" value="<?php echo $meta['owner_2_firstname']; ?>" class="form-control">
				</div>

				<label for="owner_2_area" class="col-sm-4 col-form-label">Περιοχή διαμονής</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_2_area" name="owner_2_area" type="text" value="<?php echo $meta['owner_2_area']; ?>" class="form-control">
				</div>

				<label for="owner_2_phone" class="col-sm-4 col-form-label">Τηλέφωνο</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_2_phone" name="owner_2_phone" type="text" value="<?php echo $meta['owner_2_phone']; ?>" class="form-control">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4">Κατάλληλο κατάλυμα</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing_2" id="appropriate_housing_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_housing_2'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_2_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing_2" id="appropriate_housing_2_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_housing_2'] == "Μερικώς" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_2_1" class="custom-control-label">Μερικώς</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing_2" id="appropriate_housing_2_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_housing_2'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_2_2" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Κατάλληλο νερό / τροφή</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food_2" id="appropriate_water_food_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_water_food_2'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_2_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food_2" id="appropriate_water_food_2_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_water_food_2'] == "Μερικώς" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_2_1" class="custom-control-label">Μερικώς</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food_2" id="appropriate_water_food_2_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_water_food_2'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_2_2" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Κοντή αλυσίδα ή μόνιμα δεμένο</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="short_leash_or_on_permanent_leash_2" id="short_leash_or_on_permanent_leash_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['short_leash_or_on_permanent_leash_2'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="short_leash_or_on_permanent_leash_2_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="short_leash_or_on_permanent_leash_2" id="short_leash_or_on_permanent_leash_2_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['short_leash_or_on_permanent_leash_2'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="short_leash_or_on_permanent_leash_2_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Εμβόλια τρέχοντος έτους</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="vaccinated_in_the_past_year_2" id="vaccinated_in_the_past_year_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['vaccinated_in_the_past_year_2'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="vaccinated_in_the_past_year_2_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="vaccinated_in_the_past_year_2" id="vaccinated_in_the_past_year_2_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['vaccinated_in_the_past_year_2'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="vaccinated_in_the_past_year_2_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Ηλεκτρονική σήμανση (τσιπ)</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-show=".chip_number_2" name="chipped_2" id="chipped_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['chipped_2'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="chipped_2_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-hide=".chip_number_2" name="chipped_2" id="chipped_2_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['chipped_2'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="chipped_2_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
						  
				<label for="chip_number_2" class="hidden chip_number_2 col-sm-4 col-form-label">Αριθμός τσιπ</label> 
				<div class="hidden chip_number_2 col-sm-8">
					<input <?php echo $disabled; ?> id="chip_number_2" name="chip_number_2" type="text" value="<?php echo $meta['chip_number_2']; ?>" class="form-control">
				</div>
		
				<label for="photos_2" class="col-sm-4 col-form-label">Φωτογραφίες</label> 
				<input type="hidden" class="photos-files" name="photos-2-files" id="photos-2-files" value='<?php echo $meta['photos-2-files']; ?>'>
				<div class="col-sm-8">
					<div id="photos-2" class="dropzone">
					  <div class="dz-message">
						<button type="button" class="dz-button">Αφήστε αρχεία πάνω σε αυτό το πλαίσιο ή κάντε κλικ για να επιλέξετε.</button><br>
					  </div>
					</div>
				</div>
				
				<label for="comments_2" class="col-sm-4 col-form-label">Σχόλια</label> 
				<div class="col-sm-8">
					<textarea <?php echo $disabled; ?> id="comments_2" name="comments_2" cols="40" rows="5" class="form-control"><?php echo $meta['comments_2']; ?></textarea>
				</div>
			</div>
			
			<hr><br>
			
			<h5>Δεύτερος επανέλεγχος</h5>
			
			<hr>
			
			<div class="form-group row">
				<label for="second_recheck_date" class="col-sm-4 col-form-label">Ημερομηνία ΔΕΥΤΕΡΟΥ επανελέγχου</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="second_recheck_date" name="second_recheck_date" type="text" value="<?php echo $meta['second_recheck_date']; ?>" class="form-control datepicker">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4">Το σκυλί ΔΕΝ βρέθηκε</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-checkbox custom-control-inline">
						<input <?php echo $disabled; ?> type="hidden" value="0" name="dog_not_found_2">
						<input <?php echo $disabled; ?> name="dog_not_found_2" id="dog_not_found_2_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['dog_not_found_2'] ? "checked" : "" ); ?> > 
						<label for="dog_not_found_2_0" class="custom-control-label"></label>
					</div>
				</div>
			</div>
		  
			<div class="form-group row">
				<label class="col-sm-4">Ίδιος με ΠΡΩΤΟ επανέλεγχο</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-checkbox custom-control-inline">
						<input <?php echo $disabled; ?> type="hidden" value="0" name="owner_same_as_second">
						<input <?php echo $disabled; ?> data-hide="#owner_3_wrapper" name="owner_same_as_second" id="owner_same_as_second_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['owner_same_as_second'] ? "checked" : "" ); ?> > 
						<label for="owner_same_as_second_0" class="custom-control-label"></label>
					  </div>
				</div>
			</div>
			
			<div class="form-group row" id="owner_3_wrapper">
				<label for="owner_3_lastname" class="col-sm-4 col-form-label">Επώνυμο</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_3_lastname" name="owner_3_lastname" type="text" value="<?php echo $meta['owner_3_lastname']; ?>" class="form-control">
				</div>

				<label for="owner_3_firstname" class="col-sm-4 col-form-label">Όνομα</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_3_firstname" name="owner_3_firstname" type="text" value="<?php echo $meta['owner_3_firstname']; ?>" class="form-control">
				</div>

				<label for="owner_3_area" class="col-sm-4 col-form-label">Περιοχή διαμονής</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_3_area" name="owner_3_area" type="text" value="<?php echo $meta['owner_3_area']; ?>" class="form-control">
				</div>

				<label for="owner_3_phone" class="col-sm-4 col-form-label">Τηλέφωνο</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="owner_3_phone" name="owner_3_phone" type="text" value="<?php echo $meta['owner_3_phone']; ?>" class="form-control">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4">Κατάλληλο κατάλυμα</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing_3" id="appropriate_housing_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_housing_3'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_3_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing_3" id="appropriate_housing_3_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_housing_3'] == "Μερικώς" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_3_1" class="custom-control-label">Μερικώς</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_housing_3" id="appropriate_housing_3_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_housing_3'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="appropriate_housing_3_2" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Κατάλληλο νερό / τροφή</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food_3" id="appropriate_water_food_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_water_food_3'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_3_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food_3" id="appropriate_water_food_3_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_water_food_3'] == "Μερικώς" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_3_1" class="custom-control-label">Μερικώς</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="appropriate_water_food_3" id="appropriate_water_food_3_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_water_food_3'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="appropriate_water_food_3_2" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Κοντή αλυσίδα ή μόνιμα δεμένο</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="short_leash_or_on_permanent_leash_3" id="short_leash_or_on_permanent_leash_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['short_leash_or_on_permanent_leash_3'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="short_leash_or_on_permanent_leash_3_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="short_leash_or_on_permanent_leash_3" id="short_leash_or_on_permanent_leash_3_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['short_leash_or_on_permanent_leash_3'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="short_leash_or_on_permanent_leash_3_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Εμβόλια τρέχοντος έτους</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="vaccinated_in_the_past_year_3" id="vaccinated_in_the_past_year_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['vaccinated_in_the_past_year_3'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="vaccinated_in_the_past_year_3_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> name="vaccinated_in_the_past_year_3" id="vaccinated_in_the_past_year_3_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['vaccinated_in_the_past_year_3'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="vaccinated_in_the_past_year_3_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			
				<label class="col-sm-4">Ηλεκτρονική σήμανση (τσιπ)</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-show=".chip_number_3" name="chipped_3" id="chipped_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['chipped_3'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="chipped_3_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-hide=".chip_number_3" name="chipped_3" id="chipped_3_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['chipped_3'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="chipped_3_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
		  
				<label for="chip_number_3" class="hidden chip_number_3 col-sm-4 col-form-label">Αριθμός τσιπ</label> 
				<div class="hidden chip_number_3 col-sm-8">
					<input <?php echo $disabled; ?> id="chip_number_3" name="chip_number_3" type="text" value="<?php echo $meta['chip_number_3']; ?>" class="form-control">
				</div>
				
				<label for="photos_3" class="col-sm-4 col-form-label">Φωτογραφίες</label> 
				<input type="hidden" class="photos-files" name="photos-3-files" id="photos-3-files" value='<?php echo $meta['photos-3-files']; ?>'>
				<div class="col-sm-8">
					<div id="photos-3" class="dropzone">
					  <div class="dz-message">
						<button type="button" class="dz-button">Αφήστε αρχεία πάνω σε αυτό το πλαίσιο ή κάντε κλικ για να επιλέξετε.</button><br>
					  </div>
					</div>
				</div>
				
				<label for="comments_3" class="col-sm-4 col-form-label">Σχόλια</label> 
				<div class="col-sm-8">
					<textarea <?php echo $disabled; ?> id="comments_3" name="comments_3" cols="40" rows="5" class="form-control"><?php echo $meta['comments_3']; ?></textarea>
				</div>
			</div>
			
			<hr><br>
			
			<h5>Τελικός έλεγχος</h5>
			
			<hr>
			
			<div class="form-group row">
				<label for="last_check_date" class="col-sm-4 col-form-label">Ημερομηνία ΤΕΛΙΚΟΥ ελέγχου</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="last_check_date" name="last_check_date" type="text" value="<?php echo $meta['last_check_date']; ?>" class="form-control datepicker">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4">Πλήρης συμμόρφωση (κατάλληλο κατάλυμα, τροφή, εμβόλια ΚΑΙ τσιπ)</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-checkbox custom-control-inline">
						<input <?php echo $disabled; ?> type="hidden" value="0" name="complete_compliance">
						<input <?php echo $disabled; ?> name="complete_compliance" id="complete_compliance_0" type="checkbox" class="custom-control-input" value="complete_compliance" <?php echo ( $meta['complete_compliance'] ? "checked" : "" ); ?> > 
						<label for="complete_compliance_0" class="custom-control-label"></label>
					</div>
				</div>
			</div>
		  
			<div class="form-group row">
				<label class="col-sm-4">Βεβαίωση προστίμου</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-checkbox custom-control-inline">
						<input <?php echo $disabled; ?> type="hidden" value="0" name="imposed_fine">
						<input <?php echo $disabled; ?> name="imposed_fine" id="imposed_fine_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['imposed_fine'] ? "checked" : "" ); ?> > 
						<label for="imposed_fine_0" class="custom-control-label"></label>
					</div>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="imposed_fine_amount" class="col-sm-4 col-form-label">Ποσό προστίμου</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="imposed_fine_amount" name="imposed_fine_amount" type="text" value="<?php echo $meta['imposed_fine_amount']; ?>" class="form-control">
				</div>
			</div>
		  
			<div class="form-group row">
				<label class="col-sm-4">Επικύρωση προστίμου</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-show="#imposed_fine_link_wrapper" name="imposed_fine_validated" id="imposed_fine_validated_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['imposed_fine_validated'] == "Ναι" ? "checked" : "" ); ?> > 
						<label for="imposed_fine_validated_0" class="custom-control-label">Ναι</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input <?php echo $disabled; ?> data-hide="#imposed_fine_link_wrapper" name="imposed_fine_validated" id="imposed_fine_validated_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['imposed_fine_validated'] == "Όχι" ? "checked" : "" ); ?> > 
						<label for="imposed_fine_validated_1" class="custom-control-label">Όχι</label>
					</div>
				</div>
			</div>
		  
			<div class="form-group row hidden" id="imposed_fine_link_wrapper">
				<label for="imposed_fine_validation_link" class="col-sm-4 col-form-label">Link επικύρωσης προστίμου</label> 
				<div class="col-sm-8">
					<input <?php echo $disabled; ?> id="imposed_fine_validation_link" name="imposed_fine_validation_link" value="<?php echo $meta['imposed_fine_validation_link']; ?>" type="text" class="form-control">
				</div>
			</div>
		  
			<div class="form-group row">
				<label class="col-sm-4">Ολοκληρώθηκε</label> 
				<div class="col-sm-8">
					<div class="custom-control custom-checkbox custom-control-inline">
						<input <?php echo $disabled; ?> type="hidden" value="0" name="completed">
						<input <?php echo $disabled; ?> name="completed" id="completed_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['completed'] ? "checked" : "" ); ?> > 
						<label for="completed_0" class="custom-control-label"></label>
					</div>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="final_comments" class="col-sm-4 col-form-label">Επιπλέον σχόλια / έλεγχοι</label> 
				<div class="col-sm-8">
					<textarea <?php echo $disabled; ?> id="final_comments" name="final_comments" cols="40" rows="5" class="form-control"><?php echo $meta['final_comments']; ?></textarea>
				</div>
			</div>
			
			<?php if ($post_id) : ?>
				<div class="form-group row">
					<div class="offset-4 col-sm-6">
						<button name="submit" type="submit" class="btn btn-primary">Αποθήκευση</button>
					</div>
					<?php if ( current_user_can( "edit_others_posts" ) ): ?>
						<div class="col-sm-2 text-right">
							<button id="delete" class="btn btn-danger">Διαγραφή</button>
						</div>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<div class="form-group row">
					<div class="offset-4 col-sm-8">
						<button name="submit" type="submit" class="btn btn-primary">Καταχώρηση</button>
					</div>
				</div>
			<?php endif; ?>
			
			<input <?php echo $disabled; ?> type="hidden" id="post_id" name="post_id" value="<?php echo $post_id; ?>"/>
			<input <?php echo $disabled; ?> type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('stray-incidents-form'); ?>"/>
		</form>

		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Χάρτης σημείου</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 modal_body_map">
								<div class="location-map" id="location-map">
									<div style="width: 600px; height: 400px;" id="map_canvas"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif;
}

add_action( 'wp_ajax_stray_incident_submission', 'stray_incident_submission' );
function stray_incident_submission() {
	
	check_ajax_referer( 'stray-incidents-form', 'security' );
		
	if ($_FILES) {
			
		$filename = $_FILES['file']['name'];
		
		$filesize = $_FILES['file']['size'];

		$filepath = wp_upload_dir()['path'] . "/" . $filename;
		
		$dataURL = wp_upload_dir()['url'] . "/" . $filename;
		
		if (is_file($filepath)) {
			wp_send_json_error("Το αρχείο υπάρχει ήδη. Παρακαλούμε αλλάξτε το όνομά του και δοκιμάστε ξανά.", 500);
		}

		if(move_uploaded_file($_FILES['file']['tmp_name'], $filepath)){
			
			if(is_array(getimagesize($filepath))){
				wp_send_json(array("filepath" => $filepath, "dataURL" => $dataURL));
			}
			else {
				wp_die();
			}
		}
		
		
	}
	else {

		$query = new WP_Query( array(
			'category_name' => 'form',
			'orderby' => 'title',
			'status' => 'publish',
			'order'   => 'DESC',
			'limit' => 1
		));
		
		$posts = $query->posts;
		
		$post_id = '';
		
		if ( isset($_POST['post_id']) ) {
			$post_id = $_POST['post_id'];
			unset($_POST['post_id']);
			$post_title = $_POST['post_title'];
			unset($_POST['post_title']);
		}
			
		if (!$post_id && get_page_by_title($post_title, OBJECT, 'post')) {
			wp_send_json_error( 'Ο κωδικός περιστατικού υπάρχει ήδη.' );
		}
		else {
		
			if ( $_POST['date'] ) {
				$temp_date = DateTime::createFromFormat('d/m/Y', $_POST['date']);
				$_POST['date'] = $temp_date->format('Y-m-d');
			}
			
			if ( $_POST['first_recheck_date'] ) {
				$temp_date = DateTime::createFromFormat('d/m/Y', $_POST['first_recheck_date']);
				$_POST['first_recheck_date'] = $temp_date->format('Y-m-d');
			}
			
			if (!$post_id) {
				$insert_result = wp_insert_post(array(
					'post_title' => $posts ? ( is_numeric($post_title) ? intval($post_title) : intval($posts[0]->post_title) + 1 ) : '2500',
					'post_name' => $posts ? ( is_numeric($post_title) ? intval($post_title) : intval($posts[0]->post_title) + 1 ) : '2500',
					'post_status'   => 'publish',
					'post_category' => array( get_category_by_slug('form') -> term_id ),
					'meta_input' => $_POST
				), true);
				$data = 'Η καταχώρηση ολοκληρώθηκε. Πατήστε ΟΚ για επαναφόρτωση της σελίδας.';
			}
			else {
				$insert_result = wp_insert_post(array(
					'ID'	=> intval($post_id),
					'post_title' => is_numeric($post_title) ? intval($post_title) : get_post($post_id)->name,
					'post_name' => is_numeric($post_title) ? intval($post_title) : get_post($post_id)->name,
					'post_status'   => 'publish',
					'post_category' => array( get_category_by_slug('form') -> term_id ),
					'meta_input' => $_POST
				), true);
				$data = 'Η αποθήκευση ολοκληρώθηκε. Πατήστε ΟΚ για επαναφόρτωση της σελίδας.';
			}
				
			if ( !is_wp_error( $insert_result ) ) {
				wp_send_json( array( 'success' => true, 'data' => $data ) );
			}
			else {
				wp_send_json_error( $insert_result );
			}
		}
	}
}

add_action( 'wp_ajax_stray_incident_delete', 'stray_incident_delete' );
function stray_incident_delete() {
	
	check_ajax_referer( 'stray-incidents-form', 'security' );
	
	$delete_result = wp_delete_post( $_POST['post_id'] );
	
	if ( !is_wp_error( $delete_result ) ) {
		wp_send_json( array( 'success' => true ) );
	}
	else {
		wp_send_json_error( $delete_result );
	}
}

add_action( 'wp_ajax_stray_incident_delete_file', 'stray_incident_delete_file' );
function stray_incident_delete_file() {
	
	check_ajax_referer( 'stray-incidents-form', 'security' );
		
	if (is_file($_POST['filepath'])) {
		$delete_result = wp_delete_file( $_POST['filepath'] );
		
		if ( !is_wp_error( $delete_result ) ) {
			
			if ($_POST['post_id']) {
				$photos = json_decode(get_post_meta( $_POST['post_id'] )[$_POST['meta_key']][0]);
				foreach ($photos as $key => $value) {
					if ($value -> filepath == $_POST['filepath']) {
						unset($photos[$key]);
					}
				}
				update_post_meta($_POST['post_id'], $_POST['meta_key'], json_encode($photos));
			}
			
			wp_send_json( array( 'success' => true ) );
		}
		else {
			wp_send_json_error( $delete_result );
		}
	}
}