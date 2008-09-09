<?php
/*
Plugin Name: TwitterCounter
Version:     1.0
Plugin URI:  http://ajaydsouza.com/wordpress/plugins/twittercounter/
Description: Integrate TwitterCounter.com badges on your blog to display the number of followers you have on Twitter
Author:      Ajay D'Souza
Author URI:  http://ajaydsouza.com/
*/

if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

function ald_tc_init() {
     load_plugin_textdomain('myald_tc_plugin', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)));
}
add_action('init', 'ald_tc_init');

define('ALD_TC_DIR', dirname(__FILE__));

/*********************************************************************
*				Main Function (Do not edit)							*
********************************************************************/
function ald_tc()
{
	$tc_settings = tc_read_options();
	
	if($tc_settings[username]=='')
	{
		$str = __('Please visit WP-Admin &gt; Settings &gt; TwitterCounter and enter your Twitter username','ald_tc_plugin');
	}
	else
	{
		$str = '<a href="http://twittercounter.com/?username=';
		$str .= $tc_settings[username];
		$str .= '" title="';
		$str .= __('TwitterCounter for @','ald_tc_plugin');
		$str .= '"><img src="http://twittercounter.com/counter/?username=';
		$str .= $tc_settings[username];
		if($tc_settings[style]!='')
		{
			$str .= '&amp;style=';
			$str .= $tc_settings[style];
		}
		$str .= '" width="88" height="26" style="border:none;" alt="';
		$str .= __('TwitterCounter for @','ald_tc_plugin');
		$str .= '" /></a>';
	}
	
	return $str;
}

function tc_read_options() 
{
	if(!is_array(get_option('ald_tc_settings')))
	{
		$tc_settings = tc_default_options();
		update_option('ald_tc_settings', $tc_settings);
	}
	else
	{
		$tc_settings = get_option('ald_tc_settings');
	}
	return $tc_settings;
}

// Add an action called echo_tc so that it can be called using do_action('echo_tc');
add_action('echo_tc', 'echo_tc_function');
function echo_tc_function() {
		echo ald_tc();
}

// Create a WordPress Widget
function widget_ald_tc($args) {	
	extract($args); // extracts before_widget,before_title,after_title,after_widget
	echo $before_widget;
	echo $before_title.'TwitterCounter'.$after_title;
	echo ald_tc();
	echo $after_widget;
}

function init_ald_tc(){
	register_sidebar_widget(__('TwitterCounter'), 'widget_ald_tc');
}
 add_action("plugins_loaded", "init_ald_tc");
 
// This function adds an Options page in WP Admin
if (is_admin() || strstr($_SERVER['PHP_SELF'], 'wp-admin/')) {
	require_once(ALD_TC_DIR . "/admin.inc.php");
}


?>