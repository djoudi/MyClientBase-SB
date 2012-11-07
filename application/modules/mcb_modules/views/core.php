<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3><?php echo $this->lang->line('core_modules'); ?></h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;">

				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('description'); ?></th>
					<th scope="col"><?php echo $this->lang->line('version'); ?></th>
					<th scope="col"><?php echo $this->lang->line('status'); ?></th>
					<th scope="col" class="last"  style="text-align: center;"><?php echo $this->lang->line('actions'); ?></th>
				</tr>

				<?php
					$counter = 1; 
					foreach ($modules as $module) { 
					$counter ++;
					$color='';
					if($counter % 2) $color='style="background-color: #f3f3f3;"'; 
				?>

				<tr valign="top" <?php echo $color; ?>>
					<td class="first"><b><?php if ($module->module_enabled) { echo anchor($module->module_path, $module->module_name); } else { echo $module->module_name; } ?></b></td>
					<td><?php echo $module->module_description; ?></td>
					<td>
						<?php if($module->module_version < $module->module_available_version) {
							echo anchor('mcb_modules/upgrade/' . $module->module_path, $module->module_version . ' - ' . $this->lang->line('upgrade') . ' (' . $module->module_available_version .')', array('style'=>'color: red; font-weight: bold;'));
						}
						else {
							echo $module->module_version;
						} ?>
					</td>
					<td><?php 
						if($module->module_enabled) {
							echo $this->lang->line('enabled');
						} else {
							echo $this->lang->line('disabled');
						}
					?></td>
					<td class="last" style="text-align: center;">
					<?php 
					if($module->module_change_status == "1") {
						if($module->module_enabled) {
							echo anchor('mcb_modules/disable/' . $module->module_path, icon('delete'));						
						} else {
							echo anchor('mcb_modules/enable/' . $module->module_path, icon('check'));
						}
					} else {
						echo '--';
					} 
					?></td>
				</tr>

				<?php } ?>

			</table>

		</div>

	</div>

</div>
<?php echo $actions_panel; ?>
<?php $this->load->view('footer'); ?>