<?php
/*
Plugin Name: TwitterCounter
Version:     1.6.2
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
	$tc_comments_mofile = ALD_TC_DIR . "/languages/" . $tc_localizationName . "-". $tc_comments_locale.".mo";
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
function ald_tc($style=false)
{
	$tc_settings = tc_read_options();
	if (empty($style)) $style = $tc_settings['style'];
	
	if($tc_settings['username']=='')
	{
		$str = __('Please visit WP-Admin &gt; Settings &gt; TwitterCounter and enter your Twitter username',TC_LOCAL_NAME);
	}
	else
	{
		if ($style=='custom') {
			$str = '<script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/'.$tc_settings['username'].'/'.$tc_settings['tc_hr_color'].'/'.$tc_settings['tc_bg_color'].'"></script>';
		} elseif ($style=='script_only') {
			$str = '<!-- Start of Twitter Counter Code -->';
			$str .= '<script type="text/javascript" language="javascript" src="http://twittercounter.com/widget/index.php?username='.$tc_settings['username'].'"></script><noscript><a href="http://twittercounter.com/'.$tc_settings['username'].'">@'.$tc_settings['username'].' on Twitter Counter</a></noscript>';
			$str .= '<!-- End of Twitter Counter Code --> ';
		} else {
			$str = '<script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=';
			$str .= $tc_settings['username'];
			if($style!='')
			{
				$str .= '&amp;style=';
				$str .= $style;
			}
			$str .= '"></script>';
		}
	}
	
	return $str;
}
// Add an action called echo_tc so that it can be called using do_action('echo_tc');
function echo_tc_function() {
	echo ald_tc();
}
add_action('echo_tc', 'echo_tc_function');

/* Function for Twitter Widget */
function ald_tr($width='180', $nr_show='6', $hr_color='709CB2', $a_color='709CB2', $bg_color='ffffff')
//function ald_tr($width, $nr_show, $hr_color, $a_color, $bg_color)
{
	$tc_settings = tc_read_options();
	if (empty($width)) $style = $tc_settings['width'];
	if (empty($nr_show)) $nr_show = $tc_settings['nr_show'];
	if (empty($hr_color)) $hr_color = $tc_settings['hr_color'];
	if (empty($a_color)) $a_color = $tc_settings['a_color'];
	if (empty($bg_color)) $bg_color = $tc_settings['bg_color'];
	
	if ($tc_settings['username']=='') {
		$str = __('Please visit WP-Admin &gt; Settings &gt; TwitterCounter and enter your Twitter username',TC_LOCAL_NAME);
	}
	elseif ($tc_settings['twitter_id']!='') {
		$str = "<script type=\"text/javascript\" id=\"tcws_".$tc_settings['twitter_id']."\">(function(){function async_load(){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src='http://twittercounter.com/remote/?v=2&twitter_id=".$tc_settings['twitter_id']."&users_id=".$tc_settings['users_id']."&width=".$width."&nr_show=".$nr_show."&hr_color=".$hr_color."&a_color=".$a_color."&bg_color=".$bg_color."';x=document.getElementById('tcws_".$tc_settings['twitter_id']."'); x.parentNode.insertBefore(s,x);}if(window.attachEvent){window.attachEvent('onload',async_load);}else{window.addEventListener('load',async_load,false);}})(); </script><noscript><a href=\"http://twittercounter.com/".$tc_settings['username']."\">@".$tc_settings['twitter_id']." on TwitterCounter.com</a></noscript><div id=\"tcw_".$tc_settings['twitter_id']."\"></div>";
	} else {
		$str = '<script type="text/javascript" language="javascript" src="http://twittercounter.com/remote/?username_owner=';
		$str .= $tc_settings['username'];
		$str .= '&amp;users_id=';
		$str .= $tc_settings['users_id'];
		$str .= '&amp;width=';
		$str .= $width;
		$str .= '&amp;nr_show=';
		$str .= $nr_show;
		$str .= '&amp;hr_color=';
		$str .= $hr_color;
		$str .= '&amp;a_color=';
		$str .= $a_color;
		$str .= '&amp;bg_color=';
		$str .= $bg_color;
		$str .= '"></script>';
	}
	
	$str .= '<br /><small><strong style="color:#'.$hr_color.'">Powered by </strong> <a href="http://ajaydsouza.com/wordpress/plugins/twittercounter/" style="font-weight:bold;color:#'.$a_color.'" rel="nofollow">TwitterCounter WordPress Plugin</a></small>';
	return $str;
}
// Add an action called echo_twitter_remote so that it can be called using do_action('echo_twitter_remote');
function echo_tr_function() {
	echo ald_tr();
}
add_action('echo_twitter_remote', 'echo_tr_function');

