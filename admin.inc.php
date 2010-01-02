<?php
/**********************************************************************
*					Admin Page							*
*********************************************************************/
if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

if (!defined('TC_LOCAL_NAME')) define('TC_LOCAL_NAME', 'twittercounter');

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
// Guess the location
$twittercounter_path = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__));
$twittercounter_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));

function tc_options() {
	
	global $twittercounter_url;

	$tc_settings = tc_read_options();

	if($_POST['tc_save']){
		$tc_settings[username] = $_POST['username'];
		$tc_settings[style] = (($_POST['style']!='blank') ? $_POST['style'] : '');
		$tc_settings[a_color] = $_POST['a_color'];
		$tc_settings[hr_color] = $_POST['hr_color'];
		$tc_settings[bg_color] = $_POST['bg_color'];
		$tc_settings[tc_hr_color] = $_POST['tc_hr_color'];
		$tc_settings[tc_bg_color] = $_POST['tc_bg_color'];
		$tc_settings[nr_show] = $_POST['nr_show'];
		$tc_settings[width] = $_POST['width'];

		$tc_api = unserialize(twittercounter_api($tc_settings[username]));
		$tc_settings[users_id] = $tc_api[user_id];
		
		update_option('ald_tc_settings', $tc_settings);

	
		$str = '<div id="message" class="updated fade"><p>'. __('Options saved successfully.',TC_LOCAL_NAME) .'</p></div>';
		echo $str;
	}
	
	if ($_POST['tc_default']){
	
		delete_option('ald_tc_settings');
		$tc_settings = tc_default_options();
		update_option('ald_tc_settings', $tc_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options set to Default.',TC_LOCAL_NAME) .'</p></div>';
		echo $str;
	}
?>

<div class="wrap">
  <h2> TwitterCounter </h2>

  <div style="border: #ccc 1px solid; padding: 10px">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Support the Development',TC_LOCAL_NAME); ?>
    </h3>
    </legend>
    <p>
      <?php _e('If you find my',TC_LOCAL_NAME); ?>
      <a href="http://ajaydsouza.com/wordpress/plugins/twittercounter/">TwitterCounter</a>
      <?php _e('useful, please do',TC_LOCAL_NAME); ?>
      <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amp;business=donate@ajaydsouza.com&amp;item_name=TwitterCounter%20(From%20WP-Admin)&amp;no_shipping=1&amp;return=http://ajaydsouza.com/wordpress/plugins/twittercounter/&amp;cancel_return=http://ajaydsouza.com/wordpress/plugins/twittercounter/&amp;cn=Note%20to%20Author&amp;tax=0&amp;currency_code=USD&amp;bn=PP-DonationsBF&amp;charset=UTF-8" title="Donate via PayPal">
      <?php _e('drop in your contribution',TC_LOCAL_NAME); ?>
      </a>. (<a href="http://ajaydsouza.com/donate/">
      <?php _e('Some reasons why you should.',TC_LOCAL_NAME); ?>
      </a>)</p>
    </fieldset>
  </div>
  <form method="post" id="tc_options" name="tc_options" style="border: #ccc 1px solid; padding: 10px">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Options:',TC_LOCAL_NAME); ?>
    </h3>
    </legend>
    <p>
      <label for="username"><strong>
      <?php _e('Twitter username:',TC_LOCAL_NAME); ?>
      </strong></label>
      <input type="text" name="username" id="username" value="<?php echo $tc_settings[username]; ?>" size="40" maxlength="32" />
    </p>
	<h4><?php _e('Select Style of TwitterCounter badge',TC_LOCAL_NAME); ?></h4>
    <p>
      <label>
      <input type="radio" name="style" value="blank" id="style_0" <?php if ($tc_settings[style]=='') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
    </p>
	<p>
      <label>
      <input type="radio" name="style" value="black" id="style_1" <?php if ($tc_settings[style]=='black') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>&amp;style=black" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
    </p>
	<p>
      <label>
      <input type="radio" name="style" value="white" id="style_2" <?php if ($tc_settings[style]=='white') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>&amp;style=white" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
    </p>
	<p>
      <label>
      <input type="radio" name="style" value="blue" id="style_3" <?php if ($tc_settings[style]=='blue') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>&amp;style=blue" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
    </p>
	<p>
      <label>
      <input type="radio" name="style" value="custom" id="style_4" <?php if ($tc_settings[style]=='custom') echo 'checked="checked"' ?> /><script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>/<?php echo $tc_settings[tc_hr_color]; ?>/<?php echo $tc_settings[tc_bg_color]; ?>"></script></label>
	  <br /><strong><?php _e('Choose settings below:',TC_LOCAL_NAME); ?></strong><br />
	<?php _e('Text Color',TC_LOCAL_NAME); ?>: #<input class="color" name="tc_hr_color" type="text" value="<?php echo $tc_settings[tc_hr_color]; ?>" size="15" maxlength="6" />
	  <br />
	<?php _e('Background Color',TC_LOCAL_NAME); ?>: #<input class="color" name="tc_bg_color" type="text" value="<?php echo $tc_settings[tc_bg_color]; ?>" size="15" maxlength="6" />
    </p>
	<p>
      <label>
      <input type="radio" name="style" value="bird" id="style_5" <?php if ($tc_settings[style]=='bird') echo 'checked="checked"' ?> />
      <script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=<?php if ($tc_settings[username]=='') { echo 'ajaydsouza';} else { echo $tc_settings[username];} ?>&amp;style=bird"></script></label>
    </p>
	<div style="float:left;margin-right: 30px"><h4><?php _e('Preview',TC_LOCAL_NAME); ?>:</h4> <br /><?php do_action('echo_twitter_remote'); ?></div>
	<h4><?php _e('Select options for Twitter Remote',TC_LOCAL_NAME); ?></h4>
	<p><?php _e('User ID',TC_LOCAL_NAME); ?>: <input name="users_id" type="text" size="6" value="<?php echo $tc_settings[users_id]; ?>" readonly="readonly" />
		<br /><small><?php _e('Generated automatically from your username. This is the value of <code>users_id</code> in script code generated ',TC_LOCAL_NAME); ?>
		<a href="http://twittercounter.com/pages/remote?username_owner=<?php echo $tc_settings[username]; ?>" target="_blank">here</a></small></p>
	<p><?php _e('Hyperlink color',TC_LOCAL_NAME); ?>: #<input class="color" name="a_color" type="text" value="<?php echo $tc_settings[a_color]; ?>" size="15" maxlength="6" /> <small><?php _e('used for usernames and hyperlinks',TC_LOCAL_NAME); ?></small></p>
	<p><?php _e('Text Color',TC_LOCAL_NAME); ?>: #<input class="color" name="hr_color" type="text" value="<?php echo $tc_settings[hr_color]; ?>" size="15" maxlength="6" /> <small><?php _e('used for horizontal rulers and text',TC_LOCAL_NAME); ?></small></p>
	<p><?php _e('Background Color',TC_LOCAL_NAME); ?>: #<input class="color" name="bg_color" type="text" value="<?php echo $tc_settings[bg_color]; ?>" size="15" maxlength="6" /> <small><?php _e('used for some (not all) backgrounds',TC_LOCAL_NAME); ?></small></p>
	<p><?php _e('Number of rows',TC_LOCAL_NAME); ?>: <input name="nr_show" type="text" value="<?php echo $tc_settings[nr_show]; ?>" size="6" maxlength="2" /> <small><?php _e('How many Twitter users do you want to show? Min 6',TC_LOCAL_NAME); ?></small></p>
	<p><?php _e('Width',TC_LOCAL_NAME); ?>: <input name="width" type="text" value="<?php echo $tc_settings[width]; ?>" size="6" maxlength="3" />px <small><?php _e('How wide should the widget be? Min 180 pixels',TC_LOCAL_NAME); ?></small></p>
	<p><?php _e('Twitter Remote tends to cache your remote. If you don\'t see your remote changing color, please wait for a few minutes. ',TC_LOCAL_NAME); ?>
		<a href="http://twittercounter.com/pages/remote?username_owner=<?php echo $tc_settings[username]; ?>&amp;a_color=<?php echo $tc_settings[a_color]; ?>&amp;hr_color=<?php echo $tc_settings[hr_color]; ?>&amp;nr_show=<?php echo $tc_settings[nr_show]; ?>&amp;width=<?php echo $tc_settings[width]; ?>" target="_blank"><?php _e('Try forcing reload',TC_LOCAL_NAME); ?></a></p>
    </p>
	<p>
      <input type="submit" name="tc_save" id="tc_save" value="Save Options" style="border:#00CC00 1px solid" />
      <input name="tc_default" type="submit" id="tc_default" value="Default Options" style="border:#FF0000 1px solid" onclick="if (!confirm('<?php _e('Do you want to set options to Default? If you don\'t have a copy of the username, please hit Cancel and copy it first.',TC_LOCAL_NAME); ?>')) return false;" />
    </p>
    </fieldset>
  </form>
</div>
<?php

}

function tc_adminmenu() {
	if (function_exists('current_user_can')) {
		// In WordPress 2.x
		if (current_user_can('manage_options')) {
			$tc_is_admin = true;
		}
	} else {
		// In WordPress 1.x
		global $users_id;
		if (user_can_edit_user($users_id, 0)) {
			$tc_is_admin = true;
		}
	}

	if ((function_exists('add_options_page'))&&($tc_is_admin)) {
		$plugin_page = add_options_page(__("TwitterCounter"), __("TwitterCounter"), 9, 'tc_options', 'tc_options');
		add_action( 'admin_head-'. $plugin_page, 'tc_adminhead' );
	}
}
add_action('admin_menu', 'tc_adminmenu');

function tc_adminhead() {
	global $twittercounter_url;

?>
<script type="text/javascript" src="<?php echo $twittercounter_url ?>/jscolor/jscolor.js"></script>
<?php }

?>
