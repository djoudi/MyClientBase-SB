<?php $this->load->view('header'); ?>

<?php echo modules::run('payments/payment_widgets/generate_dialog'); ?>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3><?php echo $this->lang->line('account_deposits'); ?>
		<span style="font-size: 60%;">
		<?php $this->load->view('btn_add', array('btn_value'=>$this->lang->line('enter_deposit'))); ?>
		</span>
		</h3>

		<div class="content toggle no_padding">

			<?php $this->load->view('client_credit_table'); ?>

		</div>

	</div>

</div>

<?php $this->load->view('footer'); ?>