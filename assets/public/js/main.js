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
			$(".r_time").val(time);

			$(".customer_name").val("");
			$(".email").val("");
			$(".apt_date").val("");
			$(".apt_time").val("");
		});
    });
})(jQuery);
