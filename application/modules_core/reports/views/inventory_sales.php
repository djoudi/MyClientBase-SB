<?php $this->load->view('header'); ?>

<?php $this->load->view('jquery_date_picker'); ?>

<script type="text/javascript">

	$(document).ready(function() {

		url = '<?php echo site_url('reports/standardize_date'); ?>';

		$('#btn_submit').click(function() {

			include_closed_invoices = $('#include_closed_invoices').attr('checked');
			output_type = $('#output_type').val();
			client_id = $('#client_id').val();
			from_date = $('#from_date').val();
			to_date = $('#to_date').val();

			$.ajaxSetup({async:false});

			$.post(url, {date: from_date }, function(data) {
				ts_from_date = data;
			});

			$.post(url, {date: to_date }, function(data) {
				ts_to_date = data;
			});

			if (!ts_from_date) {
				ts_from_date = 0;
			}

			if (!ts_to_date) {
				ts_to_date = 0;
			}

			if (output_type == 'view') {

				$('#results').load('<?php echo site_url('reports/inventory_sales/jquery_display_results'); ?>' + '/' + output_type + '/' + '/' + ts_from_date + '/' + ts_to_date);

			}

			else {

				window.open('<?php echo site_url('reports/inventory_sales/jquery_display_results'); ?>' + '/' + output_type + '/' + ts_from_date + '/' + ts_to_date);

			}

		});

	});

</script>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('inventory_sales'); ?></h3>

		<div class="content toggle" style="min-height: 0px;">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

                <dl>
                    <dt><label><?php echo $this->lang->line('from_date'); ?>: </label></dt>
                    <dd><input type="text" name="from_date" id="from_date" class="datepicker" /></dd>
                </dl>

                <dl>
                    <dt><label><?php echo $this->lang->line('to_date'); ?>: </label></dt>
                    <dd><input type="text" name="to_date" id="to_date" class="datepicker" /></dd>
                </dl>

                <?php $this->load->view('partial_output_type'); ?>

				<input class="uibutton" style="float: right; margin-top: 10px; margin-right: 10px;" type="button" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save'); ?>" />

			</form>
			<div style="clear: both;">&nbsp;</div>
		</div>

	</div>

    <div class="section_wrapper">
        <div class="content toggle no_padding" id="results">
        </div>
    </div>

</div>

<?php $this->load->view('footer'); ?>