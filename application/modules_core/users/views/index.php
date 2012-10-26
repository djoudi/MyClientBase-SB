<?php $this->load->view('header', array('header_insert'=>'contact/details_header')); ?>

<!-- $details contains details.tpl -->
<div class="grid_8" id="content_wrapper">
<?php echo $details; ?>
</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>