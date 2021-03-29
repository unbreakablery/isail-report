<?php
    require '../inc/config.php'; 
    require '../inc/views/template_head_start.php';
    require '../inc/views/template_head_end.php';  
?>

<!-- Error Content -->
<div class="content bg-white text-center pulldown overflow-hidden">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <!-- Error Titles -->
            <h1 class="font-s128 font-w700 text-city animated flipInX">404</h1>
            <h2 class="h3 font-w500 push-50 animated fadeInUp">We are sorry but the page you are looking for was not found..</h2>
            <!-- END Error Titles -->
        </div>
    </div>
</div>
<!-- END Error Content -->

<!-- Error Footer -->
<div class="content pulldown text-muted text-center font-w700">
    <a class="link-effect" href="/index.php">Click here to go Home <i class="si si-home push-5-l"></i></a>
</div>
<!-- END Error Footer -->

<?php require '../inc/views/template_footer_end.php'; ?>