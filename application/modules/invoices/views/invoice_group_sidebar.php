<div class="section_wrapper" style="background-color: gray">

	<h3><?php echo $this->lang->line('main_actions'); ?></h3>

	<ul class="quicklinks content toggle">
		<li><?php echo anchor('invoices/invoice_groups', $this->lang->line('view_invoice_groups')); ?></li>
		<li class="last"><?php echo anchor('invoices/invoice_groups/form', $this->lang->line('add_invoice_group')); ?></li>
	</ul>

</div>
