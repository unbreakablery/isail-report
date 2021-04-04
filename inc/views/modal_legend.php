<!-- Pop Out Modal -->
<div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Legend: In-Game Sales Model Mastery</h3>
                </div>
                <div class="block-content">
                    <div class="text-center push-20">
                        Colors indicate range of score:
                    </div>
                    <div class="progress bg-transparent">
                        <div class="progress-bar progress-bar-danger w-pt-10" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="text-left push-l-10-10 line-h-24">
                            <?php echo $common->getLegendColor('foundation'); ?> 
                            <?php echo $common->getLegendScoreRange('foundation'); ?>, 
                            <?php echo $common->getLegendLabel('foundation'); ?>. 
                            <?php echo $common->getLegendLevel('foundation'); ?>
                        </div>
                    </div>
                    <div class="progress bg-transparent">
                        <div class="progress-bar progress-bar-warning w-pt-10" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="text-left push-l-10-10 line-h-24">
                            <?php echo $common->getLegendColor('deep'); ?> 
                            <?php echo $common->getLegendScoreRange('deep'); ?>, 
                            <?php echo $common->getLegendLabel('deep'); ?>. 
                            <?php echo $common->getLegendLevel('deep'); ?>
                        </div>
                    </div>
                    <div class="progress bg-transparent">
                        <div class="progress-bar progress-bar-success w-pt-10" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="text-left push-l-10-10 line-h-24">
                            <?php echo $common->getLegendColor('advanced'); ?> 
                            <?php echo $common->getLegendScoreRange('advanced'); ?>, 
                            <?php echo $common->getLegendLabel('advanced'); ?>. 
                            <?php echo $common->getLegendLevel('advanced'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Pop Out Modal -->