// Header function
function tc_header() {
	global $wpdb, $post, $single;

	$tc_settings = tc_read_options();
	$tc_custom_CSS = stripslashes($tc_settings['custom_CSS']);
	
	// Add CSS to header 
	if (($tc_custom_CSS != '')&&($tc_settings['username']!='')&&($tc_settings['style']=='script_only')) {
			echo '<style type="text/css">'.$tc_custom_CSS.'</style>';
	}
}
add_action('wp_head','tc_header');
	
// Default Options
function tc_default_options() {
	$tc_settings = 	Array (
						'username' => '',				// Twitter Username
						'twitter_id' => '',				// twitter id 
						'style' => 'avatar',				// TwitterCounter style
						'users_id' => '',				// TwitterCounter userid
						'a_color' => '709cb2',		// Twitter Widget Text and Hyperlink
						'hr_color' => '709CB2',		// Twitter Widget Horizontal rules
						'bg_color' => 'ffffff',		// Twitter Widget Background Color
						'nr_show' => '6',				// Twitter Widget Number of Rows
						'width' => '180',				// Twitter Widget Width
						'tc_hr_color' => 'ffffff',	// Twitter Counter Hr Color
						'tc_bg_color' => '111111',	// Twitter Counter Background Color
						'custom_CSS' => '',			// Custom CSS
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
function twittercounter_api($twitter_id= '3412651') {

	$api_call = 'http://api.twittercounter.com/?apikey=e9335031a759f251ee9b4e2e6634e1c5&twitter_id='.$twitter_id;

	$response = wp_remote_get( $api_call );
	if ( !is_wp_error( $response ) && isset( $response['body'] ) ) {
		$body = $response['body'];
		$twitter_info = json_decode( $body );
	}

	return $twitter_info;
}

/* This function gets user info via Twitter API */
function twitterusersshow_api($username= 'ajaydsouza') {

	$api_call = 'https://api.twitter.com/1/users/show.json?screen_name='.$username.'&include_entities=true';

	$response = wp_remote_get( $api_call );
	if ( !is_wp_error( $response ) && isset( $response['body'] ) ) {
		$body = $response['body'];
		$twitter_info = json_decode( $body );
	}

	// Store information we plan to use as variables
	$twitter_id = $twitter_info->id;

	return $twitter_id;
}



// Create a Wordpress Widget for TwitterCounter
class WidgetTC extends WP_Widget
{
	function WidgetTC()
	{
		$widget_ops = array('classname' => 'widget_twittercounter', 'description' => __( 'Display TwitterCounter',TC_LOCAL_NAME) );
		$this->WP_Widget('widget_twittercounter',__('TwitterCounter',TC_LOCAL_NAME), $widget_ops);
	}
	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$style = isset($instance['style']) ? esc_attr($instance['style']) : 'avatar';
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e('Title', TC_LOCAL_NAME); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /> 
		</label>
		</p>
		<p>
		<?php _e('Style', TC_LOCAL_NAME); ?>: <br />
		<select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
		  <option value="avatar" <?php if ($style=='avatar') echo 'selected="selected"' ?>>avatar</option>
		  <option value="bird" <?php if ($style=='bird') echo 'selected="selected"' ?>>bird</option>
		  <option value="custom" <?php if ($style=='custom') echo 'selected="selected"' ?>>custom</option>
		  <option value="script_only" <?php if ($style=='script_only') echo 'selected="selected"' ?>>script_only</option>
		  <option value="blank" <?php if ($style=='blank') echo 'selected="selected"' ?>>blank</option>
		  <option value="black" <?php if ($style=='black') echo 'selected="selected"' ?>>black</option>
		  <option value="white" <?php if ($style=='white') echo 'selected="selected"' ?>>white</option>
		  <option value="blue" <?php if ($style=='blue') echo 'selected="selected"' ?>>blue</option>
		</select>
		</p>
		<?php
	} //ending form creation
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['style'] = strip_tags($new_instance['style']);
		return $instance;
	} //ending update
	function widget($args, $instance) {
		global $wpdb;
		
		extract($args, EXTR_SKIP);
		
		$tc_settings = tc_read_options();

		$title = apply_filters('widget_title', $instance['title']);
		$style = $instance['style'];
		if (empty($style)) $style = $tc_settings['style'];
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
	
		echo ald_tc($style);
		echo $after_widget;

	} //ending function widget
}

// Create a Wordpress Widget for Twitter Widget
class WidgetTW extends WP_Widget
{
	function WidgetTW()
	{
		$widget_ops = array('classname' => 'widget_twitterwidget', 'description' => __( 'Display Twitter Widget',TC_LOCAL_NAME) );
		$this->WP_Widget('widget_twitterwidget',__('Twitter Widget',TC_LOCAL_NAME), $widget_ops);
	}
	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$width = isset($instance['width']) ? esc_attr($instance['width']) : '';
		$nr_show = isset($instance['nr_show']) ? esc_attr($instance['nr_show']) : '';
		$hr_color = isset($instance['hr_color']) ? esc_attr($instance['hr_color']) : '';
		$a_color = isset($instance['a_color']) ? esc_attr($instance['a_color']) : '';
		$bg_color = isset($instance['bg_color']) ? esc_attr($instance['bg_color']) : '';
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e('Title', TC_LOCAL_NAME); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /> 
		</label>
		</p>

