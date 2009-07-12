<?php
/*
Plugin Name: TwitterCounter
Version:     1.3.2
Plugin URI:  http://ajaydsouza.com/wordpress/plugins/twittercounter/
Description: Integrate TwitterCounter.com badges on your blog to display the number of followers you have on Twitter
Author:      Ajay D'Souza
Author URI:  http://ajaydsouza.com/
*/

if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

define('ALD_TC_DIR', dirname(__FILE__));
define('TC_LOCAL_NAME', 'twittercounter');

function ald_tc_init() {
	//* Begin Localization Code */
	$tc_localizationName = TC_LOCAL_NAME;
	$tc_comments_locale = get_locale();
	$tc_comments_mofile = ALD_tc_DIR . "/languages/" . $tc_localizationName . "-". $tc_comments_locale.".mo";
	load_textdomain($tc_localizationName, $tc_comments_mofile);
	//* End Localization Code */
}
add_action('init', 'ald_tc_init');

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
// Guess the location
$twittercounter_path = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__));
$twittercounter_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));

/*********************************************************************
*				Main Function (Do not edit)							*
********************************************************************/
function ald_tc()
{
	$tc_settings = tc_read_options();
	
	if($tc_settings[username]=='')
	{
		$str = __('Please visit WP-Admin &gt; Settings &gt; TwitterCounter and enter your Twitter username',TC_LOCAL_NAME);
	}
	else
	{
		$str = '<script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=';
		$str .= $tc_settings[username];
		if($tc_settings[style]!='')
		{
			$str .= '&amp;style=';
			$str .= $tc_settings[style];
		}
		$str .= '"></script>';
	}
	
	return $str;
}
// Add an action called echo_tc so that it can be called using do_action('echo_tc');
add_action('echo_tc', 'echo_tc_function');
function echo_tc_function() {
	echo ald_tc();
}

/* Function for Twitter Remote */
function ald_tr()
{
	$tc_settings = tc_read_options();
	
	if($tc_settings[username]=='')
	{
		$str = __('Please visit WP-Admin &gt; Settings &gt; TwitterCounter and enter your Twitter username',TC_LOCAL_NAME);
	}
	else
	{
		$str = '<script type="text/javascript" language="javascript" src="http://twittercounter.com/remote/?username_owner=';
		$str .= $tc_settings[username];
		$str .= '&amp;users_id=';
		$str .= $tc_settings[users_id];
		$str .= '&amp;width=';
		$str .= $tc_settings[width];
		$str .= '&amp;nr_show=';
		$str .= $tc_settings[nr_show];
		$str .= '&amp;hr_color=';
		$str .= $tc_settings[hr_color];
		$str .= '&amp;a_color=';
		$str .= $tc_settings[a_color];
		$str .= '"></script>';
	}
	
	$str .= '<br /><small><strong style="color:#'.$tc_settings[hr_color].'">Get</strong> <a href="http://ajaydsouza.com/wordpress/plugins/twittercounter/" style="font-weight:bold;color:#'.$tc_settings[a_color].'">TwitterCounter WordPress Plugin</a></small>';
	return $str;
}
// Add an action called echo_twitter_remote so that it can be called using do_action('echo_twitter_remote');
add_action('echo_twitter_remote', 'echo_tr_function');
function echo_tr_function() {
	echo ald_tr();
}

// Default Options
function tc_default_options() {
	$tc_settings = 	Array (
						username => '',		// Twitter Username
						style => '',		// TwitterCounter style
						users_id => '',		// TwitterCounter style
						a_color => '709cb2',		// Twitter Remote Color 1
						hr_color => 'cccccc',		// Twitter Remote Color 2
						nr_show => '6',		// Twitter Remote Number of Rows
						width => '180',		// Twitter Remote Width
						);
	
	return $tc_settings;
}

// Function to read options from the database
function tc_read_options() {

	$tc_settings_changed = false;
	
	$defaults = tc_default_options();
	
	$tc_settings = array_map('stripslashes',(array)get_option('ald_tc_settings'));
	unset($tc_settings[0]); // produced by the (array) casting when there's nothing in the DB
	
	foreach ($defaults as $k=>$v) {
		if (!isset($tc_settings[$k]))
			$tc_settings[$k] = $v;
		$tc_settings_changed = true;	
	}
	if ($tc_settings_changed == true)
		update_option('ald_tc_settings', $tc_settings);
	
	return $tc_settings;

}

/* This function reads TwitterCounter API */
function twittercounter_api($username= 'ajaydsouza',$output= 'php',$results= '3') {

	$ch_url = 'http://twittercounter.com/api/?username='.$username.'&output='.$output.'&results='.$results;

	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$ch_url);
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	$buffer = curl_exec($curl_handle);
	curl_close($curl_handle);

	return $buffer;
}

// Create a WordPress Widget
function widget_ald_tc($args) {	
	extract($args); // extracts before_widget,before_title,after_title,after_widget
	echo $before_widget;
	echo $before_title.'TwitterCounter'.$after_title;
	echo '<div style="text-align:center">'.ald_tc().'</div>';
	echo $after_widget;
}

function widget_ald_tr($args) {	
	extract($args); // extracts before_widget,before_title,after_title,after_widget
	echo $before_widget;
	echo $before_title.'Twitter Remote'.$after_title;
	echo ald_tr();
	echo $after_widget;
}

function init_ald_tc(){
	register_sidebar_widget('TwitterCounter', 'widget_ald_tc');
	register_sidebar_widget('Twitter Remote', 'widget_ald_tr');
}
add_action("plugins_loaded", "init_ald_tc");
 
// This function adds an Options page in WP Admin
if (is_admin() || strstr($_SERVER['PHP_SELF'], 'wp-admin/')) {
	require_once(ALD_TC_DIR . "/admin.inc.php");
}


?>