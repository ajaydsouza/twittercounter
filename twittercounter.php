<?php
/**
 * Integrate TwitterCounter.com badges on your blog to display the number of followers you have on Twitter.
 *
 * @package TwitterCounter
 *
 * @wordpress-plugin
 * Plugin Name: Twitter Counter
 * Version:     1.6.3
 * Plugin URI:  http://ajaydsouza.com/wordpress/plugins/twittercounter/
 * Description: Integrate TwitterCounter.com badges on your blog to display the number of followers you have on Twitter
 * Author:      Ajay D'Souza
 * Author URI:  http://ajaydsouza.com/
 * Text Domain:	twittercounter
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:	/languages
*/

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}

/**
 * Holds the filesystem directory path.
 */
define( 'ALD_TC_DIR', dirname( __FILE__ ) );

/**
 * Set the global variables for twittercounter path and URL
 */
$twittercounter_path = plugin_dir_path( __FILE__ );
$twittercounter_url = plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) );


/**
 * Declare $tc_settings global so that it can be accessed in every function
 */
global $tc_settings;
$tc_settings = tc_read_options();


/**
 * Function to load translation files.
 */
function tc_lang_init() {
	load_plugin_textdomain( 'twittercounter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('init', 'tc_lang_init');


/**
 * Function to add Twitter Counter code.
 *
 * @param bool $style (default: false) Style of the button.
 * @return string Twitter Counter script
 */
function ald_tc( $style = false ) {
	global $tc_settings;

	if ( empty( $style ) ) {
		$style = $tc_settings['style'];
	}

	if ( '' == $tc_settings['username'] ) {
		$str = __( 'Please visit WP-Admin &gt; Settings &gt; Twitter Counter and enter your Twitter username', 'twittercounter' );
	} else {
		if ( 'custom' == $style ) {
			$str = '<script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/' . $tc_settings['username'] . '/' . $tc_settings['tc_hr_color'] . '/' . $tc_settings['tc_bg_color'] . '"></script>';
		} elseif ( 'script_only' == $style ) {
			$str = '<!-- Start of Twitter Counter Code -->';
			$str .= '<script type="text/javascript" language="javascript" src="http://twittercounter.com/widget/index.php?username=' . $tc_settings['username'] . '"></script><noscript><a href="http://twittercounter.com/' . $tc_settings['username'] . '">@' . $tc_settings['username'] . ' on Twitter Counter</a></noscript>';
			$str .= '<!-- End of Twitter Counter Code --> ';
		} else {
			$str = '<script type="text/javascript" language="JavaScript" src="http://twittercounter.com/embed/?username=';
			$str .= $tc_settings['username'];
			if ( '' != $style ) {
				$str .= '&amp;style=';
				$str .= $style;
			}
			$str .= '"></script>';
		}
	}

	return apply_filters( 'ald_tc', $str );
}


/**
 * Function to echo Twitter Counter code. Also add an action called echo_tc so that it can be called using do_action( 'echo_tc' ).
 */
function echo_twittercounter( $args ) {
	echo ald_tc( $args );
}
add_action( 'echo_tc', 'echo_twittercounter' );


/**
 * Function to add Twitter Widget code.
 *
 * @param array $args Parameters in a query string format
 * @return string Twitter Widget code
 */
function ald_tr( $args = array() ) {
	global $tc_settings;

	$defaults = array(
		'username' => $tc_settings['username'],
		'twitter_id' => $tc_settings['twitter_id'],
		'users_id' => $tc_settings['users_id'],
		'width' => $tc_settings['width'],
		'nr_show' => $tc_settings['nr_show'],
		'hr_color' => $tc_settings['hr_color'],
		'a_color' => $tc_settings['a_color'],
		'bg_color' => $tc_settings['bg_color'],
	);

	// Parse incomming $args into an array and merge it with $defaults
	$args = wp_parse_args( $args, $defaults );

	// OPTIONAL: Declare each item in $args as its own variable i.e. $type, $before.
	extract( $args, EXTR_SKIP );

	$str = '<div class="twittercounter_widget">';

	if ( '' == $username ) {
		$str .= __( 'Please visit WP-Admin &gt; Settings &gt; Twitter Counter and enter your Twitter username.', 'twittercounter' );
	} elseif ( '' != $users_id ) {
		$str .= "
			<script type=\"text/javascript\" id=\"tcws_" . $users_id . "\">(function(){function async_load(){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src='http://twittercounter.com/remote/?v=2&twitter_id=" . $users_id . "&width=" . $width . "&nr_show=" . $nr_show . "&hr_color=" . $hr_color . "&a_color=" . $a_color . "&bg_color=" . $bg_color . "';x=document.getElementById('tcws_" . $users_id . "'); x.parentNode.insertBefore(s,x);}if(window.attachEvent){window.attachEvent('onload',async_load);}else{window.addEventListener('load',async_load,false);}})(); </script>
				<noscript><a href=\"http://twittercounter.com/" . $username . "\">@" . $username . " on TwitterCounter.com</a></noscript>
			<div id=\"tcw_" . $users_id . "\"></div>
		";
	} else {
		$str = '<script type="text/javascript" language="javascript" src="http://twittercounter.com/remote/?username_owner=';
		$str .= $username;
		$str .= '&amp;users_id=';
		$str .= $users_id;
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

	$str .= '<br /><small>';
	$str .= sprintf( __( '<strong style="%1$s">Get <a href="http://ajaydsouza.com/wordpress/plugins/twittercounter/" style="%2$s" rel="nofollow">Twitter Counter WordPress Plugin</a></strong>', 'twittercounter' ), 'color:#' . $hr_color, 'color:#' . $a_color );
	$str .= '</small></div>';

	return apply_filters( 'ald_tr', $str );
}

/**
 * Echo Twitter Widget code. Add an action called echo_twitter_widget so that it can be called using do_action( 'echo_twitter_widget' ).
 */
function echo_tr_function( $args ) {
	echo ald_tr( $args );
}
add_action( 'echo_twitter_widget', 'echo_tr_function' );


/**
 * Add Twitter Counter CSS to theme header. Filters `wp_head`.
 */
function tc_header() {
	global $wpdb, $post, $single, $tc_settings;

	$tc_custom_CSS = stripslashes( $tc_settings['custom_CSS'] );

	// Add CSS to header
	if ( ( '' != $tc_custom_CSS ) && ( '' != $tc_settings['username'] ) && ( 'script_only' == $tc_settings['style'] ) ) {
		echo '<style type="text/css">' . $tc_custom_CSS . '</style>';
	}
}
add_action( 'wp_head', 'tc_header' );


/**
 * Default options.
 */
function tc_default_options() {
	$tc_settings = 	array (
		'username' => '',			// Twitter Username
		'twitter_id' => '',			// twitter id
		'style' => 'custom',		// Twitter Counter style
		'users_id' => '',			// Twitter Counter userid
		'a_color' => '709cb2',		// Twitter Widget Text and Hyperlink
		'hr_color' => '709CB2',		// Twitter Widget Horizontal rules
		'bg_color' => 'ffffff',		// Twitter Widget Background Color
		'nr_show' => '6',			// Twitter Widget Number of Rows
		'width' => '220',			// Twitter Widget Width
		'tc_hr_color' => 'ffffff',	// Twitter Counter Hr Color
		'tc_bg_color' => '111111',	// Twitter Counter Background Color
		'custom_CSS' => '',			// Custom CSS
	);

	return apply_filters( 'tc_default_options', $tc_settings );
}


/**
 * Function to read options from the database.
 */
function tc_read_options() {

	$tc_settings_changed = false;

	$defaults = tc_default_options();

	$tc_settings = array_map( 'stripslashes', (array) get_option('ald_tc_settings') );
	unset( $tc_settings[0] ); // produced by the (array) casting when there's nothing in the DB

	foreach ( $defaults as $k=>$v ) {
		if ( ! isset( $tc_settings[$k] ) ) {
			$tc_settings[$k] = $v;
		}
		$tc_settings_changed = true;
	}
	if ( true == $tc_settings_changed ) {
		update_option( 'ald_tc_settings', $tc_settings );
	}

	return apply_filters( 'tc_read_options', $tc_settings );
}


/**
 * This function reads Twitter Counter API.
 *
 * @access public
 * @param string $twitter_id (default: '3412651')
 * @return void
 */
function twittercounter_api( $twitter_id= '3412651' ) {

	$api_call = 'http://api.twittercounter.com/?apikey=e9335031a759f251ee9b4e2e6634e1c5&twitter_id=' . $twitter_id;

	$response = wp_remote_get( $api_call );
	if ( ! is_wp_error( $response ) && isset( $response['body'] ) ) {
		$body = $response['body'];
		$twitter_info = json_decode( $body );
	}

	return apply_filters( 'twittercounter_api', $twitter_info );
}


/**
 * Create a Wordpress Widget for Twitter Counter.
 *
 * @extends WP_Widget
 */
class WidgetTC extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'widget_twittercounter', // Base ID
			__( 'Twitter Counter', 'twittercounter' ), // Name
			array( 'description' => __( 'Display Twitter Counter button', 'twittercounter' ), ) // Args
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$style = isset( $instance['style'] ) ? esc_attr( $instance['style'] ) : 'avatar';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title', 'twittercounter' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p>
			<?php _e( 'Style', 'twittercounter' ); ?>: <br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
			  <option value="avatar" <?php if ( 'avatar' == $style ) { echo 'selected="selected"'; } ?>><?php _e( 'Avatar', 'twittercounter' ); ?></option>
			  <option value="bird" <?php if ( 'bird' == $style ) { echo 'selected="selected"'; } ?>><?php _e( 'Big Bird', 'twittercounter' ); ?></option>
			  <option value="custom" <?php if ( 'custom' == $style ) { echo 'selected="selected"'; } ?>><?php _e( 'Classic', 'twittercounter' ); ?></option>
			  <option value="script_only" <?php if ( 'script_only' == $style ) { echo 'selected="selected"'; } ?>><?php _e( 'Count only', 'twittercounter' ); ?></option>
			</select>
		</p>
		<?php
	} //ending form creation

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = strip_tags( $new_instance['style'] );
		return $instance;
	} //ending update

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		global $wpdb, $tc_settings;

		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$style = $instance['style'];
		if ( empty( $style ) ) {
			$style = $tc_settings['style'];
		}

		echo $before_widget;
		echo $before_title . $title . $after_title;

		echo ald_tc( $style );
		echo $after_widget;

	} //ending function widget
} // End WidgetTC


