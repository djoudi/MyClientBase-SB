<?php $this->load->view('header'); ?>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3><?php echo $this->lang->line('no_invoices_for_payment'); ?></h3>

		<div class="content toggle no_padding">

			<p><?php echo $this->lang->line('no_invoices_for_payment'); ?></p>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar'); ?>

<?php $this->load->view('footer'); ?>