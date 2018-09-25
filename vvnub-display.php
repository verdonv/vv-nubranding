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


class VVNUB_Display {

    /*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/

    /** Refers to a single instance of this class. */
    private static $instance = null;

    /* Saved options */
    public $options;


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
		$this->options = (object)get_option( 'vvnub_settings' );

		// add the css
		add_action( 'wp_enqueue_scripts', array( $this, 'vvnub_que_css' ) );

		// add the html
		if ($this->options->vvnub_dispw == 'above') {
			add_action( 'get_footer', array( &$this, 'vvnub_print_nubranding' ) );
		} elseif ($this->options->vvnub_dispw == 'below') {
			add_action( 'wp_footer', array( &$this, 'vvnub_print_nubranding' ) );
		} elseif ($this->options->vvnub_dispw == 'head') {
			add_action( 'get_header', array( &$this, 'vvnub_print_nubranding' ) );
		}

    }


    /*--------------------------------------------*
     * Functions
     *--------------------------------------------*/

	// ADD THE BASE CSS
	public function vvnub_que_css () {
		$handle = 'vv_nubranding_css';
		$src = VVNUB__PLUGIN_URL . 'css/vv-nubranding.css';
		wp_enqueue_style( $handle, $src );
	}

	// RENDER THE HTML
	public function vvnub_print_nubranding() {
	?>

	<style type="text/css">
		#nuFooterWrap {
			background-color: <?php echo $this->options->vvnub_bgcol ?>; 
			color: <?php echo $this->options->vvnub_fgcol1 ?>;
			width: <?php echo $this->options->vvnub_width . $this->options->vvnub_wunit ?>;
			margin: 0 auto 0 auto;
		}
		#nuFooterLogo {
			<?php if ($this->options->vvnub_flogo !== 'sch') { ?>
				background-image: url(<?php echo VVNUB__PLUGIN_URL ?>images/footer_<?php echo $this->options->vvnub_flogo ?>.png);
			<?php } else { ?>
					width: 0px;
			<?php } ?>
		}
		#nuFooterWrap a, #nuFooterWrap a:active, #nuFooterWrap a:visited {
			color: <?php echo $this->options->vvnub_linkcol ?>;
		}
	</style>

	<div id="nuFooterWrap">
		<div style="clear: both">
		</div>
		<div id="nuFooterInner">
			<div id="nuFooterLogo">
				&nbsp;
			</div>
			<div id="nuFooterAddress">
				<?php if ($this->options->vvnub_flogo == 'sch') { ?>
					<img src="<?php echo VVNUB__PLUGIN_URL ?>images/footer_<?php echo $this->options->vvnub_flogo ?>.png" width="409" height="49" alt="Nipissing | Schulich" /><br /><br />
				<?php } ?>
				<strong><a href="https://www.nipissingu.ca/">Nipissing University</a></strong> <br />
				100 College Drive, Box 5002 <br />
				North Bay, ON, Canada <br />
				P1B 8L7 <br />
				Tel: 705.474.3450<br />
				Fax: 705.474.1947<br />
				TTY: 877.688.5507<br />
			</div>
			<div id="nuFooterLinks">
				<div id="nuLinks1">
					<?php if ($this->options->vvnub_showlink_5) { ?>
						<a href="https://www.nipissingu.ca/users">Phone Directory</a><br />
					<?php } ?>
					<?php if ($this->options->vvnub_showlink_6) { ?>
						<a href="https://mail.nipissingu.ca/">NU Mail</a><br />
					<?php } ?>
					<?php if ($this->options->vvnub_showlink_7) { ?>
						<a href="https://www.nipissingu.ca/departments/human-resources/health-and-safety">Health &amp; Safety</a><br />
					<?php } ?>
					<?php if ($this->options->vvnub_showlink_8) { ?>
						<a href="https://www.nipissingu.ca/site-information/site-map">Site Map</a><br />
					<?php } ?>
					<?php 
						if ($this->options->vvnub_customlink_1_disp == 'col2') { 
							echo ('<a href="' . esc_url ($this->options->vvnub_customlink_1_url) . '">');
							if ($this->options->vvnub_customlink_1_label) {
								echo (sanitize_text_field ($this->options->vvnub_customlink_1_label));
							} else {
								echo (esc_url_raw ($this->options->vvnub_customlink_1_url));
							}
							echo ('</a><br />');
						} 
						if ($this->options->vvnub_customlink_2_disp == 'col2') { 
							echo ('<a href="' . esc_url ($this->options->vvnub_customlink_2_url) . '">');
							if ($this->options->vvnub_customlink_2_label) {
								echo (sanitize_text_field ($this->options->vvnub_customlink_2_label));
							} else {
								echo (esc_url_raw ($this->options->vvnub_customlink_2_url));
							}
							echo ('</a><br />');
						} 
					?>
				</div>
				<div id="nuLinks2">
					<?php if ($this->options->vvnub_showlink_1) { ?>
						<a href="https://my.nipissingu.ca/">MyNipissing</a><br />
					<?php } ?>
					<?php if ($this->options->vvnub_showlink_2) { ?>
						<a href="https://webadvisor.nipissingu.ca/">WebAdvisor</a><br />
					<?php } ?>
					<?php if ($this->options->vvnub_showlink_3) { ?>
						<a href="https://learn.nipissingu.ca/">Blackboard</a><br />
					<?php } ?>
					<?php if ($this->options->vvnub_showlink_4) { ?>
						<a href="https://www.eclibrary.ca/">Library</a><br />
					<?php } ?>
					<?php 
						if ($this->options->vvnub_customlink_1_disp == 'col1') { 
							echo ('<a href="' . esc_url ($this->options->vvnub_customlink_1_url) . '">');
							if ($this->options->vvnub_customlink_1_label) {
								echo (sanitize_text_field ($this->options->vvnub_customlink_1_label));
							} else {
								echo (esc_url_raw ($this->options->vvnub_customlink_1_url));
							}
							echo ('</a><br />');
						} 
						if ($this->options->vvnub_customlink_2_disp == 'col1') { 
							echo ('<a href="' . esc_url ($this->options->vvnub_customlink_2_url) . '">');
							if ($this->options->vvnub_customlink_2_label) {
								echo (sanitize_text_field ($this->options->vvnub_customlink_2_label));
							} else {
								echo (esc_url_raw ($this->options->vvnub_customlink_2_url));
							}
							echo ('</a><br />');
						} 
					?>
				</div>
			</div>
		</div>
		<div style="clear: both">
		</div>
	</div>

	<?php
	}

}


VVNUB_Display::get_instance();

