<?php
/*
 * Plugin Name: Form Builder
 * Plugin URI: http://www.zingiri.com/form-builder
 * Description: Create amazing web forms with ease.
 * Author: Zingiri
 * Version: 3.0.0
 * Author URI: http://www.zingiri.com/
 */
define("FORM_VERSION", form_version());

add_action("init", "form_init");
add_action('admin_head', 'form_admin_header');
add_action('wp_head', 'form_header');
add_action('admin_notices', 'form_admin_notices');
add_filter('the_content', 'form_content', 10, 3);

register_deactivation_hook(__FILE__, 'form_deactivate');
register_uninstall_hook(__FILE__, 'form_uninstall');

$formRegions['us1']=array('Dallas, US (1)','formbuilder3.us1.zingiri.net');
$formRegions['us2']=array('Dallas, US (2)','formbuilder3.us2.zingiri.net');
$formRegions['eu1']=array('London, UK (1)','formbuilder3.eu1.zingiri.net');
@include (dirname(__FILE__) . '/regions.php');

require_once (dirname(__FILE__) . '/includes/shared.inc.php');
require_once (dirname(__FILE__) . '/includes/http.class.php');
require_once (dirname(__FILE__) . '/controlpanel.php');

function form_admin_notices() {
	global $form;
	$errors=array();
	$warnings=array();
	$files=array();
	$dirs=array();
	
	if (isset($form['output']['warnings']) && is_array($form['output']['warnings']) && (count($form['output']['warnings']) > 0)) {
		$warnings=$form['output']['warnings'];
	}
	if (isset($form['output']['errors']) && is_array($form['output']['errors']) && (count($form['output']['errors']) > 0)) {
		$errors=$form['output']['errors'];
	}
	$upload=wp_upload_dir();
	if (session_save_path() && !is_writable(session_save_path())) $errors[]='PHP sessions are not properly configured on your server, the sessions save path ' . session_save_path() . ' is not writable.';
	if ($upload['error']) $errors[]=$upload['error'];
	if (phpversion() < '5') $warnings[]="You are running PHP version " . phpversion() . ". We recommend you upgrade to PHP 5.3 or higher.";
	if (ini_get("zend.ze1_compatibility_mode")) $warnings[]="You are running PHP in PHP 4 compatibility mode. We recommend you turn this option off.";
	if (!function_exists('curl_init')) $errors[]="You need to have cURL installed. Contact your hosting provider to do so.";
	
	if (count($warnings) > 0) {
		echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>";
		foreach ($warnings as $message)
			echo 'Form Builder: ' . $message . '<br />';
		echo "</strong> " . "</p></div>";
	}
	if (count($errors) > 0) {
		echo "<div id='zing-warning' style='background-color:pink' class='updated fade'><p><strong>";
		foreach ($errors as $message)
			echo 'Form Builder:' . $message . '<br />';
		echo "</strong> " . "</p></div>";
	}
	
	return array('errors' => $errors,'warnings' => $warnings);
}

function form_deactivate() {
	form_output('deactivate');
}

function form_uninstall() {
	form_output('uninstall');
	
	$form_options=form_options();
	
	delete_option('form_log'); // legacy
	foreach ($form_options as $value) {
		delete_option($value['id']);
	}
	delete_option("form_ftp_user"); // legacy
	delete_option("form_ftp_password"); // legacy
	delete_option("form_version");
	delete_option('form-support-us');
	delete_option('form_debug'); // legacy
}

function form_content($content) {
	global $form;
	
	if (preg_match_all('/\[form(.*)\]/', $content, $matches)) {
		$pg=isset($_REQUEST['zfaces']) ? $_REQUEST['zfaces'] : 'form';
		$postVars=array();
		if (!isset($_POST['formid']) && !isset($_POST['form']) && is_numeric($matches[1][0])) $postVars['formid']=$matches[1][0];
		if (!isset($_POST['formid']) && !isset($_POST['form']) && !is_numeric($matches[1][0])) $postVars['form']=trim($matches[1][0]);
		if (!isset($_POST['action'])) $postVars['action']='add';
		form_output($pg, $postVars);
		$output='<div id="form" class="aphps">';
		$output.=$form['output']['body'];
		$output.='</div>';
		$content=str_replace($matches[0][0], $output, $content);
	}
	return $content;
}

