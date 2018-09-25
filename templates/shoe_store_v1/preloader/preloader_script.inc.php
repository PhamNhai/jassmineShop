<?php 

echo "
<script>

(function ($){

    $(window).on('load', function () {
        var preloader = $('#preloader'),
            spinner   = preloader.find('.os-spinner');
        spinner.fadeOut();
        preloader.delay(350).fadeOut('slow');
    });

})(jQuerOs);

</script>
";

?>