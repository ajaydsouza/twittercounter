<?php
/**
 * Generates the settings page in the Admin
 *
 * @package TwitterCounter
 */

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}

/**
 * Twitter Counter options.
 */
function tc_options() {

	global $twittercounter_url;

	$tc_settings = tc_read_options();

	if ( ( isset( $_POST['tc_save'] ) ) && ( check_admin_referer( 'twittercounter-admin-ops' ) ) ) {
		$tc_settings['username'] = $_POST['username'];
		$tc_settings['twitter_id'] = $_POST['twitter_id'];
		$tc_settings['users_id'] = $_POST['users_id'];
		$tc_settings['style'] = ( 'blank' != $_POST['style'] ) ? $_POST['style'] : '';
		$tc_settings['a_color'] = $_POST['a_color'];
		$tc_settings['hr_color'] = $_POST['hr_color'];
		$tc_settings['bg_color'] = $_POST['bg_color'];
		$tc_settings['tc_hr_color'] = $_POST['tc_hr_color'];
		$tc_settings['tc_bg_color'] = $_POST['tc_bg_color'];
		$tc_settings['nr_show'] = $_POST['nr_show'];
		$tc_settings['width'] = intval( $_POST['width'] );
		$tc_settings['custom_CSS'] = wp_kses_post( $_POST['custom_CSS'] );

		update_option( 'ald_tc_settings', $tc_settings );

		$str = '<div id="message" class="updated fade"><p>'. __( 'Options saved successfully.', 'twittercounter' ) .'</p></div>';
		echo $str;
	}

	if ( ( isset( $_POST['tc_default'] ) ) && ( check_admin_referer( 'twittercounter-admin-ops' ) ) ) {
		delete_option( 'ald_tc_settings' );
		$tc_settings = tc_default_options();
		update_option( 'ald_tc_settings', $tc_settings );

		$str = '<div id="message" class="updated fade"><p>'. __( 'Options set to Default.', 'twittercounter' ) .'</p></div>';
		echo $str;
	}
?>

<div class="wrap">
	<h2><?php _e( 'Twitter Counter', 'twittercounter' ); ?></h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
	<div id="post-body-content">
	  <div id="options-div">
	  <form method="post" id="tc_options" name="tc_options" onsubmit="return checkForm()">
	    <div id="thirdpartydiv" class="postbox closed"><div class="handlediv" title="<?php _e( 'Click to toggle', 'twittercounter' ); ?>"><br /></div>
	      <h3 class='hndle'><span><?php _e( 'Twitter Buttons', 'twittercounter' ); ?></span></h3>
	      <div class="inside">
			<table class="form-table">
				<tr style="vertical-align: top;"><th scope="row"><label for="username"><?php _e( 'Twitter username:', 'twittercounter' ); ?></label></th>
				<td><input type="text" name="username" id="username" value="<?php echo $tc_settings['username']; ?>" size="40" maxlength="32" /></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><label for="twitter_id"><?php _e( 'Twitter ID:', 'twittercounter' ); ?></label></th>
				<td><input type="text" name="twitter_id" id="twitter_id" value="<?php echo $tc_settings['twitter_id']; ?>" size="40" maxlength="32" />
				<br /><?php _e( 'Find out your Twitter ID from <a href="http://tweeterid.com/" target="_blank">TweeterID</a> or <a href="http://id.twidder.info/" target="_blank">TwIDder</a> or <a href="http://idfromuser.org/" target="_blank">idfromuser.org</a> or <a href="http://www.idfromuser.com/" target="_blank">idfromuser.com</a>', 'twittercounter'); ?>
				</td>
				</tr>
				<tr style="vertical-align: top; background: #eee"><th scope="row" colspan="2"><?php _e( 'Select Style of TwitterCounter badge', 'twittercounter' ); ?></th>
				</tr>
				<tr style="vertical-align: top;"><td colspan="2"><?php _e( 'If you have never used TwitterCounter before please visit <a href="http://twittercounter.com" target="_blank">http://twittercounter.com</a>, enter your Twitter username and hit <strong>Get Stats</strong>', 'twittercounter' ); ?></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="avatar" id="style_6" <?php if ($tc_settings['style']=='avatar') echo 'checked="checked"' ?> /></th>
				<td><label for="style_6"><script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>&amp;style=avatar"></script></label></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="bird" id="style_5" <?php if ($tc_settings['style']=='bird') echo 'checked="checked"' ?> /></th>
				<td><label for="style_5"><script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>&amp;style=bird"></script></label></td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="custom" id="style_4" <?php if ($tc_settings['style']=='custom') echo 'checked="checked"' ?> /></th>
				<td><label for="style_4"><script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>/<?php echo $tc_settings['tc_hr_color']; ?>/<?php echo $tc_settings['tc_bg_color']; ?>"></script></label>
				  <br /><strong><?php _e( 'Choose settings for the above button:', 'twittercounter' ); ?></strong><br />
				<?php _e( 'Text Color', 'twittercounter' ); ?>: #<input class="color" name="tc_hr_color" type="text" value="<?php echo $tc_settings['tc_hr_color']; ?>" size="15" maxlength="6" />
				  <br />
				<?php _e( 'Background Color', 'twittercounter' ); ?>: #<input class="color" name="tc_bg_color" type="text" value="<?php echo $tc_settings['tc_bg_color']; ?>" size="15" maxlength="6" />
				</td>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><input type="radio" name="style" value="script_only" id="style_7" <?php if ($tc_settings['style']=='script_only') echo 'checked="checked"' ?> /></th>
				<td><script type="text/javascript" language="javascript" src="http://twittercounter.com/widget/index.php?username=<?php if ($tc_settings['username']=='') { echo 'ajaydsouza';} else { echo $tc_settings['username'];} ?>"></script>
				<br /><?php _e( 'This generates just the count wrapped with a div with <code>id="TwitterCounter"</code>. You can enter your own styles in the <strong>Custom Styles</strong> tab', 'twittercounter' ); ?></td>
				</tr>
				<tr style="vertical-align: top; background: #eee"><th scope="row" colspan="2"><?php _e( 'Older buttons (count may not be accurate)', 'twittercounter' ); ?></th>
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
	    </div>
	    <div id="headeropdiv" class="postbox closed"><div class="handlediv" title="<?php _e( 'Click to toggle', 'twittercounter' ); ?>"><br /></div>
	      <h3 class='hndle'><span><?php _e( 'Twitter Widget', 'twittercounter' ); ?></span></h3>
	      <div class="inside">
			<table class="form-table">
				<tr style="vertical-align: top; background: #eee;"><th scope="col" style="text-align: center;"><?php _e( 'Preview', 'twittercounter' ); ?></th>
				<th scope="col" style="text-align: center;"><?php _e( 'Select options for Twitter Widget', 'twittercounter' ); ?></th>
				</tr>
				<tr style="vertical-align: top;"><th scope="row"><?php do_action('echo_twitter_remote'); ?></th>
				<td><table>
					<tr style="vertical-align: top; background:#ccc"><td colspan="2"><?php _e( 'If you have never used TwitterCounter before please visit <a href="http://twittercounter.com" target="_blank">http://twittercounter.com</a>, enter your Twitter username and hit <strong>Get Stats</strong>', 'twittercounter' ); ?></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e( 'User ID', 'twittercounter' ); ?></th>
					<td><input name="users_id" type="text" size="6" value="<?php echo $tc_settings['users_id']; ?>" />
						<br /><small><?php _e( 'This is the value of <code>users_id</code> in script code generated ', 'twittercounter' ); ?>
						<a href="http://twittercounter.com/pages/twitter-widget" target="_blank">here</a></small>
					</td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e( 'Text and links', 'twittercounter' ); ?></th>
					<td>#<input class="color" name="a_color" type="text" value="<?php echo $tc_settings['a_color']; ?>" size="15" maxlength="6" /> <small><?php _e( 'used for usernames and hyperlinks', 'twittercounter' ); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e( 'Header horizontal rules', 'twittercounter' ); ?></th>
					<td>#<input class="color" name="hr_color" type="text" value="<?php echo $tc_settings['hr_color']; ?>" size="15" maxlength="6" /> <small><?php _e( 'used for horizontal rulers and text', 'twittercounter' ); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e( 'Background Color', 'twittercounter' ); ?></th>
					<td>#<input class="color" name="bg_color" type="text" value="<?php echo $tc_settings['bg_color']; ?>" size="15" maxlength="6" /> <small><?php _e( 'used for some (not all) backgrounds', 'twittercounter' ); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e( 'Number of rows', 'twittercounter' ); ?></th>
					<td><input name="nr_show" type="text" value="<?php echo $tc_settings['nr_show']; ?>" size="6" maxlength="2" /> <small><?php _e( 'How many Twitter users do you want to show? Min 6', 'twittercounter' ); ?></small></td>
					</tr>
					<tr style="vertical-align: top;"><th scope="row"><?php _e( 'Width', 'twittercounter' ); ?></th>
					<td><input name="width" type="text" value="<?php echo $tc_settings['width']; ?>" size="6" maxlength="3" />px <small><?php _e( 'How wide should the widget be? Min 180 pixels', 'twittercounter' ); ?></small></td>
					</tr>
					</table>
				</td>
				</tr>
			</table>
	      </div>
	    </div>
	    <div id="contentopdiv" class="postbox closed"><div class="handlediv" title="<?php _e( 'Click to toggle', 'twittercounter' ); ?>"><br /></div>
	      <h3 class='hndle'><span><?php _e( 'Custom Styles', 'twittercounter' ); ?></span></h3>
	      <div class="inside">
			<table class="form-table">
				<tr style="vertical-align: top; "><th scope="row" colspan="2"><?php _e( 'Custom CSS to add to header:','twittercounter'); ?></th>
				</tr>
				<tr style="vertical-align: top; "><td scope="row" colspan="2"><textarea name="custom_CSS" id="custom_CSS" rows="15" cols="80"><?php echo stripslashes($tc_settings['custom_CSS']); ?></textarea>
				<br /><em><?php _e( 'Do not include <code>style</code> tags. Check out the <a href="http://wordpress.org/extend/plugins/twittercounter/faq/">FAQ</a> for available CSS classes to style.','twittercounter'); ?></em></td></tr>
			</table>
	      </div>
	    </div>

		<p>
		  <input type="submit" name="tc_save" id="tc_save" value="Save Options" class="button-primary" />
		  <input name="tc_default" type="submit" id="tc_default" value="Default Options" class="button-secondary" onclick="if (!confirm('<?php _e( 'Do you want to set options to Default?', 'twittercounter' ); ?>')) return false;" />
		</p>
		<?php wp_nonce_field( 'twittercounter-admin-ops' ); ?>
	  </form>
	</div><!-- /options-div -->
	</div><!-- /post-body-content -->
	<div id="postbox-container-1" class="postbox-container">
	  <div id="side-sortables" class="meta-box-sortables ui-sortable">
	    <div id="donatediv" class="postbox"><div class="handlediv" title="<?php _e( 'Click to toggle', 'twittercounter' ); ?>"><br /></div>
	      <h3 class='hndle'><span><?php _e( 'Support the development', 'twittercounter' ); ?></span></h3>
	      <div class="inside">
			<div id="donate-form">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="donate@ajaydsouza.com">
				<input type="hidden" name="lc" value="IN">
				<input type="hidden" name="item_name" value="Donation for Twitter Counter">
				<input type="hidden" name="item_number" value="tc_admin">
				<strong><?php _e( 'Enter amount in USD: ', 'twittercounter' ); ?></strong> <input name="amount" value="10.00" size="6" type="text"><br />
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="button_subtype" value="services">
				<input type="hidden" name="bn" value="PP-BuyNowBF:btn_donate_LG.gif:NonHosted">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="<?php _e( 'Send your donation to the author of', 'twittercounter' ); ?> Twitter Counter?">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
	      </div>
	    </div>
	    <div id="followdiv" class="postbox"><div class="handlediv" title="<?php _e( 'Click to toggle', 'twittercounter' ); ?>"><br /></div>
	      <h3 class='hndle'><span><?php _e( 'Follow me', 'twittercounter' ); ?></span></h3>
	      <div class="inside">
			<div id="follow-us">
				<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fajaydsouzacom&amp;width=292&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=true&amp;appId=113175385243" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
				<div style="text-align:center"><a href="https://twitter.com/ajaydsouza" class="twitter-follow-button" data-show-count="false" data-size="large" data-dnt="true">Follow @ajaydsouza</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//plataorm.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
			</div>
	      </div>
	    </div>
	    <div id="qlinksdiv" class="postbox"><div class="handlediv" title="<?php _e( 'Click to toggle', 'twittercounter' ); ?>"><br /></div>
	      <h3 class='hndle'><span><?php _e( 'Quick links', 'twittercounter' ); ?></span></h3>
	      <div class="inside">
	        <div id="quick-links">
				<ul>
					<li><a href="http://ajaydsouza.com/wordpress/plugins/twittercounter/"><?php _e( 'Twitter Counter plugin page', 'twittercounter' ); ?></a></li>
					<li><a href="http://ajaydsouza.com/wordpress/plugins/"><?php _e( 'Other plugins', 'twittercounter' ); ?></a></li>
					<li><a href="http://ajaydsouza.com/"><?php _e( "Ajay's blog", 'twittercounter' ); ?></a></li>
					<li><a href="https://wordpress.org/plugins/twittercounter/faq/"><?php _e( 'FAQ', 'twittercounter' ); ?></a></li>
					<li><a href="https://wordpress.org/support/plugin/twittercounter"><?php _e( 'Support', 'twittercounter' ); ?></a></li>
					<li><a href="https://wordpress.org/support/view/plugin-reviews/twittercounter"><?php _e( 'Reviews', 'twittercounter' ); ?></a></li>
				</ul>
	        </div>
	      </div>
	    </div>
	  </div><!-- /side-sortables -->
	</div><!-- /postbox-container-1 -->
	</div><!-- /post-body -->
	<br class="clear" />
	</div><!-- /poststuff -->
</div><!-- /wrap -->

<?php
}