function form_output($form_to_include='', $postVars=array()) {
	global $post, $form;
	global $wpdb;
	global $wordpressPageName;
	global $form_loaded;
	
	list($http, $reSubmit)=form_http($form_to_include);
	form_log('Notification', 'Call: ' . $http);
	//echo '<br />' . $http . '<br />';
	$news=new formHttpRequest($http, 'form');
	$news->reSubmit=$reSubmit;
	$news->noErrors=true;
	$news->post=array_merge($news->post, $postVars);
	
	if (!$news->curlInstalled()) {
		form_log('Error', 'CURL not installed');
		return "cURL not installed";
	} elseif (!$news->live()) {
		form_log('Error', 'A HTTP Error occured');
		return "A HTTP Error occured";
	} else {
		$buffer=$news->DownloadToString();
		if ($news->error && is_admin()) echo $news->errorMessage;
		elseif ($news->error) echo 'The service is currently unavailable';
		else {
			if ($news->headers['content-type'] == 'text/csv') {
				while ( count(ob_get_status(true)) > 0 )
					ob_end_clean();
				header("Content-type: text/csv");
				header("Cache-Control: no-store, no-cache");
				header('Content-Disposition: ' . $news->headers['content-disposition']);
				echo $buffer;
				// $form['output']=json_decode($buffer, true);
				// echo $form['output']['data'];
				die();
			} else {
				$form['output']=json_decode($buffer, true);
				if (!$form['output']) {
					$form['output']['body']=$buffer;
					$form['output']['head']='';
				} else {
					if (isset($form['output']['http_referer'])) $_SESSION['form']['http_referer']=$form['output']['http_referer'];
				}
			}
		}
	}
}

function form_header() {
	echo '<script type="text/javascript">';
	echo "var formPageurl='" . form_home() . "';";
	echo "var ajaxurl = '" . admin_url('admin-ajax.php') . "';";
	echo "var aphpsAjaxURL=ajaxurl+'?action=aphps_ajax&form=';";
	echo "var aphpsURL='" . form_url(false) . 'lib/fwkfor/' . "';";
	echo "var wsCms='gn';";
	echo '</script>';
	echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url(__FILE__) . 'css/client.css" media="screen" />';
	echo '<link rel="stylesheet" type="text/css" href="' . form_url(false) . 'lib/fwkfor/css/integrated_view.css" media="screen" />';
}

function form_admin_header() {
	global $wp_version;
	
	echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url(__FILE__) . 'css/admin.css" media="screen" />';
}

function form_http($page="index") {
	global $current_user;
	
	$vars="";
	$http=form_url(true) . '?zfaces=' . $page;
	$and="&";
	if (count($_GET) > 0) {
		foreach ($_GET as $n => $v) {
			if (!in_array($n, array('page'))) {
				$vars.=$and . $n . '=' . urlencode($v);
				$and="&";
			}
		}
	}
	
	$and="&";
	
	$wp=array();
	if (is_user_logged_in()) {
		$wp['login']=$current_user->data->user_login;
		$wp['email']=$current_user->data->user_email;
		$wp['first_name']=isset($current_user->data->first_name) ? $current_user->data->first_name : $current_user->data->display_name;
		$wp['last_name']=isset($current_user->data->last_name) ? $current_user->data->last_name : $current_user->data->display_name;
		$wp['roles']=$current_user->roles;
	}
	$wp['lic']=get_option('form_lic');
	$wp['siteurl']=home_url();
	$wp['sitename']=get_bloginfo('name');
	$wp['pluginurl']=plugin_dir_url(__FILE__);
	if (is_admin()) {
		$wp['mode']='b';
		$wp['pageurl']='admin.php?page=form&';
	} else {
		$wp['mode']='f';
		$wp['pageurl']=form_home();
	}
	$wp['time_format']=get_option('time_format');
	$wp['admin_email']=get_option('admin_email');
	$wp['key']=get_option('form_key');
	$wp['lang']=get_bloginfo('language');
	$wp['client_version']=FORM_VERSION;
	$vars.=$and . 'wp=' . urlencode(base64_encode(json_encode($wp)));
	
	if (isset($_SESSION['form']['http_referer'])) $vars.='&http_referer=' . urlencode($_SESSION['form']['http_referer']);
	
	if ($vars) $http.=$vars;
	return array($http,array('wp' => urlencode(base64_encode(json_encode($wp)))));
}

