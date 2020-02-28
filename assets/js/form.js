(function($) {
	
	$(document).ready(function() {
		
		$("#stray-incident-form").submit(function(e) {
			e.preventDefault();    
			var formData = new FormData(this);

			$.ajax({
				url: '/wp-admin/admin-ajax.php?action=stray_incident_submission',
				type: 'POST',
				data: formData,
				success: function (data) {
					if (data.success) {
						Swal.fire({
							title: 'Επιτυχία!',
							text: 'Η καταχώρηση ολοκληρώθηκε. Πατήστε ΟΚ για επαναφόρτωση της σελίδας.',
							icon: 'success',
							confirmButtonText: 'ΟΚ',
							onClose: () => {
								window.setTimeout(function() {
									window.scrollTo(0,0);
									location.reload();
								}, 300);
							}
						});
					}
					else {
						Swal.fire({
						  title: 'Σφάλμα',
						  text: 'Προέκυψε κάποιο σφάλμα. Παρακαλούμε προσπαθήστε ξανά.',
						  icon: 'error',
						  confirmButtonText: 'ΟΚ'
						});
					}
				},
				cache: false,
				contentType: false,
				processData: false
			});
		});
		
		$("#delete").click(function(e) {
			e.preventDefault();
			
			Swal.fire({
				title: 'Προσοχή!',
				text: 'Θέλετε σίγουρα να διαγράψετε την καταχώρηση αυτήν;',
				icon: 'warning',
				confirmButtonText: 'Ναι',
				cancelButtonText: 'Όχι, ακύρωση',
				showCancelButton: true,
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: '/wp-admin/admin-ajax.php?action=stray_incident_delete',
						type: 'POST',
						data: {post_id : $('#post_id').val()} ,
						success: function (data) {
							if (data.success) {
								Swal.fire({
									title: 'Επιτυχία!',
									text: 'Η καταχώρηση διεγράφη. Πατήστε ΟΚ για επαναφόρτωση της σελίδας.',
									icon: 'success',
									onClose: () => {
										window.setTimeout(function() {
											window.scrollTo(0,0);
											location.href = "/";
										}, 300);
									}
								});
							}
						},
						error: function(e) {
							Swal.fire({
								title: 'Σφάλμα',
								text: 'Η διαγραφή της καταχώρησης δεν ήταν δυνατή. Παρακαλούμε επιβεβαιώστε ότι είστε συνδεδεμένοι.',
								icon: 'error'
							});
						},
					});
				}
			});
		});
		
		$('*[data-show]').each(function() {
			if ( $(this).is(':checked') ){
				$($(this).data('show')).removeClass("hidden");
				//TODO: Restore required.
			}
		});
			
		$('*[data-hide]').each(function() {
			if ( $(this).is(':checked') ) {
				$($(this).data('hide')).addClass("hidden");
				//TODO: Un-required.
			}
		});
		
		$('*[data-show]').change(function() {
			if ($(this).is(':checkbox') && $(this).is(':not(:checked)')) {
				$($(this).data('show')).addClass("hidden");
				//TODO: Un-required.
			}
			else {
				$($(this).data('show')).removeClass("hidden");
				//TODO: Restore required.
			}
		});
			
		$('*[data-hide]').change(function() {
			if ($(this).is(':checkbox') && $(this).is(':not(:checked)')) {
				$($(this).data('hide')).removeClass("hidden");
				//TODO: Restore required.
			}
			else {
				$($(this).data('hide')).addClass("hidden");
				//TODO: Un-required.
			}
		});
		
		$( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
		
		$('#myModal').on('shown.bs.modal', function(event) {
			var button = $(event.relatedTarget);
			initializeGMap(button.data('lat'), button.data('lng'));
			$("#location-map").css("width", "100%");
			$("#map_canvas").css("width", "100%");
		});
		
		$('#myModal').on('shown.bs.modal', function() {
			google.maps.event.trigger(map, "resize");
			map.setCenter(myLatlng);
		});
	});
	
	function initializeGMap(lat, lng) {
		myLatlng = new google.maps.LatLng(lat, lng);

		var myOptions = {
			zoom: 15,
			zoomControl: true,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		myMarker = new google.maps.Marker({
			position: myLatlng
		});
		
		myMarker.setMap(map);
	}
		
})( jQuery );