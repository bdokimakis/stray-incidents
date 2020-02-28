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
						
		$title = $post_id ? 'Επεξεργασία περιστατικού: ' . get_the_title($post_id) : "Νέο περιστατικό";
	?>
	
	<h4 class="text-center mb-4"><?php echo $title; ?></h4><hr>
	
	<form id="stray-incident-form" autocomplete="off">
		<div class="form-group row">
			<label class="col-sm-4 col-form-label" for="date">Ημερομηνία αρχικού εντοπισμού / ελέγχου</label> 
			<div class="col-sm-8">
				<input id="date" name="date" type="text" value="<?php echo ( $meta['date'] ? date("d/m/Y", strtotime($meta['date'])) : "" ) ?>" class="form-control datepicker">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="area" class="col-sm-4 col-sm-form-label">Περιοχή</label> 
			<div class="col-sm-8">
				<select id="area" name="area" class="custom-select">
					<option></option>
					<option value="perioxi1">Περιοχή 1</option>
					<option value="perioxi2">Περιοχή 2</option>
					<option value="perioxi3">Περιοχή 3</option>
				  </select> 
			</div>
		</div>
		
		<div class="form-group row">
			<label for="lat" class="col-sm-4 col-sm-form-label">Γεωγραφικό πλάτος (lat)</label> 
			<div class="col-sm-8">
				<input id="lat" name="lat" type="text" value="<?php echo $meta['lat']; ?>" class="form-control">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="lng" class="col-sm-4 col-sm-form-label">Γεωγραφικό μήκος (lng)</label> 
			<div class="col-sm-8">
				<input id="lng" name="lng" type="text" value="<?php echo $meta['lng']; ?>" class="form-control">
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
					<input data-show="#owner_wrapper" name="owner_found" id="owner_found_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['owner_found'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="owner_found_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input data-hide="#owner_wrapper" name="owner_found" id="owner_found_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['owner_found'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="owner_found_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		</div>
		
		<div class="form-group row hidden" id="owner_wrapper">
			<label for="owner_lastname" class="col-sm-4 col-sm-form-label">Επώνυμο</label> 
			<div class="col-sm-8">
				<input id="owner_lastname" name="owner_lastname" type="text" value="<?php echo $meta['owner_lastname']; ?>" class="form-control">
			</div>

			<label for="owner_firstname" class="col-sm-4 col-sm-form-label">Όνομα</label> 
			<div class="col-sm-8">
				<input id="owner_firstname" name="owner_firstname" type="text" value="<?php echo $meta['owner_firstname']; ?>" class="form-control">
			</div>

			<label for="owner_area" class="col-sm-4 col-sm-form-label">Περιοχή διαμονής</label> 
			<div class="col-sm-8">
				<input id="owner_area" name="owner_area" type="text" value="<?php echo $meta['owner_area']; ?>" class="form-control">
			</div>

			<label for="owner_phone" class="col-sm-4 col-sm-form-label">Τηλέφωνο</label> 
			<div class="col-sm-8">
			  <input id="owner_phone" name="owner_phone" type="text" value="<?php echo $meta['owner_phone']; ?>" class="form-control">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Φύλο</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="gender" id="gender_0" type="radio" class="custom-control-input" value="Αρσενικό" <?php echo ( $meta['gender'] == "Αρσενικό" ? "checked" : "" ); ?> > 
					<label for="gender_0" class="custom-control-label">Αρσενικό</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="gender" id="gender_1" type="radio" class="custom-control-input" value="Θηλυκό" <?php echo ( $meta['gender'] == "Θηλυκό" ? "checked" : "" ); ?> > 
					<label for="gender_1" class="custom-control-label">Θηλυκό</label>
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Κατάλληλο κατάλυμα</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing" id="appropriate_housing_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_housing'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing" id="appropriate_housing_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_housing'] == "Μερικώς" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_1" class="custom-control-label">Μερικώς</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing" id="appropriate_housing_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_housing'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_2" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Κατάλληλο νερό / τροφή</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food" id="appropriate_water_food_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_water_food'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food" id="appropriate_water_food_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_water_food'] == "Μερικώς" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_1" class="custom-control-label">Μερικώς</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food" id="appropriate_water_food_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_water_food'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_2" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Κοντή αλυσίδα ή μόνιμα δεμένο</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="short_leash_or_on_permanent_leash" id="short_leash_or_on_permanent_leash_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['short_leash_or_on_permanent_leash'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="short_leash_or_on_permanent_leash_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="short_leash_or_on_permanent_leash" id="short_leash_or_on_permanent_leash_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['short_leash_or_on_permanent_leash'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="short_leash_or_on_permanent_leash_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Εμβόλια τρέχοντος έτους</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="vaccinated_in_the_past_year" id="vaccinated_in_the_past_year_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['vaccinated_in_the_past_year'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="vaccinated_in_the_past_year_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="vaccinated_in_the_past_year" id="vaccinated_in_the_past_year_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['vaccinated_in_the_past_year'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="vaccinated_in_the_past_year_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Ηλεκτρονική σήμανση (τσιπ)</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input data-show="#chip_number_wrapper" name="chipped" id="chipped_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['chipped'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="chipped_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input data-hide="#chip_number_wrapper" name="chipped" id="chipped_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['chipped'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="chipped_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
	  
			<div class="hidden" id="chip_number_wrapper">
				<label for="chip_number" class="col-sm-4 col-sm-form-label">Αριθμός τσιπ</label> 
				<div class="col-sm-8">
					<input id="chip_number" name="chip_number" type="text" value="<?php echo $meta['chip_number']; ?>" class="form-control">
				</div>
			</div>
			
			<label for="comments" class="col-sm-4 col-sm-form-label">Σχόλια</label> 
			<div class="col-sm-8">
				<textarea id="comments" name="comments" cols="40" rows="5" class="form-control"><?php echo $meta['comments']; ?></textarea>
			</div>
		</div>
		
		<hr><br>
		
		<h5>Πρώτος επανέλεγχος</h5>
		
		<hr>
		
		<div class="form-group row">
			<label for="first_recheck_date" class="col-sm-4 col-sm-form-label">Ημερομηνία ΠΡΩΤΟΥ επανελέγχου</label> 
			<div class="col-sm-8">
			<input id="first_recheck_date" name="first_recheck_date" type="text" value="<?php echo ( $meta['first_recheck_date'] ? date("d/m/Y", strtotime($meta['first_recheck_date'])) : "" ) ?>" class="form-control datepicker">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Το σκυλί ΔΕΝ βρέθηκε</label> 
			<div class="col-sm-8">
			<div class="custom-control custom-checkbox custom-control-inline">
				<input type="hidden" value="0" name="dog_not_found">
				<input name="dog_not_found" id="dog_not_found_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['dog_not_found'] ? "checked" : "" ); ?> > 
				<label for="dog_not_found_0" class="custom-control-label"></label>
			</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Ίδιος με αρχικό έλεγχο</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-checkbox custom-control-inline">
					<input type="hidden" value="0" name="owner_same_as_first">
					<input data-hide="#owner_2_wrapper" name="owner_same_as_first" id="owner_same_as_first_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['owner_same_as_first'] ? "checked" : "" ); ?> > 
					<label for="owner_same_as_first_0" class="custom-control-label"></label>
			  </div>
			</div>
		</div>
		
		<div class="form-group row" id="owner_2_wrapper">
			<label for="owner_2_lastname" class="col-sm-4 col-sm-form-label">Επώνυμο</label> 
			<div class="col-sm-8">
				<input id="owner_2_lastname" name="owner_2_lastname" type="text" value="<?php echo $meta['owner_2_lastname']; ?>" class="form-control">
			</div>

			<label for="owner_2_firstname" class="col-sm-4 col-sm-form-label">Όνομα</label> 
			<div class="col-sm-8">
				<input id="owner_2_firstname" name="owner_2_firstname" type="text" value="<?php echo $meta['owner_2_firstname']; ?>" class="form-control">
			</div>

			<label for="owner_2_area" class="col-sm-4 col-sm-form-label">Περιοχή διαμονής</label> 
			<div class="col-sm-8">
				<input id="owner_2_area" name="owner_2_area" type="text" value="<?php echo $meta['owner_2_area']; ?>" class="form-control">
			</div>

			<label for="owner_2_phone" class="col-sm-4 col-sm-form-label">Τηλέφωνο</label> 
			<div class="col-sm-8">
				<input id="owner_2_phone" name="owner_2_phone" type="text" value="<?php echo $meta['owner_2_phone']; ?>" class="form-control">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Κατάλληλο κατάλυμα</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing_2" id="appropriate_housing_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_housing_2'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_2_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing_2" id="appropriate_housing_2_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_housing_2'] == "Μερικώς" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_2_1" class="custom-control-label">Μερικώς</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing_2" id="appropriate_housing_2_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_housing_2'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_2_2" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Κατάλληλο νερό / τροφή</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food_2" id="appropriate_water_food_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_water_food_2'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_2_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food_2" id="appropriate_water_food_2_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_water_food_2'] == "Μερικώς" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_2_1" class="custom-control-label">Μερικώς</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food_2" id="appropriate_water_food_2_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_water_food_2'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_2_2" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Κοντή αλυσίδα ή μόνιμα δεμένο</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="short_leash_or_on_permanent_leash_2" id="short_leash_or_on_permanent_leash_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['short_leash_or_on_permanent_leash_2'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="short_leash_or_on_permanent_leash_2_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="short_leash_or_on_permanent_leash_2" id="short_leash_or_on_permanent_leash_2_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['short_leash_or_on_permanent_leash_2'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="short_leash_or_on_permanent_leash_2_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Εμβόλια τρέχοντος έτους</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="vaccinated_in_the_past_year_2" id="vaccinated_in_the_past_year_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['vaccinated_in_the_past_year_2'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="vaccinated_in_the_past_year_2_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="vaccinated_in_the_past_year_2" id="vaccinated_in_the_past_year_2_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['vaccinated_in_the_past_year_2'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="vaccinated_in_the_past_year_2_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Ηλεκτρονική σήμανση (τσιπ)</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input data-show=".chip_number_2" name="chipped_2" id="chipped_2_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['chipped_2'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="chipped_2_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input data-hide=".chip_number_2" name="chipped_2" id="chipped_2_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['chipped_2'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="chipped_2_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
	  
			<label for="chip_number_2" class="hidden chip_number_2 col-sm-4 col-sm-form-label">Αριθμός τσιπ</label> 
			<div class="hidden chip_number_2 col-sm-8">
				<input id="chip_number_2" name="chip_number_2" type="text" value="<?php echo $meta['chip_number_2']; ?>" class="form-control">
			</div>
			
			<label for="comments_2" class="col-sm-4 col-sm-form-label">Σχόλια</label> 
			<div class="col-sm-8">
				<textarea id="comments_2" name="comments_2" cols="40" rows="5" class="form-control"><?php echo $meta['comments_2']; ?></textarea>
			</div>
		</div>
		
		<hr><br>
		
		<h5>Δεύτερος επανέλεγχος</h5>
		
		<hr>
		
		<div class="form-group row">
			<label for="second_recheck_date" class="col-sm-4 col-sm-form-label">Ημερομηνία ΔΕΥΤΕΡΟΥ επανελέγχου</label> 
			<div class="col-sm-8">
				<input id="second_recheck_date" name="second_recheck_date" type="text" value="<?php echo $meta['second_recheck_date']; ?>" class="form-control datepicker">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Το σκυλί ΔΕΝ βρέθηκε</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-checkbox custom-control-inline">
					<input type="hidden" value="0" name="dog_not_found_2">
					<input name="dog_not_found_2" id="dog_not_found_2_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['dog_not_found_2'] ? "checked" : "" ); ?> > 
					<label for="dog_not_found_2_0" class="custom-control-label"></label>
				</div>
			</div>
		</div>
	  
		<div class="form-group row">
			<label class="col-sm-4">Ίδιος με ΠΡΩΤΟ επανέλεγχο</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-checkbox custom-control-inline">
					<input type="hidden" value="0" name="owner_same_as_second">
					<input data-hide="#owner_3_wrapper" name="owner_same_as_second" id="owner_same_as_second_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['owner_same_as_second'] ? "checked" : "" ); ?> > 
					<label for="owner_same_as_second_0" class="custom-control-label"></label>
				  </div>
			</div>
		</div>
		
		<div class="form-group row" id="owner_3_wrapper">
			<label for="owner_3_lastname" class="col-sm-4 col-sm-form-label">Επώνυμο</label> 
			<div class="col-sm-8">
				<input id="owner_3_lastname" name="owner_3_lastname" type="text" value="<?php echo $meta['owner_3_lastname']; ?>" class="form-control">
			</div>

			<label for="owner_3_firstname" class="col-sm-4 col-sm-form-label">Όνομα</label> 
			<div class="col-sm-8">
				<input id="owner_3_firstname" name="owner_3_firstname" type="text" value="<?php echo $meta['owner_3_firstname']; ?>" class="form-control">
			</div>

			<label for="owner_3_area" class="col-sm-4 col-sm-form-label">Περιοχή διαμονής</label> 
			<div class="col-sm-8">
				<input id="owner_3_area" name="owner_3_area" type="text" value="<?php echo $meta['owner_3_area']; ?>" class="form-control">
			</div>

			<label for="owner_3_phone" class="col-sm-4 col-sm-form-label">Τηλέφωνο</label> 
			<div class="col-sm-8">
				<input id="owner_3_phone" name="owner_3_phone" type="text" value="<?php echo $meta['owner_3_phone']; ?>" class="form-control">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Κατάλληλο κατάλυμα</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing_3" id="appropriate_housing_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_housing_3'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_3_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing_3" id="appropriate_housing_3_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_housing_3'] == "Μερικώς" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_3_1" class="custom-control-label">Μερικώς</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_housing_3" id="appropriate_housing_3_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_housing_3'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="appropriate_housing_3_2" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Κατάλληλο νερό / τροφή</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food_3" id="appropriate_water_food_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['appropriate_water_food_3'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_3_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food_3" id="appropriate_water_food_3_1" type="radio" class="custom-control-input" value="Μερικώς" <?php echo ( $meta['appropriate_water_food_3'] == "Μερικώς" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_3_1" class="custom-control-label">Μερικώς</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="appropriate_water_food_3" id="appropriate_water_food_3_2" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['appropriate_water_food_3'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="appropriate_water_food_3_2" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Κοντή αλυσίδα ή μόνιμα δεμένο</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="short_leash_or_on_permanent_leash_3" id="short_leash_or_on_permanent_leash_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['short_leash_or_on_permanent_leash_3'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="short_leash_or_on_permanent_leash_3_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="short_leash_or_on_permanent_leash_3" id="short_leash_or_on_permanent_leash_3_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['short_leash_or_on_permanent_leash_3'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="short_leash_or_on_permanent_leash_3_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Εμβόλια τρέχοντος έτους</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input name="vaccinated_in_the_past_year_3" id="vaccinated_in_the_past_year_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['vaccinated_in_the_past_year_3'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="vaccinated_in_the_past_year_3_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input name="vaccinated_in_the_past_year_3" id="vaccinated_in_the_past_year_3_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['vaccinated_in_the_past_year_3'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="vaccinated_in_the_past_year_3_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		
			<label class="col-sm-4">Ηλεκτρονική σήμανση (τσιπ)</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input data-show=".chip_number_3" name="chipped_3" id="chipped_3_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['chipped_3'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="chipped_3_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input data-hide=".chip_number_3" name="chipped_3" id="chipped_3_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['chipped_3'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="chipped_3_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
	  
			<label for="chip_number_3" class="hidden chip_number_3 col-sm-4 col-sm-form-label">Αριθμός τσιπ</label> 
			<div class="hidden chip_number_3 col-sm-8">
				<input id="chip_number_3" name="chip_number_3" type="text" value="<?php echo $meta['chip_number_3']; ?>" class="form-control">
			</div>
			
			<label for="comments_3" class="col-sm-4 col-sm-form-label">Σχόλια</label> 
			<div class="col-sm-8">
				<textarea id="comments_3" name="comments_3" cols="40" rows="5" class="form-control"><?php echo $meta['comments_3']; ?></textarea>
			</div>
		</div>
		
		<hr><br>
		
		<h5>Τελικός έλεγχος</h5>
		
		<hr>
		
		<div class="form-group row">
			<label for="last_check_date" class="col-sm-4 col-sm-form-label">Ημερομηνία ΤΕΛΙΚΟΥ ελέγχου</label> 
			<div class="col-sm-8">
				<input id="last_check_date" name="last_check_date" type="text" value="<?php echo $meta['last_check_date']; ?>" class="form-control datepicker">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-4">Πλήρης συμμόρφωση (κατάλληλο κατάλυμα, τροφή, εμβόλια ΚΑΙ τσιπ)</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-checkbox custom-control-inline">
					<input type="hidden" value="0" name="complete_compliance">
					<input name="complete_compliance" id="complete_compliance_0" type="checkbox" class="custom-control-input" value="complete_compliance" <?php echo ( $meta['complete_compliance'] ? "checked" : "" ); ?> > 
					<label for="complete_compliance_0" class="custom-control-label"></label>
				</div>
			</div>
		</div>
	  
		<div class="form-group row">
			<label class="col-sm-4">Βεβαίωση προστίμου</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-checkbox custom-control-inline">
					<input type="hidden" value="0" name="imposed_fine">
					<input name="imposed_fine" id="imposed_fine_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['imposed_fine'] ? "checked" : "" ); ?> > 
					<label for="imposed_fine_0" class="custom-control-label"></label>
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label for="imposed_fine_amount" class="col-sm-4 col-sm-form-label">Ποσό προστίμου</label> 
			<div class="col-sm-8">
				<input id="imposed_fine_amount" name="imposed_fine_amount" type="text" value="<?php echo $meta['imposed_fine_amount']; ?>" class="form-control">
			</div>
		</div>
	  
		<div class="form-group row">
			<label class="col-sm-4">Επικύρωση προστίμου</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-radio custom-control-inline">
					<input data-show="#imposed_fine_link_wrapper" name="imposed_fine_validated" id="imposed_fine_validated_0" type="radio" class="custom-control-input" value="Ναι" <?php echo ( $meta['imposed_fine_validated'] == "Ναι" ? "checked" : "" ); ?> > 
					<label for="imposed_fine_validated_0" class="custom-control-label">Ναι</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input data-hide="#imposed_fine_link_wrapper" name="imposed_fine_validated" id="imposed_fine_validated_1" type="radio" class="custom-control-input" value="Όχι" <?php echo ( $meta['imposed_fine_validated'] == "Όχι" ? "checked" : "" ); ?> > 
					<label for="imposed_fine_validated_1" class="custom-control-label">Όχι</label>
				</div>
			</div>
		</div>
	  
		<div class="form-group row hidden" id="imposed_fine_link_wrapper">
			<label for="imposed_fine_validation_link" class="col-sm-4 col-sm-form-label">Link επικύρωσης προστίμου</label> 
			<div class="col-sm-8">
				<input id="imposed_fine_validation_link" name="imposed_fine_validation_link" value="<?php echo $meta['imposed_fine_validation_link']; ?>" type="text" class="form-control">
			</div>
		</div>
	  
		<div class="form-group row">
			<label class="col-sm-4">Ολοκληρώθηκε</label> 
			<div class="col-sm-8">
				<div class="custom-control custom-checkbox custom-control-inline">
					<input type="hidden" value="0" name="completed">
					<input name="completed" id="completed_0" type="checkbox" class="custom-control-input" value="1" <?php echo ( $meta['completed'] ? "checked" : "" ); ?> > 
					<label for="completed_0" class="custom-control-label"></label>
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label for="final_comments" class="col-sm-4 col-sm-form-label">Επιπλέον σχόλια / έλεγχοι</label> 
			<div class="col-sm-8">
				<textarea id="final_comments" name="final_comments" cols="40" rows="5" class="form-control"><?php echo $meta['final_comments']; ?></textarea>
			</div>
		</div>
		
		<?php if ($post_id) : ?>
			<div class="form-group row">
				<div class="offset-4 col-sm-6">
					<button name="submit" type="submit" class="btn btn-primary">Αποθήκευση</button>
				</div>
				<div class="col-sm-2 text-right">
					<button id="delete" class="btn btn-danger">Διαγραφή</button>
				</div>
			</div>
		<?php else: ?>
			<div class="form-group row">
				<div class="offset-4 col-sm-8">
					<button name="submit" type="submit" class="btn btn-primary">Καταχώρηση</button>
				</div>
			</div>
		<?php endif; ?>
		
		<input type="hidden" id="post_id" name="post_id" value="<?php echo $post_id; ?>"/>
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
	
	<?php
}

add_action( 'wp_ajax_stray_incident_submission', 'stray_incident_submission' );
add_action( 'wp_ajax_nopriv_stray_incident_submission', 'stray_incident_submission' );
function stray_incident_submission() {
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
	}
	
	if ( $_POST['date'] ) {
		$temp_date = DateTime::createFromFormat('d/m/Y', $_POST['date']);
		$_POST['date'] = $temp_date->format('Y-m-d');
	}
	
	if ( $_POST['first_recheck_date'] ) {
		$temp_date = DateTime::createFromFormat('d/m/Y', $_POST['first_recheck_date']);
		$_POST['first_recheck_date'] = $temp_date->format('Y-m-d');
	}
		
	$insert_result = wp_insert_post(array(
		'ID'	=> intval($post_id),
		'post_title' => $posts ? ( $post_id != '' ? get_the_title($post_id) : intval($posts[0]->post_title) + 1 ) : '2500',
		'post_status'   => 'publish',
		'post_category' => array( get_category_by_slug('form') -> term_id ),
		'meta_input' => $_POST
	), true);
		
	if ( !is_wp_error( $insert_result ) ) {
		wp_send_json( array( 'success' => true ) );
	}
	else {
		wp_send_json_error( $insert_result );
	}
}

add_action( 'wp_ajax_stray_incident_delete', 'stray_incident_delete' );
function stray_incident_delete() {
	$delete_result = wp_delete_post( $_POST['post_id'] );
	
	if ( !is_wp_error( $delete_result ) ) {
		wp_send_json( array( 'success' => true ) );
	}
	else {
		wp_send_json_error( $delete_result );
	}
}