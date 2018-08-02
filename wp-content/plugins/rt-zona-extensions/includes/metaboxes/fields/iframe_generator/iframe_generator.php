<?php
/**
 * Muttley Framework
 *
 * @package     MuttleyBox
 * @subpackage  iframe_generator
 * @author      Mariusz Rek
 * @version     2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MuttleyBox_iframe_generator' ) ) {
	class MuttleyBox_iframe_generator extends MuttleyBox {

		private static $_initialized = false;
		private static $_args;
		private static $_saved_options;
		private static $_option;


		/**
         * Field Constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
        */
		public function __construct( $option, $args, $saved_options ) {
			
			// Variables
			self::$_args = $args;
			self::$_saved_options = $saved_options;
			self::$_option = $option;

			// Only for first instance
			if ( ! self::$_initialized ) {
	            self::$_initialized = true;

	            // Enqueue
				add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue' ) );

	            // Ajax
	            add_action( 'wp_ajax_easy_link_ajax', array( &$this, 'easy_link_ajax' ) );            
	            
	        }

		}

		/**
         * Enqueue Function.
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
        */
		public function enqueue() {
			
			// Admin Footer
			add_action( 'admin_footer', array( &$this, 'admin_footer' ) );

			$path = self::$_args['admin_path'];

			// Load script
			$handle = self::$_option['type'] . '.js';
			if ( ! wp_script_is( $handle, 'enqueued' ) ) {
				wp_enqueue_script( $handle, $path . '/fields/' . self::$_option['type'] . '/' . self::$_option['type'] . '.js', false, false, true );
			}
			
		}


		/**
         * Render HTML code in admin footer
         *
         * @since 		1.0.0
         * @access  	public
        */
		public function admin_footer() {
			$this->widget();
		}


		/**
         * Field Render Function.
         * Takes the vars and outputs the HTML
         *
         * @since 		1.0.0
         * @access  	public
        */
		public function render() {
			

			if ( isset( self::$_saved_options[self::$_option['id']] ) ) {
				self::$_option['std'] = self::$_saved_options[self::$_option['id']];
			}
			
			// Depedency
			if ( isset( self::$_option['dependency']) && is_array( self::$_option['dependency'] ) ) {
				echo '<div class="box-row clearfix dependent-hidden" data-depedency-el="' . esc_attr( self::$_option['dependency']['element'] ) .'" data-depedency-val="'.esc_attr( implode(',', self::$_option['dependency']['value'] ) ).'" data-id="' . esc_attr( self::$_option['id'] ) . '">';
			} else {
				echo '<div class="box-row clearfix">';
			}

				// Input Wrap
				echo '<div class="box-row-input">';

					// Label
					echo '<div class="box-tc box-tc-label">';
						if ( isset( self::$_option['name'] ) && ( self::$_option['name'] != '' ) ) {	
							echo '<label for="' . esc_attr( self::$_option['id'] ) . '" >' . esc_attr( self::$_option['name'] ) . '</label>';
						}
					echo '</div>';

					// Input
					echo '<div class="box-tc box-tc-input">';
						if ( isset( self::$_option['sub_name'] ) && ( self::$_option['sub_name'] != '' ) ) {	
							echo '<div class="sub-name">' . esc_attr( self::$_option['sub_name'] ) . '</div>';
						}

						// Field
						// ---------------------------------------

							if ( isset( self::$_option['std'] ) && self::$_option['std'] != '' ) {
					      		$display_i = 'display:block;';
								$display_g = 'display:none;';
								$display_d = 'display:inline-block;';
							} else { 
								$display_i = 'display:none;';
								$display_g = 'display:inline-block;';
								$display_d = 'display:none;';
							}

							echo '<div class="iframe-generator-wrap" style="' . esc_attr( $display_i ) . '" data-widget="#_' . esc_attr(self::$_option['type'] ) . '">';
							echo '<input type="text" id="' . esc_attr( self::$_option['id'] ) . '" name="' . esc_attr( self::$_option['id'] ) . '" class="iframe-generator-input" value="' . esc_attr( self::$_option['std'] ) . '" />';
							echo '</div>';
							echo '<button class="_button special-button generate-iframe" style="' . esc_attr( $display_g ) . '"><i class="fa icon fa-magic"></i>' . esc_attr_x( 'Generate Iframe', 'MuttleyBox Class', $this->textdomain ) . '</button>';
							
							echo '<button class="_button special-button ui-button-delete delete-iframe" style="' . esc_attr( $display_d ) . '"><i class="fa icon fa-trash-o"></i>' . esc_attr_x( 'Remove', 'MuttleyBox Class', $this->textdomain ) . '</button>';
							echo '<p class="msg msg-error" style="display:none;">' . esc_html_x( 'Error: Content does not contain the iframe.', 'MuttleyBox Class', $this->textdomain ) . '</p>';

						
						// ----------------------------------------

						// Display help
						if ( isset( self::$_option['desc'] ) && self::$_option['desc'] != '' ) {
							echo '<p class="help-box">';
							$this->e_esc( self::$_option['desc'] );
							echo '</p>';
						}
					echo '</div>';

				echo '</div>';

				if ( ! isset( self::$_option['separator'] ) || ( self::$_option['separator'] == true ) ) {	
					echo '<div class="box-row-line"></div>';
				}

			
			echo '</div>';
			
		}
		
		
		/* Widget
		---------------------------------------------- */
		private function widget() {
		  
			echo '<div id="_' . self::$_option['type'] . '" style="display:none" class="_iframe-generator muttleybox">';
			echo '<input type="hidden" autofocus="autofocus" />';

			echo '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="color">' . esc_html_x( 'Iframe Code', 'MuttleyBox Class', $this->textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
					<textarea id="iframe-content" name="iframe_content" style="min-height:100px"></textarea>
					<p class="help-box">' . esc_html_x( 'Paste Iframe code here.', 'MuttleyBox Class', $this->textdomain ) . '</p>
					</div>
				</div>
			</div>';

			echo '</div>';

		}

	}
}