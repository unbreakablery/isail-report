<?php
/**
 * template_footer_start.php
 *
 * Author: Anna
 *
 * All vital JS scripts are included here
 *
 */
?>

<!-- iSAIL_UI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/jquery.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/bootstrap.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/jquery.slimscroll.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/jquery.scrollLock.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/jquery.appear.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/jquery.countTo.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/jquery.placeholder.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/core/js.cookie.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/app.js"></script>

<script>
    $("#logout").click(function() {
        window.localStorage.removeItem('aggregate_form');
        window.localStorage.removeItem('player_report_form');
        window.localStorage.removeItem('comparison_report_form');
        window.localStorage.removeItem('comparison_model_report_form');
        window.localStorage.removeItem('coaching_report_form');
        window.localStorage.removeItem('progression_report_form');
        window.localStorage.removeItem('class_create_form');
        window.localStorage.removeItem('gkn_create_form');
    });
</script>
