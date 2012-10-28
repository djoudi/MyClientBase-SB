<?php $this->load->view('header'); ?>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3><?php echo $this->lang->line('upload_invoice_logo'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data">

				<dl>
					<dt><label><?php echo $this->lang->line('select_file'); ?>: </label></dt>
					<dd><input type="file" name="userfile" size="20" /></dd>
				</dl>

                <div style="clear: both;">&nbsp;</div>

				<input type="submit" id="btn_submit" name="btn_upload_logo" value="<?php echo $this->lang->line('upload_invoice_logo'); ?>" />
				<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('footer'); ?>