/**
 * Add links to the Admin menu. Filters `admin_menu`.
 */
function tc_adminmenu() {
	$plugin_page = add_options_page(__( "TwitterCounter", 'twittercounter'), __( "TwitterCounter", 'twittercounter'), 'manage_options', 'tc_options', 'tc_options');
	add_action( 'admin_head-'. $plugin_page, 'tc_adminhead' );
}
add_action( 'admin_menu', 'tc_adminmenu' );


/**
 * Add scripts to Admin head.
 */
function tc_adminhead() {
	global $twittercounter_url;
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'wp-lists' );
	wp_enqueue_script( 'postbox' );

?>
	<style type="text/css">
		.postbox .handlediv:before {
			right:12px;
			font:400 20px/1 dashicons;
			speak:none;
			display:inline-block;
			top:0;
			position:relative;
			-webkit-font-smoothing:antialiased;
			-moz-osx-font-smoothing:grayscale;
			text-decoration:none!important;
			content:'\f142';
			padding:8px 10px;
		}
		.postbox.closed .handlediv:before {
			content: '\f140';
		}
		.wrap h2:before {
		    content: "\f301";
		    display: inline-block;
		    -webkit-font-smoothing: antialiased;
		    font: normal 29px/1 'dashicons';
		    vertical-align: middle;
		    margin-right: 0.3em;
		}
	</style>

	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('tc_options');
		});
		//]]>
	</script>

	<script type="text/javascript" src="<?php echo $twittercounter_url ?>/jscolor/jscolor.js"></script>
	<script type="text/javascript" language="JavaScript">
		//<![CDATA[
		function checkForm() {
		answer = true;
		if (siw && siw.selectingSomething)
			answer = false;
		return answer;
		}//
		//]]>
	</script>

<?php
}

?>