/**
 * Create a Wordpress Widget for Twitter Widget.
 *
 * @extends WP_Widget
 */
class WidgetTW extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'widget_twitterwidget', // Base ID
			__( 'Twitter Widget', 'twittercounter' ), // Name
			array( 'description' => __( 'Display Twitter Widget', 'twittercounter' ), ) // Args
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$width = isset( $instance['width'] ) ? esc_attr( $instance['width'] ) : '';
		$nr_show = isset( $instance['nr_show'] ) ? esc_attr( $instance['nr_show'] ) : '';
		$hr_color = isset( $instance['hr_color'] ) ? esc_attr( $instance['hr_color'] ) : '';
		$a_color = isset( $instance['a_color'] ) ? esc_attr( $instance['a_color'] ) : '';
		$bg_color = isset( $instance['bg_color'] ) ? esc_attr( $instance['bg_color'] ) : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title', 'twittercounter' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p><?php _e('Leave the below options blank to use the default settings', 'twittercounter'); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">
				<?php _e( 'Width', 'twittercounter' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'nr_show' ); ?>">
				<?php _e( 'Number of rows', 'twittercounter' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'nr_show' ); ?>" name="<?php echo $this->get_field_name( 'nr_show' ); ?>" type="text" value="<?php echo esc_attr( $nr_show ); ?>" />
			</label>
		</p>
		<p><em><?php _e('Enter 6 character hexadecimal code without the # for the below options. e.g. for white, enter FFFFFF', 'twittercounter'); ?></em></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hr_color' ); ?>">
				<?php _e( 'Header Color', 'twittercounter' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'hr_color' ); ?>" name="<?php echo $this->get_field_name( 'hr_color' ); ?>" type="text" value="<?php echo esc_attr( $hr_color ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'a_color' ); ?>">
				<?php _e( 'Text and links Color', 'twittercounter' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'a_color' ); ?>" name="<?php echo $this->get_field_name( 'a_color' ); ?>" type="text" value="<?php echo esc_attr( $a_color ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'bg_color' ); ?>">
				<?php _e( 'Background Color', 'twittercounter' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" type="text" value="<?php echo esc_attr( $bg_color ); ?>" />
			</label>
		</p>

		<?php
	} //ending form creation

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['width'] = strip_tags( $new_instance['width'] );
		$instance['nr_show'] = strip_tags( $new_instance['nr_show'] );
		$instance['hr_color'] = strip_tags( $new_instance['hr_color'] );
		$instance['a_color'] = strip_tags( $new_instance['a_color'] );
		$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		return $instance;
	} //ending update

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		global $wpdb, $tc_settings;

		extract($args, EXTR_SKIP);

		$title = apply_filters( 'widget_title', $instance['title'] );

		$style = empty( $instance['style'] ) ? $tc_settings['style'] : $instance['style'];
		$width = empty( $instance['width'] ) ? $tc_settings['width'] : intval( $instance['width'] );
		$nr_show = empty( $instance['nr_show'] ) ? $tc_settings['nr_show'] : intval( $instance['nr_show'] );
		$hr_color = empty( $instance['hr_color'] ) ? $tc_settings['hr_color'] : $instance['hr_color'];
		$a_color = empty( $instance['a_color'] ) ? $tc_settings['a_color'] : $instance['a_color'];
		$bg_color = empty( $instance['bg_color'] ) ? $tc_settings['bg_color'] : $instance['bg_color'];

		echo $before_widget;
		echo $before_title . $title . $after_title;

		echo ald_tr( array(
			'width' => $width,
			'nr_show' => $nr_show,
			'hr_color' => $hr_color,
			'a_color' => $a_color,
			'bg_color' => $bg_color,
		) );
		echo $after_widget;

	} //ending function widget
}


