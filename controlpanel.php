<?php
function form_options() {
	global $form_name,$form_shortname,$cc_login_type,$current_user;
	$form_name = "Form Builder";
	$form_shortname = "form";

	$form_options[] = array(  "name" => "Settings",
            "type" => "heading",
			"desc" => "This section customizes the way the Form Builder plugin works.");
	$form_options[] = array("name" => "API Key",
			"desc" => 'This is your auto-generated API key, it is uniquely linked to your web site, make sure to keep it in a safe place.',
			"id" => $form_shortname."_key",
			"type" => "text");
	$form_options[] = array("name" => "License Key",
			"desc" => 'If you wish to make use of the <strong>Form Builder Pro</strong> features, enter your license key here. Further details about Form Builder Pro will be announced soon.',
			"id" => $form_shortname."_lic",
			"type" => "text");
	$form_options[] = array(	"name" => "Debug Mode",
			"desc" => "If you have problems with the plugin, activate the debug mode to generate a debug log for our support team",
			"id" => $form_shortname."_debug",
			"type" => "checkbox");

	return $form_options;
}

function form_add_admin() {

	global $form_name, $form_shortname, $form;

	$form_options=form_options();

	if (isset($_GET['page']) && ($_GET['page'] == "form")) {

		if ( isset($_REQUEST['action']) && 'install' == $_REQUEST['action'] ) {
			delete_option('form_log');
			foreach ($form_options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) {
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				} else { delete_option( $value['id'] );
				}
			}
			header("Location: admin.php?page=form&installed=true");
			die;
		}
	}

	add_menu_page($form_name, $form_name, 'manage_options', 'form','form_main');
	add_submenu_page('form', $form_name.' - Setup', 'Setup', 'manage_options', 'form', 'form_main');
	add_submenu_page('form', $form_name.' - Form Builder', 'Form Builder', 'manage_options', 'form&zf=form_edit', 'form_main');
}

function form_main() {
	global $form;

	if (!isset($_GET['zf'])) return form_admin();

	echo '<div class="wrap">';
	echo '<div id="form" style="position:relative;float:left;width:75%">';
	if (isset($form['output']['messages']) && is_array($form['output']['messages']) && (count($form['output']['messages']) > 0)) {
		echo '<div class="error">';
		foreach ($form['output']['messages'] as $msg) {
			echo $msg.'<br />';
		}
		echo '</div>';
	}
	if (isset($form['output']['body'])) echo $form['output']['body'];
	echo '</div>';
	require(dirname(__FILE__).'/includes/support-us.inc.php');
	zing_support_us('form','form','form',FORM_VERSION);
	echo '</div>';
}

function form_admin() {

	global $form_name, $form_shortname;

	$controlpanelOptions=form_options();

	if ( isset($_REQUEST['install']) ) echo '<div id="message" class="updated fade"><p><strong>'.$form_name.' settings updated.</strong></p></div>';
	if ( isset($_REQUEST['error']) ) echo '<div id="message" class="updated fade"><p>The following error occured: <strong>'.$_REQUEST['error'].'</strong></p></div>';

	?>
<div class="wrap">
<div id="cc-left" style="position: relative; float: left; width: 80%">
<h2><b><?php echo $form_name; ?></b></h2>

	<?php
	$form_version=get_option("form_version");
	$submit='Update';
	?>
<form method="post"><?php require(dirname(__FILE__).'/includes/cpedit.inc.php')?>

<p class="submit"><input name="install" type="submit" value="<?php echo $submit;?>" /> <input
	type="hidden" name="action" value="install"
/></p>
</form>
<hr />
	<?php
	if ($form_version && get_option('form_debug')) {
		echo '<h2 style="color: green;">Debug log</h2>';
		echo '<textarea rows=10 cols=80>';
		$r=get_option('form_log');
		if ($r) {
			$v=$r;
			foreach ($v as $m) {
				echo date('H:i:s',$m[0]).' '.$m[1].chr(13).chr(10);
				echo $m[2].chr(13).chr(10);
			}
		}
		echo '</textarea><hr />';
	}
	?>
If you need help, please check out our forums at <a href="http://forums.zingiri.com" target="_blank">forums.zingiri.net</a>.	
</div>
<!-- end cc-left --> <?php
require(dirname(__FILE__).'/includes/support-us.inc.php');
zing_support_us('form','form','form',FORM_VERSION);
}
add_action('admin_menu', 'form_add_admin'); ?>