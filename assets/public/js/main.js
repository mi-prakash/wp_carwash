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

    });
})(jQuery);
