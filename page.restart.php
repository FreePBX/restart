<?php
/* $Id: */
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

//Both of these are used for switch on config.php
$display = isset($_REQUEST['display'])?$_REQUEST['display']:'restart';

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$restartlist = isset($_REQUEST['restartlist'])?$_REQUEST['restartlist']:'';

switch ($action) {
	case "restart":
		$restarted = false;
		if(is_array($restartlist) && sizeof($restartlist))  {
			foreach($restartlist as $device)  {
				restart_device($device);
			}
			$restarted = true;
		}
		break;
}
if(isset($restarted))  {
	if($restarted){
		$info = '<div class="well well-info">'._("Restart requests sent!").'</div>';
	}else{
		$info = '<div class="well well-warning">'._("Warning: The restart mechanism behavior is vendor specific.  Some vendors only restart the phone if there is a change to the phone configuration or if an updated firmware is available via tftp/ftp/http"). "</div>";
	}
}
$device_list = core_devices_list();
$info = isset($info)?$info:'<div class="well well-info">'._("Currently, only Aastra, Snom, Polycom, Grandstream and Cisco devices are supported.").'</div>';
?>
<div class="container-fluid">
	<h1><?php echo _('Restart Phones')?></h1>
	<?php echo $info ?>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="fpbx-container">
					<div class="display full-border">
						<form name='restart' action='' method='post'>
							<input type='hidden' name='action' value='restart'>
							<input type='hidden' name='display' value='restart'>
							<!--Device List-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="xtnlist"><?php echo _("Device List") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="xtnlist"></i>
												</div>
												<div class="col-md-9">
													<div class="input-group">
														<select class="form-control" id="xtnlist" multiple="multiple" name="restartlist[]">
															<?php
															$selected = isset($selected)?$selected:array();
															foreach ($device_list as $device) {
																if($ua = get_device_useragent($device[0]))  {
																	echo '<option value="'.$device[0].'" ';
																	if (array_search($device[0], $selected) !== false) echo ' selected="selected" ';
																	echo '>'.$device[0].' - '.$device[1].' - '.ucfirst($ua).' Device</option>';
																}
															}
															?>
														</select>
														<span class="input-group-addon" id="deviceaddon">
															<input type="button" name="Button" value="<?php echo _('SELECT ALL'); ?>" onclick="selectAll('xtnlist',true)" />
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="xtnlist-help" class="help-block fpbx-help-block"><?php echo _("Select Device(s) to restart.  Currently, only Aastra, Snom, Polycom, Grandstream and Cisco devices are supported.  All other devices will not show up in this list.  Click the \"Select All\" button to restart all supported devices.")?></span>
									</div>
								</div>
							</div>
							<!--END Device List-->
							<?php
										// implementation of module hook
										$module_hook = moduleHook::create();
										echo $module_hook->hookHtml;
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script language="javascript">
	<!-- hide script from older browsers

	function selectAll(selectBox,selectAll) {
		// have we been passed an ID
		if (typeof selectBox == "string") {
			selectBox = document.getElementById(selectBox);
		}
		// is the select box a multiple select box?
		if (selectBox.type == "select-multiple") {
			for (var i = 0; i < selectBox.options.length; i++) {
				selectBox.options[i].selected = selectAll;
			}
		}
	}
	// end of hiding script -->
	</script>
