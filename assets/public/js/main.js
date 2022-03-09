; (function ($) {
	$(document).ready(function () {
		var max_height = 0;
		$(".carwash-appointment .card-body").each(function() {
			var height = $(this).height();
			if (height > max_height) {
				max_height += height;
			}
		});
		$(".carwash-appointment .card-body").height(max_height);

		// Populating Modal data on clicking appointment btn 
		$(".carwash-appointment .apt-btn").on("click", function() {
			$(".response-txt").html("");
			var pack_id = $(this).data('id');
			var pack_name = $(this).data('pack-name');
			var price = $(this).data('price');
			var time = $(this).data('time');
			$(".pack-id").val(pack_id);
			$(".pack-name").text(pack_name);
			$(".pack-price").text(price);
			$(".pack-time").text(time);
			$(".r_price").val(price);
			$(".r_time").val(time);

			$(".customer_name").val("");
			$(".email").val("");
			$(".apt_date").val("");
			$(".apt_time").val("");
		});

		// Handle Ajax request for Appointment
		$("#appointmentModal form").on("submit", function(e) {
			e.preventDefault();
			var data = "action=carwash_add_appointment&" + $(this).serialize();
			var close_btn = $("#appointmentModal .btn-exit");
			var submit_btn = $("#appointmentModal .btn-submit");
			var modal_main = $("#appointmentModal .modal-body .main");
			var modal_response = $("#appointmentModal .modal-body .response");
			$.ajax({
				url: carwash_info.ajax_url,
				type: 'POST',
				data: data,
				beforeSend: function() {
					close_btn.attr('disabled', 'disabled');
					submit_btn.attr('disabled', 'disabled');
					submit_btn.text('Processing...');
				},			
				success: function (response) {
					var obj = JSON.parse(response);
					modal_main.addClass('hidden');
					modal_response.removeClass('hidden');
					if (obj.success) {
						modal_response.html('<h6 class="text-success text-center py-5">'+obj.message+'</h6>');
					} else {
						modal_response.html('<h6 class="text-danger text-center py-5">'+obj.message+'</h6>');
					}
					setTimeout(function() {
						close_btn.removeAttr('disabled');
						submit_btn.removeAttr('disabled');
						submit_btn.text('Submit');
						close_btn.click();
						modal_main.removeClass('hidden');
						modal_response.addClass('hidden');
					}, 5000);
				}
			});
		});

		// Handle Ajax request for Login
		$("#loginModal form").on("submit", function(e) {
			e.preventDefault();
			var data = "action=carwash_front_login&" + $(this).serialize();
			var close_btn = $("#loginModal .btn-exit");
			var submit_btn = $("#loginModal .btn-submit");
			var modal_main = $("#loginModal .modal-body .main");
			var modal_response = $("#loginModal .modal-body .response");
			$.ajax({
				url: carwash_info.ajax_url,
				type: 'POST',
				data: data,
				beforeSend: function() {
					close_btn.attr('disabled', 'disabled');
					submit_btn.attr('disabled', 'disabled');
					submit_btn.text('Processing...');
				},			
				success: function (response) {
					var obj = JSON.parse(response);
					modal_main.addClass('hidden');
					modal_response.removeClass('hidden');
					if (obj.success) {
						modal_response.html('<h6 class="text-success text-center py-5">'+obj.message+'</h6>');
					} else {
						modal_response.html('<h6 class="text-danger text-center py-5">'+obj.message+'</h6>');
					}
					setTimeout(function() {
						close_btn.removeAttr('disabled');
						submit_btn.removeAttr('disabled');
						submit_btn.text('Submit');
						close_btn.click();
						modal_main.removeClass('hidden');
						modal_response.addClass('hidden');
						if (obj.success) {
							location.reload();
						}
					}, 3500);
				}
			});
		});

		// Handle Ajax request for Registration
		$("#registerModal form").on("submit", function(e) {
			e.preventDefault();
			var data = "action=carwash_front_registration&" + $(this).serialize();
			var close_btn = $("#registerModal .btn-exit");
			var submit_btn = $("#registerModal .btn-submit");
			var modal_main = $("#registerModal .modal-body .main");
			var modal_response = $("#registerModal .modal-body .response");
			$.ajax({
				url: carwash_info.ajax_url,
				type: 'POST',
				data: data,
				beforeSend: function() {
					close_btn.attr('disabled', 'disabled');
					submit_btn.attr('disabled', 'disabled');
					submit_btn.text('Processing...');
				},			
				success: function (response) {
					var obj = JSON.parse(response);
					modal_main.addClass('hidden');
					modal_response.removeClass('hidden');
					if (obj.success) {
						modal_response.html('<h6 class="text-success text-center py-5">'+obj.message+'</h6>');
					} else {
						modal_response.html('<h6 class="text-danger text-center py-5">'+obj.message+'</h6>');
					}
					setTimeout(function() {
						close_btn.removeAttr('disabled');
						submit_btn.removeAttr('disabled');
						submit_btn.text('Submit');
						close_btn.click();
						modal_main.removeClass('hidden');
						modal_response.addClass('hidden');
						if (obj.success) {
							location.reload();
						}
					}, 3500);
				}
			});
		});
	});
})(jQuery);
