<?php
/**********************************************************************
*					Admin Page							*
*********************************************************************/
function tc_default_options() {
	$tc_settings = 	Array (
						username => '',		// Twitter Username
						style => '',		// TwitterCounter username
						);
	
	return $tc_settings;
}

function tc_options() {
	
	$tc_settings = tc_read_options();

	if($_POST['tc_save']){
		$tc_settings[username] = $_POST['username'];
		$tc_settings[style] = (($_POST['style']!='blank') ? $_POST['style'] : '');
				
		update_option('ald_tc_settings', $tc_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options saved successfully.','ald_tc_plugin') .'</p></div>';
		echo $str;
	}
	
	if ($_POST['tc_default']){
	
		delete_option('ald_tc_settings');
		$tc_settings = tc_default_options();
		update_option('ald_tc_settings', $tc_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options set to Default.','ald_tc_plugin') .'</p></div>';
		echo $str;
	}
?>

<div class="wrap">
  <h2> TwitterCounter </h2>
  <div style="border: #ccc 1px solid; padding: 10px">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Support the Development','ald_tc_plugin'); ?>
    </h3>
    </legend>
    <p>
      <?php _e('If you find my','ald_tc_plugin'); ?>
      <a href="http://ajaydsouza.com/wordpress/plugins/twittercounter/">TwitterCounter</a>
      <?php _e('useful, please do','ald_tc_plugin'); ?>
      <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amp;business=donate@ajaydsouza.com&amp;item_name=TwitterCounter%20(From%20WP-Admin)&amp;no_shipping=1&amp;return=http://ajaydsouza.com/wordpress/plugins/twittercounter/&amp;cancel_return=http://ajaydsouza.com/wordpress/plugins/twittercounter/&amp;cn=Note%20to%20Author&amp;tax=0&amp;currency_code=USD&amp;bn=PP-DonationsBF&amp;charset=UTF-8" title="Donate via PayPal">
      <?php _e('drop in your contribution','ald_tc_plugin'); ?>
      </a>. (<a href="http://ajaydsouza.com/donate/">
      <?php _e('Some reasons why you should.','ald_tc_plugin'); ?>
      </a>)</p>
    </fieldset>
  </div>
  <form method="post" id="tc_options" name="tc_options" style="border: #ccc 1px solid; padding: 10px">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Options:','ald_tc_plugin'); ?>
    </h3>
    </legend>
	<p>Style: "<?php echo $tc_settings[style]; ?>"</p>
    <p>
      <label for="username"><strong>
      <?php _e('Twitter username:','ald_tc_plugin'); ?>
      </strong></label>
      <input type="text" name="username" id="username" value="<?php echo $tc_settings[username]; ?>" size="40" maxlength="32" />
    </p>
    <p>
      <label>
      <input type="radio" name="style" value="blank" id="style_0" <?php if ($tc_settings[style]=='') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
      <br />
      <label>
      <input type="radio" name="style" value="black" id="style_1" <?php if ($tc_settings[style]=='black') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>&amp;style=black" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
      <br />
      <label>
      <input type="radio" name="style" value="white" id="style_2" <?php if ($tc_settings[style]=='white') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>&amp;style=white" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
      <br />
      <label>
      <input type="radio" name="style" value="blue" id="style_3" <?php if ($tc_settings[style]=='blue') echo 'checked="checked"' ?> />
      <img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>&amp;style=blue" alt="TwitterCounter for @<?php if ($tc_settings[username]=='') { echo 'thecounter';} else { echo $tc_settings[username];} ?>" width="88" height="26" /></label>
      <br />
    </p>
    <p>
      <input type="submit" name="tc_save" id="tc_save" value="Save Options" style="border:#00CC00 1px solid" />
      <input name="tc_default" type="submit" id="tc_default" value="Default Options" style="border:#FF0000 1px solid" onclick="if (!confirm('<?php _e('Do you want to set options to Default? If you don\'t have a copy of the username, please hit Cancel and copy it first.','ald_tc_plugin'); ?>')) return false;" />
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
		global $user_ID;
		if (user_can_edit_user($user_ID, 0)) {
			$tc_is_admin = true;
		}
	}

	if ((function_exists('add_options_page'))&&($tc_is_admin)) {
		add_options_page(__("TwitterCounter"), __("TwitterCounter"), 9, 'tc_options', 'tc_options');
		}
}


add_action('admin_menu', 'tc_adminmenu');

?>
