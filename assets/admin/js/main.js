;(function($){
    $(document).ready(function () {
        var service_index = $('.select-service').length;
        $(".carwash_form.package_meta .add-more").on("click", function() {
            $(".clone-service .service-container").attr("id", "container-"+service_index);
            $(".clone-service label").attr("for", "carwash_service_ids-"+service_index);
            $(".clone-service select").attr("id", "carwash_service_ids-"+service_index);
            $(".clone-service .remove").attr("data-index", service_index);
            $("#carwash_service_ids-"+service_index).attr("name", "carwash_service_ids[]");
            var html = $(".clone-service").html();
            $(".added-services").append(html);

            $(".clone-service #carwash_service_ids-"+service_index).attr("name", "");
            service_index++;
        });

        $(".postbox").on("click", ".carwash_form.package_meta .remove", function() {
            var index = $(this).data('index');
            console.log(index);
            $("#container-"+index).remove();
        });

    });
})(jQuery);
