(function($) {
	
	$(document).ready(function() {
		
		Dropzone.autoDiscover = false;
		
		$(document).on('click', '.dz-image img', function() {
			$.fancybox.open('<img src="' + $(this).attr('alt') + '">');
		});
		
		let existingImages = [];
		
		$(".dropzone").dropzone({
			init: function() {
				this.on("sending", function(file, xhr, formData) {
				  formData.append("security", ajax_object.ajax_nonce);
				});
				
				thisDropzone = this;
				thisID = $($(this)[0]['element']).attr("id");

				if ($('#' + thisID + '-files').val()) {

					let file_urls = JSON.parse($('#' + $($(this)[0]['element']).attr("id") + '-files').val());
					
					existingImages[thisID] = [];
								
					$.each(file_urls, function(index, value) {
						
						if (value) {
							let mockFile = { name: value['dataURL'], filepath: value['filepath'] };

							thisDropzone.displayExistingFile(mockFile, value['dataURL']);
							
							existingImages[thisID].push({"filepath": value['filepath'], "dataURL": value["dataURL"]});
						}
					});
				}				
			},
			success: function(result) {
				this.filepath = result.filepath;
			},
			error: function(file, message) {
				Swal.fire({
				  title: 'Σφάλμα',
				  text: message.data,
				  icon: 'error',
				  confirmButtonText: 'ΟΚ'
				});
				this.removeFile(file);
			},
			thumbnailWidth: 200,
			thumbnailMethod: "contain",
			url: ajax_object.ajaxurl + "?action=stray_incident_submission",
			acceptedFiles: "image/*",
			addRemoveLinks: true,
			dictRemoveFileConfirmation: "Είστε σίγουρος;",
			removedfile: function(file) {
				$.ajax({
					url: ajax_object.ajaxurl + '?action=stray_incident_delete_file',
					type: 'POST',
					data: {post_id: $("#post_id").val(), meta_key: file.previewElement.parentElement.attributes[0].value + "-files", filepath: file.xhr ? JSON.parse(file.xhr.response).filepath : file.filepath, security: ajax_object.ajax_nonce},
					success: function (result) {
						existingImages[file.previewElement.parentElement.attributes[0].value] = $.grep(existingImages[file.previewElement.parentElement.attributes[0].value], function(e){ return e.filepath != file.filepath; });
						file.previewElement.remove();
					}
				});
			}
		});
			
		$("#stray-incident-form").submit(function(e) {
			e.preventDefault();
						
			$(".photos-files").each(function () {
				final_value = [];
				$.each($('#' + $(this).attr('id').split('-files')[0])[0].dropzone.files, function() {
					final_value.push(JSON.parse($(this)[0].xhr.response));
				});
				
				$(this).val(JSON.stringify(final_value.concat(existingImages[$(this).attr('id').split('-files')[0]])));
			});
						
			var formData = new FormData(this);

			$.ajax({
				url: ajax_object.ajaxurl + '?action=stray_incident_submission',
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
						url: ajax_object.ajaxurl + '?action=stray_incident_delete',
						type: 'POST',
						data: {post_id : $('#post_id').val(), security: ajax_object.ajax_nonce} ,
						success: function (result) {
							if (result.success) {
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