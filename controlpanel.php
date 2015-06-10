<?php

function form_options() {
	global $form_name, $form_shortname, $cc_login_type, $current_user, $wp_roles, $formRegions;
	$form_name="Form Builder";
	$form_shortname="form";
	
	if (!get_option('form_region')) {
		if (get_option('form_key')) update_option('form_region', 'us2');
		else update_option('form_region', zingiri::findNearestServer($formRegions));
	}
	if (!get_option('form_key')) update_option('form_key', zingiri::create_api_key('formbuilder'));
	
	$form_options[]=array("name" => "Settings","type" => "heading","desc" => "This section customizes the way the Form Builder plugin works.");
	$form_options[]=array("name" => "API Key","desc" => 'This plugin uses remote web services to provide mailing list functionality. This API key has been automatically generated for you. Once you click on Install, the API key will create an account on our servers allowing the plugin to access the remote web services.<br />The API key uniquely identifes you so please make sure to keep it in a safe place.',"id" => $form_shortname . "_key","type" => "text");
	$form_options[]=array("name" => "License Key","desc" => 'If you wish to make use of the <strong>Form Builder Pro or Expert</strong> features, enter your license key here. Further details about Form Builder Pro and Expert can be found <a href="http://www.zingiri.com/form-builder/pricing-signup/" target="_blank">here</a>.',"id" => $form_shortname . "_lic","type" => "text");
	
	$form_options[]=array("name" => "Region","desc" => "Region you are connected to.","value" => $formRegions[get_option("form_region")][0],"type" => "info");
	
	$form_options[]=array("name" => "Before you install","type" => "heading","desc" => '<div style="text-decoration:underline;display:inline;font-weight:bold">IMPORTANT:</div> Zingiri Form Builder uses web services stored on Zingiri\'s servers. In doing so, data entered via the forms you create is collected and stored on our servers. 
					Your admin email address, together with the API key listed here above is also recored as as a unique identifier for your account on Zingiri\'s servers.
					This data remains your property and Zingiri will not use nor make available for use any of this information without your permission.
					The data is stored securely in a database and is only accessible to persons you have authorized to use Zingiri Form Builder.
					We have a very strict <a href="http://www.zingiri.com/privacy-policy/" target="_blank">privacy policy</a> as well as <a href="http://www.zingiri.com/terms/" target="_blank">terms & conditions</a> governing data stored on our servers.
					<div style="font-weight:bold;display:inline">By installing this plugin you accept these terms & conditions.</div>');
	
	return $form_options;
}

function form_admin_menu() {
	global $form_name, $form_shortname, $form;
	
	$form_options=form_options();
	
	if (isset($_GET['page']) && ($_GET['page'] == "form")) {
		if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'install')) {
			delete_option('form_log');
			update_option("form_version", FORM_VERSION);
			foreach ($form_options as $value) {
				if (isset($value['id'])) {
					if (isset($_REQUEST[$value['id']])) update_option($value['id'], $_REQUEST[$value['id']]);
					else delete_option($value['id']);
				}
			}
			header("Location: admin.php?page=form&installed=true");
			die();
		}
	}
	
	add_menu_page($form_name, $form_name, 'manage_options', 'form', 'form_main');
	add_submenu_page('form', $form_name . ' - Setup', 'Setup', 'manage_options', 'form', 'form_main');
}

function form_main() {
	global $form;
	
	require (dirname(__FILE__) . '/includes/support-us.inc.php');
	if (!isset($_GET['zf']) && !isset($_REQUEST['zfaces'])) return form_admin();
	
	echo '<div class="wrap">';
	echo '<div id="form" style="width:100%;min-height:400px;" class="aphps">';
	if (isset($form['output']['messages']) && is_array($form['output']['messages']) && (count($form['output']['messages']) > 0)) {
		echo '<div class="error">';
		foreach ($form['output']['messages'] as $msg) {
			echo 'Form Builder: ' . $msg . '<br />';
		}
		echo '</div>';
	}
	if (isset($form['output']['body'])) echo $form['output']['body'];
	echo '</div>';
	echo '<div style="clear:both"></div>';
	echo '<hr />';
	echo 'If you need help, please check out our <a href="http://wordpress.org/support/plugin/form" target="_blank">forums</a>.';
	zing_support_us_bottom('form-builder', 'form', 'form', FORM_VERSION, false, false, 'Zingiri Form Builder');
	echo '</div>';
}

function form_admin() {
	global $form_name, $form_shortname;
	
	require (dirname(__FILE__) . '/includes/support-us.inc.php');
	
	$controlpanelOptions=form_options();
	
	if (isset($_REQUEST['install'])) echo '<div id="message" class="updated fade"><p><strong>' . $form_name . ' settings updated.</strong></p></div>';
	if (isset($_REQUEST['error'])) echo '<div id="message" class="updated fade"><p>The following error occured: <strong>' . zingiri::form_sanitize($_REQUEST['error']) . '</strong></p></div>';
	
	?>
<div class="wrap">
<?php zing_support_us_top('form-builder','form','form',FORM_VERSION,false,false,'Zingiri Form Builder');?>
	<div class="cc-left">
		<h2>
			<b><?php echo $form_name; ?> </b>
		</h2>
		<?php
	if (!get_option('form_version')) $submit='Install';
	else $submit='Update';
	?>
		<form method="post">
		<?php require(dirname(__FILE__).'/includes/cpedit.inc.php')?>

			<p class="submit">
				<input class="button" name="install" type="submit" value="<?php echo $submit;?>" /> <input
					type="hidden" name="action" value="install"
				/>
			</p>
		</form>
	</div>
	<?php
	if (get_option('form_version')) {
		echo '<div class="cc-right">';
		echo '<h3>Administration</h3>';
		echo '<p>To administer Form Builder, please login <a href="' . form_url(false) . 'index.php?apikey=' . get_option('form_key') . '" target="_blank">here</a>.</p>';
		echo '<p>You can login with your Wordpress user. If it is the first time you login, use password admin.</p>';
		echo '</div>';
	}
	require (dirname(__FILE__) . '/includes/help.inc.php');
	zing_support_us_bottom('form-builder', 'form', 'form', FORM_VERSION, false, false, 'Zingiri Form Builder');
}
add_action('admin_menu', 'form_admin_menu');
?>