		<p><?php _e('Leave the below options blank to use the default settings', TC_LOCAL_NAME); ?></p>
		<p>
		<label for="<?php echo $this->get_field_id('width'); ?>">
		<?php _e('Width', TC_LOCAL_NAME); ?>: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" /> 
		</label>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('nr_show'); ?>">
		<?php _e('Number of rows', TC_LOCAL_NAME); ?>: <input class="widefat" id="<?php echo $this->get_field_id('nr_show'); ?>" name="<?php echo $this->get_field_name('nr_show'); ?>" type="text" value="<?php echo esc_attr($nr_show); ?>" /> 
		</label>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('hr_color'); ?>">
		<?php _e('Header horizontal rules', TC_LOCAL_NAME); ?>: <input class="widefat" id="<?php echo $this->get_field_id('hr_color'); ?>" name="<?php echo $this->get_field_name('hr_color'); ?>" type="text" value="<?php echo esc_attr($hr_color); ?>" /> 
		</label>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('a_color'); ?>">
		<?php _e('Text and links', TC_LOCAL_NAME); ?>: <input class="widefat" id="<?php echo $this->get_field_id('a_color'); ?>" name="<?php echo $this->get_field_name('a_color'); ?>" type="text" value="<?php echo esc_attr($a_color); ?>" /> 
		</label>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('bg_color'); ?>">
		<?php _e('Background Color', TC_LOCAL_NAME); ?>: <input class="widefat" id="<?php echo $this->get_field_id('bg_color'); ?>" name="<?php echo $this->get_field_name('bg_color'); ?>" type="text" value="<?php echo esc_attr($bg_color); ?>" /> 
		</label>
		</p>

		<?php
	} //ending form creation
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['nr_show'] = strip_tags($new_instance['nr_show']);
		$instance['hr_color'] = strip_tags($new_instance['hr_color']);
		$instance['a_color'] = strip_tags($new_instance['a_color']);
		$instance['bg_color'] = strip_tags($new_instance['bg_color']);
		return $instance;
	} //ending update
	function widget($args, $instance) {
		global $wpdb;
		
		extract($args, EXTR_SKIP);
		
		$tc_settings = tc_read_options();

		$title = apply_filters('widget_title', $instance['title']);
		$style = $instance['style'];
		if (empty($style)) $style = $tc_settings['style'];
		
		$width = $instance['width'];
		if (empty($width)) $width = $tc_settings['width'];

		$nr_show = $instance['nr_show'];
		if (empty($nr_show)) $nr_show = $tc_settings['nr_show'];
		
		$hr_color = $instance['hr_color'];
		if (empty($hr_color)) $hr_color = $tc_settings['hr_color'];
		
		$a_color = $instance['a_color'];
		if (empty($a_color)) $a_color = $tc_settings['a_color'];
		
		$bg_color = $instance['bg_color'];
		if (empty($bg_color)) $bg_color = $tc_settings['bg_color'];

		echo $before_widget;
		echo $before_title . $title . $after_title;
	
		echo ald_tr($width, $nr_show, $hr_color, $a_color, $bg_color);
		echo $after_widget;

	} //ending function widget
}

function init_ald_tc(){
	if (function_exists('register_widget')) { 
		register_widget('WidgetTC');
		register_widget('WidgetTW');
	} 
}
add_action('init', 'init_ald_tc', 1); 
 
// This function adds an Options page in WP Admin
if (is_admin() || strstr($_SERVER['PHP_SELF'], 'wp-admin/')) {
	require_once(ALD_TC_DIR . "/admin.inc.php");

	function tc_plugin_actions_links( $links ) {
	
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=tc_options' ) . '">' . __('Settings', TC_LOCAL_NAME ) . '</a>'
			),
			$links
		);
	
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tc_plugin_actions_links' );

	// Add meta links
	function tc_plugin_actions( $links, $file ) {
		$plugin = plugin_basename(__FILE__);
	 
		// create link
		if ($file == $plugin) {
			$links[] = '<a href="http://ajaydsouza.com/support/">' . __('Support', TC_LOCAL_NAME ) . '</a>';
			$links[] = '<a href="http://ajaydsouza.com/donate/">' . __('Donate', TC_LOCAL_NAME ) . '</a>';
		}
		return $links;
	}
	global $wp_version;
	if ( version_compare( $wp_version, '2.8alpha', '>' ) )
		add_filter( 'plugin_row_meta', 'tc_plugin_actions', 10, 2 ); // only 2.8 and higher
	else add_filter( 'plugin_action_links', 'tc_plugin_actions', 10, 2 );

	
} // End admin.inc


?>