<?php
/*
Author: Verdon Vaillancourt
Author URI: http://verdon.ca/
License: GPLv2 or later
Text Domain: vv-nubranding
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

class VVNUB_Settings {

    /*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/

    /** Refers to a single instance of this class. */
    private static $instance = null;

    /* Saved options */
    public $options;

    private static $defaults = array(
		'vvnub_dispw'				=> 'above',
		'vvnub_flogo'				=> 'coa',
		'vvnub_bgcol'				=> '#ffffff',
		'vvnub_fgcol1'				=> '#000000',
		'vvnub_linkcol'				=> '#999999',
		'vvnub_width'				=> '100',
		'vvnub_wunit'				=> '%',
		'vvnub_showlink_1'			=> '1',
		'vvnub_showlink_2'			=> '1',
		'vvnub_showlink_3'			=> '1',
		'vvnub_showlink_4'			=> '1',
		'vvnub_showlink_5'			=> '1',
		'vvnub_showlink_6'			=> '1',
		'vvnub_showlink_7'			=> '1',
		'vvnub_showlink_8'			=> '1',
		'vvnub_customlink_1_disp'	=> 'hide',
		'vvnub_customlink_1_label'	=> '',
		'vvnub_customlink_1_url'	=> '',
		'vvnub_customlink_2_disp'	=> 'hide',
		'vvnub_customlink_2_label'	=> '',
		'vvnub_customlink_2_url'	=> '',
		'vvnub_reset'				=> '0',
		'vvnub_clearout'			=> '0'
	);


    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    // CREATE OR RETURN AN INSTANCE OF THE CLASS
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    } // end get_instance;


	// INITIALIZE THE CLASS
    private function __construct() {

		// get our settings
		$this->options = (object) get_option( 'vvnub_settings', self::$defaults );

		// add page to admin menu
		add_action( 'admin_menu', array( $this, 'vvnub_add_admin_page' ) );

		// register page options
		add_action( 'admin_init', array( $this, 'vvnub_settings_init' ) );
    }


    /*--------------------------------------------*
     * Functions
     *--------------------------------------------*/

    // ADD PAGE UNDER SETTINGS MENU
	public function vvnub_add_admin_page() {
		$page = add_options_page(
			'NU Branding', // Page title
			'NU Branding', // Menu title
			'manage_options', // capability
			'vv_nubranding_options', // menu slug
			array( $this, 'vvnub_options_page' ) // Callback
		);

		add_action( "load-{$page}", array( $this, 'vvnub_enqueue_admin_js') );
	}

    // RENDER THE ADMIN PAGE
	public function vvnub_options_page() {
		?>
        <div class="wrap">
			<h2>Nipissing University Branding</h2>
			<form action='options.php' method='post'>
			<?php
				settings_fields('vvnub_settings_group');
				do_settings_sections('vv_nubranding_options');
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

    // REGISTER ADMIN PAGE OPTIONS
	public function vvnub_settings_init() {

		register_setting(
			'vvnub_settings_group', // option group
			'vvnub_settings', // option name
			array( $this, 'vvnub_validate_options' ) // sanitize
		);

		add_settings_section(
			'vvnub_options_section', // ID
			__( 'Choose Branding Options', 'vv-nubranding' ), // Title
			array( $this, 'vvnub_settings_section_callback' ), // Callback
			'vv_nubranding_options' // page
		);

		add_settings_section(
			'vvnub_links_section', // ID
			__( 'Choose Link Options', 'vv-nubranding' ), // Title
			array( $this, 'vvnub_links_section_callback' ), // Callback
			'vv_nubranding_options' // page
		);

		add_settings_section(
			'vvnub_admin_section', // ID
			__( 'Administrative Options', 'vv-nubranding' ), // Title
			array( $this, 'vvnub_admin_section_callback' ), // Callback
			'vv_nubranding_options' // page
		);

		add_settings_field( // display where
			'vvnub_dispw', // ID
			__( 'Display NU footer where', 'vv-nubranding' ), // Title
			array( $this, 'vvnub_dispw_render' ), // Callback
			'vv_nubranding_options', // Page
			'vvnub_options_section' // Section
		);

		add_settings_field( // logo
			'vvnub_flogo',
			__( 'Footer logo to use', 'vv-nubranding' ),
			array( $this, 'vvnub_flogo_render' ),
			'vv_nubranding_options',
			'vvnub_options_section'
		);

		add_settings_field( // bg colour
			'vvnub_bgcol',
			__( 'Background colour', 'vv-nubranding' ),
			array( $this, 'vvnub_bgcol_render' ),
			'vv_nubranding_options',
			'vvnub_options_section'
		);

		add_settings_field( // main foreground colour
			'vvnub_fgcol1',
			__( 'Foreground colour', 'vv-nubranding' ),
			array( $this, 'vvnub_fgcol1_render' ),
			'vv_nubranding_options',
			'vvnub_options_section'
		);

		add_settings_field( // link colour
			'vvnub_linkcol',
			__( 'Link colour', 'vv-nubranding' ),
			array( $this, 'vvnub_linkcol_render' ),
			'vv_nubranding_options',
			'vvnub_options_section'
		);

		add_settings_field( // width
			'vvnub_width',
			__( 'NU footer width', 'vv-nubranding' ),
			array( $this, 'vvnub_width_render' ),
			'vv_nubranding_options',
			'vvnub_options_section'
		);

		add_settings_field( // unit
			'vvnub_wunit',
			__( 'Width unit', 'vv-nubranding' ),
			array( $this, 'vvnub_wunit_render' ),
			'vv_nubranding_options',
			'vvnub_options_section'
		);



		add_settings_field( // link 1
			'vvnub_showlink_1',
			__( 'Show MyNipissing link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_1_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // link 2
			'vvnub_showlink_2',
			__( 'Show WebAdvisor link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_2_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // link 3
			'vvnub_showlink_3',
			__( 'Show Blackboard link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_3_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // link 4
			'vvnub_showlink_4',
			__( 'Show Library link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_4_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // link 5
			'vvnub_showlink_5',
			__( 'Show Phone Directory link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_5_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // link 6
			'vvnub_showlink_6',
			__( 'Show NU Mail link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_6_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // link 7
			'vvnub_showlink_7',
			__( 'Show Health & Safety link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_7_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // link 8
			'vvnub_showlink_8',
			__( 'Show Site Map link', 'vv-nubranding' ),
			array( $this, 'vvnub_showlink_8_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // custom link 1 display
			'vvnub_customlink_1_disp',
			__( 'Display custom link 1', 'vv-nubranding' ),
			array( $this, 'vvnub_customlink_1_disp_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // custom link 1 label
			'vvnub_customlink_1_label',
			__( 'Label for custom link 1', 'vv-nubranding' ),
			array( $this, 'vvnub_customlink_1_label_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // custom link 1 url
			'vvnub_customlink_1_url',
			__( 'URL for custom link 1', 'vv-nubranding' ),
			array( $this, 'vvnub_customlink_1_url_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // custom link 2 display
			'vvnub_customlink_2_disp',
			__( 'Display custom link 2', 'vv-nubranding' ),
			array( $this, 'vvnub_customlink_2_disp_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // custom link 2 label
			'vvnub_customlink_2_label',
			__( 'Label for custom link 2', 'vv-nubranding' ),
			array( $this, 'vvnub_customlink_2_label_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);

		add_settings_field( // custom link 2 url
			'vvnub_customlink_2_url',
			__( 'URL for custom link 2', 'vv-nubranding' ),
			array( $this, 'vvnub_customlink_2_url_render' ),
			'vv_nubranding_options',
			'vvnub_links_section'
		);



		add_settings_field( // reset defaults
			'vvnub_reset',
			__( 'RESET ALL TO DEFAULT', 'vv-nubranding' ),
			array( $this, 'vvnub_reset_render' ),
			'vv_nubranding_options',
			'vvnub_admin_section'
		);

		add_settings_field( // delete settings on deactivate
			'vvnub_clearout',
			__( 'Clear stored settings from database when deactivating this plugin', 'vv-nubranding' ),
			array( $this, 'vvnub_clearout_render' ),
			'vv_nubranding_options',
			'vvnub_admin_section'
		);

	}

	// RENDER THE DISPLAY WHERE OPTION FIELD
	public function vvnub_dispw_render(  ) {
		?>
		<select name='vvnub_settings[vvnub_dispw]' id='vvnub_settings[vvnub_dispw]'>
			<option value='head' <?php selected( $this->options->vvnub_dispw, 'head' ); ?>>Above the theme header</option>
			<option value='above' <?php selected( $this->options->vvnub_dispw, 'above' ); ?>>Above the theme footer</option>
			<option value='below' <?php selected( $this->options->vvnub_dispw, 'below' ); ?>>Below the theme footer</option>
		</select>
		<?php
	}

	// RENDER THE LOGO OPTION FIELD
	public function vvnub_flogo_render(  ) {
		?>
		<select name='vvnub_settings[vvnub_flogo]' id='vvnub_settings[vvnub_flogo]'>
			<option value='coa' <?php selected( $this->options->vvnub_flogo, 'coa' ); ?>>Coat of Arms</option>
			<option value='nul' <?php selected( $this->options->vvnub_flogo, 'nul' ); ?>>NU Lakers</option>
		</select>
		<?php
	}

	// RENDER THE BG COLOUR DISPLAY OPTIONS
	public function vvnub_bgcol_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_bgcol]' id='vvnub_settings[vvnub_bgcol]' value='<?php echo $this->options->vvnub_bgcol ?>' class='vvnub-color-picker' />
		<?php
	}

	// RENDER THE FOREGROUND COLOUR DISPLAY OPTIONS
	public function vvnub_fgcol1_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_fgcol1]' id='vvnub_settings[vvnub_fgcol1]' value='<?php echo $this->options->vvnub_fgcol1 ?>' class='vvnub-color-picker' />
		<?php
	}

	// RENDER THE LINK COLOUR DISPLAY OPTIONS
	public function vvnub_linkcol_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_linkcol]' id='vvnub_settings[vvnub_linkcol]' value='<?php echo $this->options->vvnub_linkcol ?>' class='vvnub-color-picker' />
		<?php
	}

	// RENDER THE WIDTH DISPLAY OPTIONS
	public function vvnub_width_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_width]' id='vvnub_settings[vvnub_width]' value='<?php echo $this->options->vvnub_width ?>' size='4' maxlength='5' />
		<?php
	}

	// RENDER THE WIDTH UNIT OPTION FIELD
	public function vvnub_wunit_render(  ) {
		?>
		<select name='vvnub_settings[vvnub_wunit]' id='vvnub_settings[vvnub_wunit]'>
			<option value='%' <?php selected( $this->options->vvnub_wunit, '%' ); ?>>Percent</option>
			<option value='px' <?php selected( $this->options->vvnub_wunit, 'px' ); ?>>Pixels</option>
		</select>
		<?php
	}



	// RENDER THE MYNIPISSING LINK
	public function vvnub_showlink_1_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_1]' id='vvnub_settings[vvnub_showlink_1]' <?php checked( $this->options->vvnub_showlink_1, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE WEBADVISOR LINK
	public function vvnub_showlink_2_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_2]' id='vvnub_settings[vvnub_showlink_2]' <?php checked( $this->options->vvnub_showlink_2, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE BLACKBOARD LINK
	public function vvnub_showlink_3_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_3]' id='vvnub_settings[vvnub_showlink_3]' <?php checked( $this->options->vvnub_showlink_3, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE LIBRARY LINK
	public function vvnub_showlink_4_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_4]' id='vvnub_settings[vvnub_showlink_4]' <?php checked( $this->options->vvnub_showlink_4, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE PHONE DIRECTORY LINK
	public function vvnub_showlink_5_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_5]' id='vvnub_settings[vvnub_showlink_5]' <?php checked( $this->options->vvnub_showlink_5, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE NU MAIL LINK
	public function vvnub_showlink_6_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_6]' id='vvnub_settings[vvnub_showlink_6]' <?php checked( $this->options->vvnub_showlink_6, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE HEALTH & SAFETY LINK
	public function vvnub_showlink_7_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_7]' id='vvnub_settings[vvnub_showlink_7]' <?php checked( $this->options->vvnub_showlink_7, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE SITE MAP LINK
	public function vvnub_showlink_8_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_showlink_8]' id='vvnub_settings[vvnub_showlink_8]' <?php checked( $this->options->vvnub_showlink_8, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE CUSTOM LINK 1 DISPLAY OPTION
	public function vvnub_customlink_1_disp_render(  ) {
		?>
		<select name='vvnub_settings[vvnub_customlink_1_disp]' id='vvnub_settings[vvnub_customlink_1_disp]'>
			<option value='hide' <?php selected( $this->options->vvnub_customlink_1_disp, 'hide' ); ?>>Hide</option>
			<option value='col1' <?php selected( $this->options->vvnub_customlink_1_disp, 'col1' ); ?>>Column 1</option>
			<option value='col2' <?php selected( $this->options->vvnub_customlink_1_disp, 'col2' ); ?>>Column 2</option>
		</select>
		<?php
	}

	// RENDER THE CUSTOM LINK 1 LABEL
	public function vvnub_customlink_1_label_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_customlink_1_label]' id='vvnub_settings[vvnub_customlink_1_label]' value='<?php echo $this->options->vvnub_customlink_1_label ?>' size='16' maxlength='30' />
		<?php
	}

	// RENDER THE CUSTOM LINK 1 URL
	public function vvnub_customlink_1_url_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_customlink_1_url]' id='vvnub_settings[vvnub_customlink_1_url]' value='<?php echo $this->options->vvnub_customlink_1_url ?>' size='32' maxlength='150' />
		<?php
	}

	// RENDER THE CUSTOM LINK 2 DISPLAY OPTION
	public function vvnub_customlink_2_disp_render(  ) {
		?>
		<select name='vvnub_settings[vvnub_customlink_2_disp]' id='vvnub_settings[vvnub_customlink_2_disp]'>
			<option value='hide' <?php selected( $this->options->vvnub_customlink_2_disp, 'hide' ); ?>>Hide</option>
			<option value='col1' <?php selected( $this->options->vvnub_customlink_2_disp, 'col1' ); ?>>Column 1</option>
			<option value='col2' <?php selected( $this->options->vvnub_customlink_2_disp, 'col2' ); ?>>Column 2</option>
		</select>
		<?php
	}

	// RENDER THE CUSTOM LINK 2 LABEL
	public function vvnub_customlink_2_label_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_customlink_2_label]' id='vvnub_settings[vvnub_customlink_2_label]' value='<?php echo $this->options->vvnub_customlink_2_label ?>' size='16' maxlength='30' />
		<?php
	}

	// RENDER THE CUSTOM LINK 2 URL
	public function vvnub_customlink_2_url_render(  ) {
		?>
		<input type='text' name='vvnub_settings[vvnub_customlink_2_url]' id='vvnub_settings[vvnub_customlink_2_url]' value='<?php echo $this->options->vvnub_customlink_2_url ?>' size='32' maxlength='150' />
		<?php
	}



	// RENDER THE RESET TO DEFAULT DISPLAY OPTIONS
	public function vvnub_reset_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_reset]' id='vvnub_settings[vvnub_reset]' <?php checked( $this->options->vvnub_reset, 1 ); ?> value='1' />
		<?php
	}

	// RENDER THE RESET TO DEFAULT DISPLAY OPTIONS
	public function vvnub_clearout_render(  ) {
		?>
		<input type='checkbox' name='vvnub_settings[vvnub_clearout]' id='vvnub_settings[vvnub_clearout]' <?php checked( $this->options->vvnub_clearout, 1 ); ?> value='1' />
		<?php
	}

    // ADD JAVASCRIPT FOR THE COLOUR PICKER
	public function vvnub_enqueue_admin_js() {
		// Make sure to add the wp-color-picker dependecy to js file

		// add the css for the colour picker
		wp_enqueue_style( 'wp-color-picker' );

		// add the custom script
		wp_enqueue_script( 'vvnub_custom_js', plugins_url( 'js/vvnub-jquery.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  );
	}

    // VALIDATE THE FIELDS
    public function vvnub_validate_options( $fields ) {
		$valid_fields = array();

		if ($fields['vvnub_reset'] == 1) {
			$valid_fields = self::$defaults;
			return $valid_fields;
		}

		// just passing these right through as they are fixed input values
		$valid_fields['vvnub_dispw'] 				= $fields['vvnub_dispw'];
		$valid_fields['vvnub_flogo'] 				= $fields['vvnub_flogo'];
		$valid_fields['vvnub_wunit'] 				= $fields['vvnub_wunit'];
		$valid_fields['vvnub_showlink_1'] 			= $fields['vvnub_showlink_1'];
		$valid_fields['vvnub_showlink_2'] 			= $fields['vvnub_showlink_2'];
		$valid_fields['vvnub_showlink_3'] 			= $fields['vvnub_showlink_3'];
		$valid_fields['vvnub_showlink_4'] 			= $fields['vvnub_showlink_4'];
		$valid_fields['vvnub_showlink_5'] 			= $fields['vvnub_showlink_5'];
		$valid_fields['vvnub_showlink_6'] 			= $fields['vvnub_showlink_6'];
		$valid_fields['vvnub_showlink_7'] 			= $fields['vvnub_showlink_7'];
		$valid_fields['vvnub_showlink_8'] 			= $fields['vvnub_showlink_8'];

		$valid_fields['vvnub_customlink_1_disp'] 	= $fields['vvnub_customlink_1_disp'];
		$valid_fields['vvnub_customlink_1_label'] 	= sanitize_text_field( $fields['vvnub_customlink_1_label'] );
		$valid_fields['vvnub_customlink_1_url'] 	= esc_url_raw( $fields['vvnub_customlink_1_url'] );
		$valid_fields['vvnub_customlink_2_disp'] 	= $fields['vvnub_customlink_2_disp'];
		$valid_fields['vvnub_customlink_2_label'] 	= sanitize_text_field( $fields['vvnub_customlink_2_label'] );
		$valid_fields['vvnub_customlink_2_url'] 	= esc_url_raw( $fields['vvnub_customlink_2_url'] );

		// always zero this one back out
		$valid_fields['vvnub_reset'] 	= 0;
		$valid_fields['vvnub_clearout'] = $fields['vvnub_clearout'];

		// validate background color
		$background = trim( $fields['vvnub_bgcol'] );
		$background = strip_tags( stripslashes( $background ) );

		// check if it is a valid hex color
		if( FALSE === $this->check_color( $background ) ) {

			// set the error message
			add_settings_error( 'vvnub_settings', 'vvnub_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type

			// get the previous valid value
			$valid_fields['vvnub_bgcol'] = $this->options->vvnub_bgcol;
		} else {
			$valid_fields['vvnub_bgcol'] = $background;
		}

		// validate fg1 color
		$fg1 = trim( $fields['vvnub_fgcol1'] );
		$fg1 = strip_tags( stripslashes( $fg1 ) );
		if( FALSE === $this->check_color( $fg1 ) ) {
			add_settings_error( 'vvnub_settings', 'vvnub_fg1_error', 'Insert a valid color for Foreground', 'error' );
			$valid_fields['vvnub_fgcol1'] = $this->options->vvnub_fgcol1;
		} else {
			$valid_fields['vvnub_fgcol1'] = $fg1;
		}

		// validate link color
		$link = trim( $fields['vvnub_linkcol'] );
		$link = strip_tags( stripslashes( $link ) );
		if( FALSE === $this->check_color( $link ) ) {
			add_settings_error( 'vvnub_settings', 'vvnub_link_error', 'Insert a valid color for Link', 'error' );
			$valid_fields['vvnub_linkcol'] = $this->options->vvnub_linkcol;
		} else {
			$valid_fields['vvnub_linkcol'] = $link;
		}
		
		// validate width
		$width = trim( $fields['vvnub_width'] );
		$width = strip_tags( stripslashes( $width ) );
		// between 1-100 for %
		if ($valid_fields['vvnub_wunit'] == '%') {
			if ( !preg_match ('/^([1-9]|[1-9]\d|100)$/', $width) ) {
				add_settings_error( 'vvnub_settings', 'vvnub_width_error', 'You must use a number between 1 and 100 for percentage width', 'error' );
				$valid_fields['vvnub_width'] = $this->options->vvnub_width;
			} else {
				$valid_fields['vvnub_width'] = intval($width);
			}
		// legit number value for pixels
		} else {
			if ( !preg_match ('/^[0-9]+\.?[0-9]*$/', $width) ) {
				add_settings_error( 'vvnub_settings', 'vvnub_width_error', 'You must use a number for pixel width, no commas', 'error' );
				$valid_fields['vvnub_width'] = $this->options->vvnub_width;
			} else {
				$valid_fields['vvnub_width'] = intval($width);
			}
		}

		return $valid_fields;
    }

    // CHECK FOR VALID HEX COLOUR
    public function check_color( $value ) {
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
			return true;
		}

		return false;
    }

    // CALLBACK FOR SETTINGS SECTION
	public function vvnub_settings_section_callback(  ) {
		echo __( 'You may customize the following display options.', 'vv-nubranding' );
	}

    // CALLBACK FOR LINKS SECTION
	public function vvnub_links_section_callback(  ) {
		echo __( 'You may customize the following links options.', 'vv-nubranding' );
	}

    // CALLBACK FOR ADMIN SECTION
	public function vvnub_admin_section_callback(  ) {
		echo __( 'The following choices will reset options now, or tidy up vs. saving if deactivating this plugin.', 'vv-nubranding' );
	}

	public static function vvnub_activate() {
		if (get_option( 'vvnub_settings' ) == FALSE) {
			update_option ('vvnub_settings', self::$defaults);
		}
		update_option ('vvnub_version', VVNUB_VERSION);
	}

	public static function vvnub_deactivate() {
		$opts = (object) get_option( 'vvnub_settings' );
		$clear = $opts->vvnub_clearout;
		if ($clear == 1) {
			delete_option('vvnub_settings');
			delete_option('vvnub_version');
		}
	}

} // end class


VVNUB_Settings::get_instance();

