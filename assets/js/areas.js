(function($) {
	
	$(document).ready(function() {
		
		$("#stray-incidents-areas").submit(function(e) {
			e.preventDefault();
												
			var formData = new FormData(this);

			$.ajax({
				url: ajax_object.ajaxurl + '?action=stray_incidents_areas_update',
				type: 'POST',
				data: formData,
				success: function (result) {
					if (result.success) {
						Swal.fire({
							title: 'Επιτυχία!',
							text: result.data,
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
						  text: result.data,
						  icon: 'error',
						  confirmButtonText: 'ΟΚ'
						});
					}
				},
				error: function(error) {
					Swal.fire({
					  title: 'Σφάλμα',
					  text: 'Προέκυψε κάποιο σφάλμα. Παρακαλούμε φορτώστε εκ νέου τη σελίδα και προσπαθήστε ξανά.',
					  icon: 'error',
					  confirmButtonText: 'ΟΚ'
					});
				},
				cache: false,
				contentType: false,
				processData: false
			});
		});
	});
})( jQuery );