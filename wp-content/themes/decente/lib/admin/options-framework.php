<?php
/*
Plugin Name: Options Framework
Plugin URI: http://www.wptheming.com
Description: A framework for building theme options.
Version: 0.8
Author: Devin Price
Author URI: http://www.wptheming.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Basic plugin definitions */

define( 'OPTIONS_FRAMEWORK_VERSION', '0.9' );

/* If the user can't edit theme options, no use running this plugin */

add_action('init', 'frameshift_rolescheck' );

function frameshift_rolescheck() {

	if ( current_user_can( 'edit_theme_options' ) ) {
		add_action( 'admin_menu', 'frameshift_add_page' );
		add_action( 'admin_init', 'frameshift_init' );
		add_action( 'admin_init', 'frameshift_mlu_init' );
		add_action( 'admin_bar_menu', 'frameshift_options_link', 95 );
	}
	
}

/**
 *  Adds FrameShift options to admin bar
 *
 *  @package frameshift
 *  @since 0.8
 *
 */
 
function frameshift_options_link() { 

	global $wp_admin_bar;

	if ( !current_user_can('edit_theme_options') )
		return;
		
	$args = array( 
		'id' => 'frameshift_options_link',
		'title' => __('Theme Options', 'frameshift' ),
		'href' => admin_url( 'themes.php?page=frameshift' )
	);

	$wp_admin_bar->add_menu( $args );

}

/**
 * Loads the file for option sanitization
 */

add_action('init', 'frameshift_load_sanitization' );

function frameshift_load_sanitization() {

	require_once( trailingslashit( get_template_directory() ) . 'lib/admin/options-sanitize.php' );
	
}
 
/**
 * Create unique identifier to store the options in the database
 */
 
function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = wp_get_theme()->get( 'Name' );
	$themename = preg_replace( "/\W/", "", strtolower( $themename ) );
	
	$optionsframework_settings = get_option( 'frameshift' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'frameshift', $optionsframework_settings );
	
}

/* 
 * Creates the settings in the database by looping through the array
 * we supplied in options.php.  This is a neat way to do it since
 * we won't have to save settings for headers, descriptions, or arguments.
 *
 * Read more about the Settings API in the WordPress codex:
 * http://codex.wordpress.org/Settings_API
 *
 */

function frameshift_init() {

	// Include the required files
	require_once( trailingslashit( get_template_directory() ) . 'lib/admin/options-interface.php' );
	require_once( trailingslashit( get_template_directory() ) . 'lib/admin/options-medialibrary-uploader.php' );
	
	// Loads the options array from the theme
	require_once( trailingslashit( get_template_directory() ) . 'lib/admin/options.php' );
	
	$frameshift_settings = get_option( 'frameshift' );
	
	// Updates the unique option id in the database if it has changed
	optionsframework_option_name();
	
	// Gets the unique id, returning a default if it isn't defined
	if ( isset( $frameshift_settings['id'] ) ) {
		$option_name = $frameshift_settings['id'];
	}
	else {
		$option_name = 'frameshift';
	}
	
	// Registers the settings fields and callback
	register_setting( 'frameshift', $option_name, 'frameshift_validate' );
}

/* 
 * Adds default options to the database if they aren't already present.
 * May update this later to load only on plugin activation, or theme
 * activation since most people won't be editing the options.php
 * on a regular basis.
 *
 * http://codex.wordpress.org/Function_Reference/add_option
 *
 */
 
function frameshift_setdefaults() {
	
	$frameshift_settings = get_option( 'frameshift' );

	// Gets the unique option id
	$option_name = $frameshift_settings['id'];
	
	/* 
	 * Each theme will hopefully have a unique id, and all of its options saved
	 * as a separate option set.  We need to track all of these option sets so
	 * it can be easily deleted if someone wishes to remove the plugin and
	 * its associated data.  No need to clutter the database.  
	 *
	 */
	
	if ( isset( $frameshift_settings['knownoptions'] ) ) {
	
		$knownoptions =  $frameshift_settings['knownoptions'];
		
		if ( !in_array( $option_name, $knownoptions ) ) {
		
			array_push( $knownoptions, $option_name );
			$frameshift_settings['knownoptions'] = $knownoptions;
			update_option( 'frameshift', $frameshift_settings );
			
		}
		
	} else {
	
		$newoptionname = array( $option_name );
		$frameshift_settings['knownoptions'] = $newoptionname;
		update_option( 'frameshift', $frameshift_settings );
		
	}
	
	// Gets the default options data from the array in options.php
	$options = frameshift_options();
	
	// If the options haven't been added to the database yet, they are added now
	$values = frameshift_get_default_values();
	
	if ( isset( $values ) ) {
	
		add_option( $option_name, $values ); // Add option with default settings
		
	}
}

/**
 * Add a subpage called "Theme Options" to the appearance menu.
 */

