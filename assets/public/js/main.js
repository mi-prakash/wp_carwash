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

		// Handle Ajax request
		$("#appointmentModal form").on("submit", function(e) {
			e.preventDefault();
			var data = "action=carwash_add_appointment&" + $(this).serialize();
			var close_btn = $("#appointmentModal .btn-exit");
			var submit_btn = $("#appointmentModal .btn-submit");
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
					console.log(response);
					var obj = JSON.parse(response);
					if (obj.success) {
						$("#appointmentModal .modal-body").html('<h4 class="text-success text-center py-5">'+obj.message+'</h4>');
						setTimeout(function() {
							close_btn.removeAttr('disabled');
							submit_btn.removeAttr('disabled');
							submit_btn.text('Submit');
							close_btn.click();
						}, 5000);
					} else {
						$("#appointmentModal .modal-body").html('<h4 class="text-danger text-center py-5">'+obj.message+'</h4>');
						setTimeout(function() {
							close_btn.removeAttr('disabled');
							submit_btn.removeAttr('disabled');
							submit_btn.text('Submit');
							close_btn.click();
						}, 5000);
					}
				}
			});
		});
	});
})(jQuery);