/**
 * Initialise the widgets.
 */
function register_tc_widgets() {
	register_widget( 'WidgetTC' );
	register_widget( 'WidgetTW' );
}
add_action( 'widgets_init', 'register_tc_widgets' );


/**
 *  Admin options
 *
 */
if ( is_admin() || strstr( $_SERVER['PHP_SELF'], 'wp-admin/' ) ) {

	/**
	 *  Load the admin pages if we're in the Admin.
	 *
	 */
	require_once( ALD_TC_DIR . "/admin.inc.php" );

	/**
	 * Adding WordPress plugin action links.
	 *
	 * @param array $links
	 * @return array
	 */
	function tc_plugin_actions_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=tc_options' ) . '">' . __( 'Settings', 'twittercounter' ) . '</a>'
			),
			$links
		);

	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tc_plugin_actions_links' );

	/**
	 * Add meta links on Plugins page.
	 *
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	function tc_plugin_actions( $links, $file ) {
		$plugin = plugin_basename(__FILE__);

		// create link
		if ($file == $plugin) {
			$links[] = '<a href="http://wordpress.org/support/plugin/twittercounter">' . __( 'Support', 'twittercounter' ) . '</a>';
			$links[] = '<a href="http://ajaydsouza.com/donate/">' . __( 'Donate', 'twittercounter' ) . '</a>';
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'tc_plugin_actions', 10, 2 ); // only 2.8 and higher

} // End admin.inc

?>