if ( ! function_exists( 'frameshift_add_page' ) ) {

	function frameshift_add_page() {
	
		global $frameshift_page;
	
		$frameshift_page = add_theme_page( __( 'Theme Options', 'frameshift' ), __( 'Theme Options', 'frameshift' ), 'edit_theme_options', 'frameshift', 'frameshift_page' );
		
		// Adds actions to hook in the required css and javascript
		add_action( 'admin_print_styles-' . $frameshift_page , 'frameshift_load_styles' );
		add_action( 'admin_print_scripts-' . $frameshift_page, 'frameshift_load_scripts' );
		
		// Adds my_help_tab when FrameShift page loads
    	add_action( 'load-' . $frameshift_page, 'frameshift_add_help_tab' );
		
	}

}

/**
 * Add help tab to theme options page
 */

function frameshift_add_help_tab() {

    global $frameshift_page;
    
    $screen = get_current_screen();

    /*
     * Check if current screen is My Admin Page
     * Don't add help tab if it's not
     */
    if ( $screen->id != $frameshift_page )
        return;

    $screen->add_help_tab( array(
        'id'	=> 'frameshift_help',
        'title'	=> __( 'Theme Support', 'frameshift' ),
        'content'	=> '
        	<p>' . __( 'If you need help with the theme, please access the ThemeShift help center.', 'frameshift' ) . '</p>
        	<p>&rarr; <a href="http://themeshift.com/help/" target="_blank">' . __( 'Help Center', 'frameshift' ) . '</p>
        ',
    ) );
}

/**
 * Load the CSS
 */

function frameshift_load_styles() {
	wp_enqueue_style( 'admin-style', FRAMESHIFT_ADMIN_URL . '/css/admin-style.css' );
	wp_enqueue_style( 'color-picker', FRAMESHIFT_ADMIN_URL . '/css/colorpicker.css' );
}	

/**
 * Load the javascript
 */

function frameshift_load_scripts() {

	// Inline scripts from options-interface.php
	add_action( 'admin_head', 'frameshift_admin_head' );
	
	// Enqueued scripts
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'color-picker', FRAMESHIFT_ADMIN_URL . '/js/colorpicker.js', array( 'jquery' ) );
	wp_enqueue_script('options-custom', FRAMESHIFT_ADMIN_URL . '/js/options-custom.js', array( 'jquery' ) );
}

function frameshift_admin_head() {

	// Hook to add custom scripts
	do_action( 'frameshift_custom_scripts' );
}

/* 
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended we would use
 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
 * we'll call our own custom frameshift_fields.  See options-interface.php
 * for specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */

if ( ! function_exists( 'frameshift_page' ) ) {
function frameshift_page() {
	$return = frameshift_fields();
	settings_errors();
	?>
    
	<div class="wrap">
    <?php screen_icon( 'themes' ); ?>
    <h2 class="nav-tab-wrapper">
        <?php echo $return[1]; ?>
    </h2>
    
    <div class="metabox-holder">
    <div id="frameshift" class="postbox">
		<form action="options.php" method="post">
		<?php settings_fields('frameshift'); ?>

		<?php echo $return[0]; /* Settings */ ?>
        
        <div id="frameshift-submit">
			<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'frameshift' ); ?>" />
            <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'frameshift' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'frameshift' ) ); ?>' );" />
            <div class="clear"></div>
		</div>
	</form>
</div> <!-- / #container -->
</div>
</div> <!-- / .wrap -->

<?php
}
}

/** 
 * Validate Options.
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 * @uses $_POST['reset']
 * @uses $_POST['update']
 */
 
function frameshift_validate( $input ) {

	/*
	 * Restore Defaults.
	 *
	 * In the event that the user clicked the "Restore Defaults"
	 * button, the options defined in the theme's options.php
	 * file will be added to the option for the active theme.
	 */
	 
	if ( isset( $_POST['reset'] ) ) {
		if ( !count( get_settings_errors() ) )
			add_settings_error( 'frameshift', 'restore_defaults', __( 'Default options restored', 'frameshift' ), 'updated' );
		return frameshift_get_default_values();
	}

	/*
	 * Udpdate Settings.
	 */
	 
	if ( isset( $_POST['update'] ) ) {
		$clean = array();
		$options = frameshift_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = '0';
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = '0';
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'frameshift_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'frameshift_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		if ( !count( get_settings_errors() ) )
			add_settings_error( 'frameshift', 'save_options', __( 'Options saved', 'frameshift' ), 'updated' );
			
		return $clean;
	}

	/*
	 * Request Not Recognized.
	 */
	
	return frameshift_get_default_values();
}

/**
 * Format Configuration Array.
 *
 * Get an array of all default values as set in
 * options.php. The 'id','std' and 'type' keys need
 * to be defined in the configuration array. In the
 * event that these keys are not present the option
 * will not be included in this function's output.
 *
 * @return    array     Rey-keyed options configuration array.
 *
 * @access    private
 */
 
function frameshift_get_default_values() {

	$output = array();
	$config = frameshift_options();
	
	foreach ( (array) $config as $option ) {
		if ( ! isset( $option['id'] ) ) {
			continue;
		}
		if ( ! isset( $option['std'] ) ) {
			continue;
		}
		if ( ! isset( $option['type'] ) ) {
			continue;
		}
		if ( has_filter( 'frameshift_sanitize_' . $option['type'] ) ) {
			$output[$option['id']] = apply_filters( 'frameshift_sanitize_' . $option['type'], $option['std'], $option );
		}
	}
	
	return $output;
	
}