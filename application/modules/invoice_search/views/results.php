<?php $this->load->view('header'); ?>

<?php echo modules::run('invoices/widgets/generate_dialog'); ?>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3><?php echo $this->lang->line('invoice_search'); ?>
			<span style="font-size: 60%;">
				<?php $this->load->view('btn_add', array('btn_name'=>'btn_add_invoice', 'btn_value'=>$this->lang->line('create_invoice'))); ?>
			</span>

		</h3>

		<div class="content toggle no_padding">

			<?php $this->load->view('invoices/invoice_table'); ?>

		</div>

	</div>

</div>

<?php $this->load->view('footer'); ?>