<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3><?php echo $this->lang->line('inventory_type_form'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<dl>
					<dt><label>* <?php echo $this->lang->line('inventory_type'); ?>: </label></dt>
					<dd><input type="text" name="inventory_type" id="inventory_type" value="<?php echo $this->mdl_inventory_types->form_value('inventory_type'); ?>" /></dd>
				</dl>

                <div style="clear: both;">&nbsp;</div>

				<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save'); ?>" />
				<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

			</form>

		</div>

	</div>

</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>