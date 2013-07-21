<?php
/**********************************************************************
*					Admin Page							*
*********************************************************************/
if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");
if (!defined('TC_LOCAL_NAME')) define('TC_LOCAL_NAME', 'twittercounter');

function tc_options() {
	
	global $twittercounter_url;

	$tc_settings = tc_read_options();

	if( (isset($_POST['tc_save']))&&( check_admin_referer('tc-plugin') ) ) {
		$tc_settings['username'] = $_POST['username'];
		$tc_settings['twitter_id'] = $_POST['twitter_id'];
		$tc_settings['users_id'] = $_POST['users_id'];
		$tc_settings['style'] = (($_POST['style']!='blank') ? $_POST['style'] : '');
		$tc_settings['a_color'] = $_POST['a_color'];
		$tc_settings['hr_color'] = $_POST['hr_color'];
		$tc_settings['bg_color'] = $_POST['bg_color'];
		$tc_settings['tc_hr_color'] = $_POST['tc_hr_color'];
		$tc_settings['tc_bg_color'] = $_POST['tc_bg_color'];
		$tc_settings['nr_show'] = $_POST['nr_show'];
		$tc_settings['width'] = $_POST['width'];
		$tc_settings['custom_CSS'] = $_POST['custom_CSS'];
		
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
	<div id="page-wrap">
	<div id="inside">
		<div id="header">
		<h2>TwitterCounter</h2>
		</div>
	  <div id="side">
		<div class="side-widget">
			<span class="title"><?php _e('Support the development',TC_LOCAL_NAME) ?></span>
			<div id="donate-form">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="donate@ajaydsouza.com">
				<input type="hidden" name="lc" value="IN">
				<input type="hidden" name="item_name" value="Donation for TwitterCounter">
				<input type="hidden" name="item_number" value="tc">
				<strong><?php _e('Enter amount in USD: ',TC_LOCAL_NAME) ?></strong> <input name="amount" value="10.00" size="6" type="text"><br />
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="button_subtype" value="services">
				<input type="hidden" name="bn" value="PP-BuyNowBF:btn_donate_LG.gif:NonHosted">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="<?php _e('Send your donation to the author of',TC_LOCAL_NAME) ?> TwitterCounter?">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
		</div>
		<div class="side-widget">
		<span class="title"><?php _e('Quick links') ?></span>				
		<ul>
			<li><a href="http://ajaydsouza.com/wordpress/plugins/twittercounter/"><?php _e('TwitterCounter ');_e('plugin page',TC_LOCAL_NAME) ?></a></li>
			<li><a href="http://ajaydsouza.com/wordpress/plugins/"><?php _e('Other plugins',TC_LOCAL_NAME) ?></a></li>
			<li><a href="http://ajaydsouza.com/"><?php _e('Ajay\'s blog',TC_LOCAL_NAME) ?></a></li>
			<li><a href="http://ajaydsouza.com/support/"><?php _e('Support',TC_LOCAL_NAME) ?></a></li>
			<li><a href="http://twitter.com/ajaydsouza"><?php _e('Follow @ajaydsouza on Twitter',TC_LOCAL_NAME) ?></a></li>
		</ul>
		</div>
		<div class="side-widget">
		<span class="title"><?php _e('Recent developments',TC_LOCAL_NAME) ?></span>				
		<?php require_once(ABSPATH . WPINC . '/class-simplepie.php'); wp_widget_rss_output('http://ajaydsouza.com/archives/category/wordpress/plugins/feed/', array('items' => 5, 'show_author' => 0, 'show_date' => 1));
		?>
		</div>
		<div class="side-widget">
		<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fajaydsouzacom&amp;width=292&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=true&amp;appId=113175385243" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
		</div>
	  </div>

	  <div id="options-div">
	  <form method="post" id="tc_options" name="tc_options" onsubmit="return checkForm()">
		<fieldset class="options">
		<div class="tabber">
			<div class="tabbertab">
				<h3>
				  <?php _e('Twitter Buttons',TC_LOCAL_NAME); ?>
				</h3>
				<table class="form-table">
				<tr style="vertical-align: top;"><th scope="row"><label for="username"><?php _e('Twitter username:',TC_LOCAL_NAME); ?></label></th>
				<td><input type="text" name="username" id="username" value="<?php echo $tc_settings['username']; ?>" size="40" maxlength="32" /></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><label for="twitter_id"><?php _e('Twitter ID:',TC_LOCAL_NAME); ?></label></th>
				<td><input type="text" name="twitter_id" id="twitter_id" value="<?php echo $tc_settings['twitter_id']; ?>" size="40" maxlength="32" />
				<br /><?php _e('Find out your Twitter ID from <a href="http://tweeterid.com/" target="_blank">TweeterID</a> or <a href="http://id.twidder.info/" target="_blank">TwIDder</a> or <a href="http://idfromuser.org/" target="_blank">idfromuser.org</a> or <a href="http://www.idfromuser.com/" target="_blank">idfromuser.com</a>', TC_LOCAL_NAME); ?>
				</td>
				</tr>
				<tr style="vertical-align: top; background: #eee"><th scope="row" colspan="2"><?php _e('Select Style of TwitterCounter badge',TC_LOCAL_NAME); ?></th>
				</tr>
				<tr style="vertical-align: top;"><td colspan="2"><?php _e('If you have never used TwitterCounter before please visit <a href="http://twittercounter.com" target="_blank">http://twittercounter.com</a>, enter your Twitter username and hit <strong>Get Stats</strong>',TC_LOCAL_NAME); ?></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="avatar" id="style_6" <?php if ($tc_settings['style']=='avatar') echo 'checked="checked"' ?> /></th>
				<td><label for="style_6"><script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>&amp;style=avatar"></script></label></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="bird" id="style_5" <?php if ($tc_settings['style']=='bird') echo 'checked="checked"' ?> /></th>
				<td><label for="style_5"><script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>&amp;style=bird"></script></label></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="custom" id="style_4" <?php if ($tc_settings['style']=='custom') echo 'checked="checked"' ?> /></th>
				<td><label for="style_4"><script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>/<?php echo $tc_settings['tc_hr_color']; ?>/<?php echo $tc_settings['tc_bg_color']; ?>"></script></label>
				  <br /><strong><?php _e('Choose settings for the above button:',TC_LOCAL_NAME); ?></strong><br />
				<?php _e('Text Color',TC_LOCAL_NAME); ?>: #<input class="color" name="tc_hr_color" type="text" value="<?php echo $tc_settings['tc_hr_color']; ?>" size="15" maxlength="6" />
				  <br />
				<?php _e('Background Color',TC_LOCAL_NAME); ?>: #<input class="color" name="tc_bg_color" type="text" value="<?php echo $tc_settings['tc_bg_color']; ?>" size="15" maxlength="6" />
				</td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="script_only" id="style_7" <?php if ($tc_settings['style']=='script_only') echo 'checked="checked"' ?> /></th>
				<td><script type="text/javascript" language="javascript" src="http://twittercounter.com/widget/index.php?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>"></script>
				<br /><?php _e('This generates just the count wrapped with a div with <code>id="TwitterCounter"</code>. You can enter your own styles in the <strong>Custom Styles</strong> tab',TC_LOCAL_NAME); ?></td>
				</tr>
				<tr style="vertical-align: top; background: #eee"><th scope="row" colspan="2"><?php _e('Older buttons (count may not be accurate)',TC_LOCAL_NAME); ?></th>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="blank" id="style_0" <?php if ($tc_settings['style']=='') echo 'checked="checked"' ?> /></th>
				<td><label for="style_0"><img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>" alt="TwitterCounter for @<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>" width="88" height="26" /></label></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="black" id="style_1" <?php if ($tc_settings['style']=='black') echo 'checked="checked"' ?> /></th>
				<td><label for="style_1"><img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>&amp;style=black" alt="TwitterCounter for @<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>" width="88" height="26" /></label></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="white" id="style_2" <?php if ($tc_settings['style']=='white') echo 'checked="checked"' ?> /></th>
				<td><label for="style_2"><img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>&amp;style=white" alt="TwitterCounter for @<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>" width="88" height="26" /></label></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="blue" id="style_3" <?php if ($tc_settings['style']=='blue') echo 'checked="checked"' ?> /></th>
				<td><label for="style_3"><img src="http://twittercounter.com/counter/index_nocache.php?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>&amp;style=blue" alt="TwitterCounter for @<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>" width="88" height="26" /></label></td>
				</tr>
				</table>		
			</div>
			<div class="tabbertab">
				<h3>
				  <?php _e('Twitter Widget',TC_LOCAL_NAME); ?>
				</h3>
				<table class="form-table">
				<tr style="vertical-align: top; background: #eee;"><th scope="col" style="text-align: center;"><?php _e('Preview',TC_LOCAL_NAME); ?></th>
				<th scope="col" style="text-align: center;"><?php _e('Select options for Twitter Widget',TC_LOCAL_NAME); ?></th>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><?php do_action('echo_twitter_remote'); ?></th>
				<td><table>
					<tr style="vertical-align: top; background:#ccc"><td colspan="2"><?php _e('If you have never used TwitterCounter before please visit <a href="http://twittercounter.com" target="_blank">http://twittercounter.com</a>, enter your Twitter username and hit <strong>Get Stats</strong>',TC_LOCAL_NAME); ?></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e('User ID',TC_LOCAL_NAME); ?></th>
					<td><input name="users_id" type="text" size="6" value="<?php echo $tc_settings['users_id']; ?>" />
						<br /><small><?php _e('This is the value of <code>users_id</code> in script code generated ',TC_LOCAL_NAME); ?>
						<a href="http://twittercounter.com/pages/twitter-widget" target="_blank">here</a></small>
					</td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e('Text and links',TC_LOCAL_NAME); ?></th>
					<td>#<input class="color" name="a_color" type="text" value="<?php echo $tc_settings['a_color']; ?>" size="15" maxlength="6" /> <small><?php _e('used for usernames and hyperlinks',TC_LOCAL_NAME); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e('Header horizontal rules',TC_LOCAL_NAME); ?></th>
					<td>#<input class="color" name="hr_color" type="text" value="<?php echo $tc_settings['hr_color']; ?>" size="15" maxlength="6" /> <small><?php _e('used for horizontal rulers and text',TC_LOCAL_NAME); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e('Background Color',TC_LOCAL_NAME); ?></th>
					<td>#<input class="color" name="bg_color" type="text" value="<?php echo $tc_settings['bg_color']; ?>" size="15" maxlength="6" /> <small><?php _e('used for some (not all) backgrounds',TC_LOCAL_NAME); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e('Number of rows',TC_LOCAL_NAME); ?></th>
					<td><input name="nr_show" type="text" value="<?php echo $tc_settings['nr_show']; ?>" size="6" maxlength="2" /> <small><?php _e('How many Twitter users do you want to show? Min 6',TC_LOCAL_NAME); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e('Width',TC_LOCAL_NAME); ?></th>
					<td><input name="width" type="text" value="<?php echo $tc_settings['width']; ?>" size="6" maxlength="3" />px <small><?php _e('How wide should the widget be? Min 180 pixels',TC_LOCAL_NAME); ?></small></td>
					</tr>
					</table>
				</td>
				</tr>
				</table>		
			</div>
			<div class="tabbertab">
			<h3>
			  <?php _e('Custom Styles',TC_LOCAL_NAME); ?>
			</h3>
				<table class="form-table">
				<tr style="vertical-align: top; "><th scope="row" colspan="2"><?php _e('Custom CSS to add to header:',ATA_LOCAL_NAME); ?></th>
				</tr>
				<tr style="vertical-align: top; "><td scope="row" colspan="2"><textarea name="custom_CSS" id="custom_CSS" rows="15" cols="80"><?php echo stripslashes($tc_settings['custom_CSS']); ?></textarea>
				<br /><em><?php _e('Do not include <code>style</code> tags. Check out the <a href="http://wordpress.org/extend/plugins/twittercounter/faq/">FAQ</a> for available CSS classes to style.',ATA_LOCAL_NAME); ?></em></td></tr>
				</table>		
			</div>
		</div>
		<p>
		  <input type="submit" name="tc_save" id="tc_save" value="Save Options" style="border:#00CC00 1px solid" />
		  <input name="tc_default" type="submit" id="tc_default" value="Default Options" style="border:#FF0000 1px solid" onclick="if (!confirm('<?php _e('Do you want to set options to Default?',TC_LOCAL_NAME); ?>')) return false;" />
		</p>
		</fieldset>
		<?php wp_nonce_field('tc-plugin'); ?>
	  </form>
	  </div>

	  </div>
	  <div style="clear: both;"></div>
	</div>
</div>
<?php

}

function tc_adminmenu() {
	if (function_exists('add_options_page')) {
		$plugin_page = add_options_page(__("TwitterCounter", TC_LOCAL_NAME), __("TwitterCounter", TC_LOCAL_NAME), 'manage_options', 'tc_options', 'tc_options');
		add_action( 'admin_head-'. $plugin_page, 'tc_adminhead' );
	}
}
add_action('admin_menu', 'tc_adminmenu');

function tc_adminhead() {
	global $twittercounter_url;

?>
<link rel="stylesheet" type="text/css" href="<?php echo $twittercounter_url ?>/admin-styles.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $twittercounter_url ?>/tabber/tabber.css" />
<script type="text/javascript" src="<?php echo $twittercounter_url ?>/jscolor/jscolor.js"></script>
<script type="text/javascript" src="<?php echo $twittercounter_url ?>/tabber/tabber.js"></script>
<script type="text/javascript" language="JavaScript">
function checkForm() {
answer = true;
if (siw && siw.selectingSomething)
	answer = false;
return answer;
}//
</script>
<?php }

?>