function form_home() {
	global $post, $page_id;
	
	$pageID=$page_id;
	
	if (get_option('permalink_structure')) {
		$homePage=get_option('home');
		$wordpressPageName=get_permalink($pageID);
		$wordpressPageName=str_replace($homePage, "", $wordpressPageName);
		$home=$homePage . $wordpressPageName;
		if (substr($home, -1) != '/') $home.='/';
		$home.='?';
	} else {
		$home=get_option('home') . '/?page_id=' . $pageID . '&';
	}
	
	return $home;
}

function form_ajax() {
	global $form;
	if (is_admin() && (isset($_GET['zf']) || isset($_REQUEST['zfaces']))) {
		$pg=isset($_GET['zf']) ? $_GET['zf'] : '';
		form_output($pg);
	}
}

function form_init() {
	global $wp_version;
	
	if (get_option("form_version") || (isset($_REQUEST['page']) && ($_REQUEST['page'] == 'form') && isset($_REQUEST['action']) && ('install' == $_REQUEST['action']))) {
		ob_start();
		session_start();
		wp_enqueue_script('jquery');
		if (is_admin() && isset($_REQUEST['page']) && ($_REQUEST['page'] == 'form')) {
			form_output('saas_check');
			//global $form;print_r($form);die();
		} else {
			wp_enqueue_script(array('jquery-ui-core','jquery-ui-datepicker','jquery-ui-tabs'));
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/flick/jquery-ui.css');
		}
	}
}

function form_log($type=0, $msg='', $filename="", $linenum=0) {
	if (get_option('form_debug')) {
		if (is_array($msg)) $msg=print_r($msg, true);
		$v=get_option('form_log');
		if (!is_array($v)) $v=array();
		array_unshift($v, array(time(),$type,$msg));
		update_option('form_log', $v);
	}
}

// URL end point for web services stored on Zingiri servers
function form_url($endpoint=true) {
	global $formRegions;
	$region=get_option('form_region');
	$url='http://'.$formRegions[$region][1].'/';
	if ($endpoint) $url.='index.php';
	return $url;
}

add_action('wp_ajax_aphps_ajax', 'aphps_ajax_callback');
add_action('wp_ajax_nopriv_aphps_ajax', 'aphps_ajax_callback');

function aphps_ajax_callback() {
	list($http, $reSubmit)=form_http('ajax');
	$news=new formHttpRequest($http, 'form');
	$news->reSubmit=$reSubmit;
	$news->noErrors=true;
	
	if (!$news->curlInstalled()) {
		form_log('Error', 'CURL not installed');
	} elseif (!$news->live()) {
		form_log('Error', 'A HTTP Error occured');
	} else {
		$buffer=$news->DownloadToString();
		$form['output']=json_decode($buffer, true);
		if (!$form['output']) {
			$form['output']['body']=$buffer;
			$form['output']['head']='';
		}
		if (isset($form['output']['head']) && $form['output']['head']) $_SESSION['form']['head']=$form['output']['head'];
		if (isset($form['output']['body'])) echo $form['output']['body'];
		else echo $buffer;
	}
	die(); // this is required to return a proper result
}

function form_version($tag='Stable tag') {
	$trunk_readme=file(dirname(__FILE__) . '/readme.txt');
	foreach ($trunk_readme as $i => $line)
		if (substr_count($line, $tag . ': ') > 0) return trim(substr($line, strpos($line, $tag . ': ') + strlen($tag) + 2));
	return NULL;
}
