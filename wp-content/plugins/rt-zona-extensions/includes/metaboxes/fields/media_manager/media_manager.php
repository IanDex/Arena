<?php
/**
 * Muttley Framework
 *
 * @package     MuttleyBox
 * @subpackage  media_manager
 * @author      Mariusz Rek
 * @version     2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MuttleyBox_media_manager' ) ) {

	class MuttleyBox_media_manager extends MuttleyBox {

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

	           	/* Media Manager - Get data of single item */
				add_action( 'wp_ajax_mm_editor', array( &$this, 'mm_editor') );

				/* Media Manager - Save data of single item */
				add_action( 'wp_ajax_mm_editor_save', array( &$this, 'mm_editor_save') );

				/* Media Manager - Actions */
				add_action( 'wp_ajax_mm_actions', array( &$this, 'mm_actions') );       
	            
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

			// Load style
			$handle_css = self::$_option['type'] . '.css';
			if ( ! wp_style_is( $handle, 'enqueued' ) ) {
				wp_enqueue_style( $handle, $path . '/fields/' . self::$_option['type'] . '/' . self::$_option['type'] . '.css' );
			}
			
		}


		/**
         * Render HTML code in admin footer
         *
         * @since 		1.0.0
         * @access  	public
        */
		public function admin_footer() {
			$this->mm_explorer_box();
			$this->mm_editor_box();
		}

		
		/**
         * Field Render Function.
         * Takes the vars and outputs the HTML
         *
         * @since 		1.0.0
         * @access  	public
        */
		public function render() {

			global $post;


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
				echo '<div class="box-row-input fullwidth">';

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
						
						if ( ! isset( self::$_option['std'] ) || self::$_option['std'] == '') {
							$no_images = 'block';
						} else {
							$no_images = 'none';
						}

						/* Select All Items */
						echo '<span class="mm-select-all">select all</span>';
						echo '<div class="clear"></div>';

						// Texts
						if ( isset( self::$_option['btn_text'] ) && self::$_option['btn_text'] != '' ) {
							$btn_text = self::$_option['btn_text'];
						} else {
							$btn_text = esc_html_x( 'Add Images', 'MuttleyBox Class', $this->textdomain );
						}
						if ( isset( self::$_option['msg_text'] ) && self::$_option['msg_text'] != '' ) {
							$msg_text = self::$_option['msg_text'];
						} else {
							$msg_text = esc_html_x( 'Currently slider does not have images, you can add them by clicking on button below.', 'MuttleyBox Class', $this->textdomain );
						}
						
						/* Message */
						echo '<div class="msg-dotted" style="display:' . esc_attr( $no_images ) . '">' . esc_html( $msg_text ) . '</div>';

						/* Settings */
						echo '<span class="mm-settings mm-hidden" data-post-id="' . esc_attr( $post->ID ) . '" data-mm-id="' . esc_attr( self::$_option['id'] ) . '" data-mm-type="' . esc_attr( self::$_option['media_type'] ) . '" data-mm-admin-path="' . esc_url( self::$_args[ 'admin_path' ] ) . '"></span>';

						/*  Hidden input */
						echo '<input type="hidden" value="' . esc_attr( self::$_option['std'] ) . '" id="' . esc_attr( self::$_option['id'] ) . '" name="' . esc_attr( self::$_option['id'] ) . '" class="mm-ids"/>';

						/* Preview */
						echo '<div class="mm-wrap '. esc_attr( self::$_option['media_type'] ) .'">';

						/* Preview Items */
						if ( isset( self::$_option['std'] ) && self::$_option['std'] != '' ) {

							$items = explode('|', self::$_option['std'] );

							foreach( $items as $id ) {

								/* Image */
								if ( self::$_option['media_type'] == 'images' || self::$_option['media_type'] == 'slider' || self::$_option['media_type'] == 'simple_slider' ) {

									$image = wp_get_attachment_image_src( $id );

									if ( $image ) {
										$item = get_post( $id );
										$meta = wp_get_attachment_metadata( $id );
										if ( is_array( $meta ) ) {
											$meta_html = esc_html( basename( $item->guid ) ) . ' - ' . $meta['width'] . 'x' . $meta['height'];
										} else {
											$meta_html = '';
										}
										echo '
										<a class="mm-item" id="' . esc_attr( $id ) . '" title="' . esc_attr( $meta_html ) . '">
											<div class="mm-item-preview">
										    	<div class="mm-item-image">
										    		<div class="mm-centered">
										    			<img src="' . esc_attr( $image[0] ) . '" />
										    		</div>
										    	</div>
											</div>
											<span class="mm-edit-button"><i class="fa fa-gear"></i></span>
										</a>';
									} else {
										echo '
										<a class="mm-item" id="' . esc_attr( $id ) . '">
											<div class="mm-item-preview">
										    	<div class="mm-filename"><div>' . esc_html_x( 'Error: Image file doesn\'t exists.', 'MuttleyBox Class', $this->textdomain  ) . '</div></div>
											</div>
										</a>';
									}
								}

								/* Audio */
								if ( self::$_option['media_type'] == 'audio' ) {

									/* If custom id */
									$audio = get_post( $id );
									$track = false;

									if ( $audio ) {

										/* This is not custom audio */
										$track = get_post_meta( $post->ID, self::$_option['id'] . '_' . $id, true );
										if ( ! isset( $track['title'] ) ) {
											$track['title'] = $audio->post_title;
										}
										$audio_filename = $audio->guid;
									} else {

										$track = get_post_meta( $post->ID, self::$_option['id'] . '_' . $id, true );

										/* Check custom track */
										if ( isset( $track['custom_url'] ) ) {
											$audio = true;
											$audio_filename = $track['custom_url'];
										} else {
											$audio_filename = '';
										}
									}

									if ( $audio ) {

										$image = '';
									
										echo '
											<a class="mm-item mm-audio" id="' . esc_attr( $id ) . '" title="' . esc_html( $audio_filename  ) . '">
												<div class="mm-item-preview">';
											    	
										// If image exists
										if ( isset( $track[ 'cover' ] )	&& is_numeric( $track['cover'] ) ) {
											$image = wp_get_attachment_image_src( $track['cover'], 'thumbnail' );
											$image = $image[0];
											echo '<img class="mm-audio-icon mm-audio-custom-cover" src="' . esc_url( $image ) . '" alt="' . esc_attr_x( 'Preview Image', 'MuttleyBox Class', $this->textdomain ) . '">';
										} else if ( isset( $track[ 'cover' ] ) && ! is_numeric( $track['cover'] ) && $track['cover'] != '' ) {
											echo '<img class="mm-audio-icon mm-audio-custom-cover" src="' . esc_url( $track['cover'] ) . '" alt="' . esc_attr_x( 'Preview Image', 'MuttleyBox Class', $this->textdomain ) . '">';
										} else {
											echo '<img src="' . esc_url( self::$_args[ 'admin_path' ] ) . '/assets/images/metabox/audio.png" class="mm-audio-icon" />';
										}

										echo '<div class="mm-filename"><div>' . esc_html( $track['title'] ) . '</div></div>
												</div>
												<span class="mm-edit-button"><i class="fa fa-gear"></i></span>
											</a>';
									} else {
										echo '
											<a class="mm-item" id="' . esc_attr( $id ) . '">
												<div class="mm-item-preview">
											    	<div class="mm-filename"><div>' . esc_html_x( 'Error: Audio file doesn\'t exists.', 'MuttleyBox Class', $this->textdomain ) . '</div></div>
												</div>
											</a>';
									}

								}
						    }	
						}
						echo '</div>';

						/* Error message */
						echo '<p class="msg msg-error" style="display:none;">' . esc_html_x( 'Error: AJAX Transport', 'MuttleyBox Class', $this->textdomain) . '</p>';

						/* Buttons */

						/* Explorer */
						echo '<button class="_button mm-explorer-button"><i class="fa icon fa-plus"></i>' . esc_html( $btn_text ) . '</button>';

						/* Add custom audio */
						if ( self::$_option['media_type'] == 'audio' ) {
							echo '<button class="_button mm-custom-audio"><i class="fa icon fa-plus"></i>' . esc_html_x( 'Add Custom Track', 'MuttleyBox Class', $this->textdomain) . '</button>';
						}

						/* Delete */
						echo '<button class="_button ui-button-delete mm-delete-button" style="display:none"><i class="fa icon fa-trash-o"></i>' . esc_html_x( 'Remove Selected', 'MuttleyBox Class', $this->textdomain ) . '</button>';

						/* Ajax loader */
						echo '<img class="mm-ajax" src="' . esc_url( admin_url( 'images/wpspin_light.gif' ) ) . '" alt="Loading..." />';


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


		/* Ajax Actions
		---------------------------------------------- */

		/* Save item data */
		function mm_editor_save() {
			
			/* Variables */
			$fields = $_POST['fields'];
			$settings = $_POST['settings'];
			$id = $_POST['item_id'];
			$output = '';
			$response = 'success';

			/* Update attachment audio title */
			if ( $settings['mm_type'] == 'audio' && $fields['title'] != '' ) {
				$response = $fields['title'];
			}

			$option_name = $settings['mm_id'] . '_' . $id;
			$options = get_post_meta($settings['post_id'], $option_name , true);
			
			if ( ! isset( $fields ) && is_array( $fields ) || ! isset( $settings ) ) 
				die();
			
			if ( update_post_meta( $settings['post_id'], $option_name, $fields ) )
		        $this->e_esc( $response );
			else
			    echo 'error';
		   exit;
		}

		/* Media Manager - Ajax Actions */
		function mm_actions() {
			
			$action = $_POST['mm_action'];
			$output = '';

			if ( ! isset( $_POST['action'] ) ) {
				exit;
				echo 'Error - Not set action';
			}


			/* --- Media Explorer --- */
			if ( $action == 'media_explorer' ) {

				/* Variables */
				$pagenum = $_POST['page_num'];
			    $args = array();
			    $args['pagenum'] = $pagenum;
			    $args['numberposts'] = $_POST['numberposts'];
			    $output = '';

				if ( isset( $_POST['type'] ) ) 
					$args['type'] = $_POST['type'];
				else 
					$args['type'] = 'images';

				if ( isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {
					$args['ids'] = $_POST['ids'];
				}
				if ( isset( $_POST['s'] ) && $_POST['s'] != '' ) 
					$args['s'] = stripslashes( $_POST['s'] );
				
				$results = $this->mm_query( $args );

				if ( ! isset( $results ) ) die();
				
			    $output = '';
				if ( ! empty( $results ) ) {

					foreach ( $results as $i => $result ) {

						$item = get_post( $result['ID'] );

						/* Images */
						if ( $args['type'] == 'images' || $args['type'] == 'slider' || $args['type'] == 'simple_slider' ) {
							$meta = wp_get_attachment_metadata( $result['ID'] );
							if ( is_array( $meta ) ) {
								$meta_html = esc_html( basename( $item->guid ) ) . ' - ' . $meta['width'] . 'x' . $meta['height'];
							} else {
								$meta_html = '';
							}
							$output .= '
							<a class="mm-item" id="' . esc_attr( $result['ID'] ) . '" title="' . esc_attr( $meta_html ) . '">
								<div class="mm-item-preview">
							    	<div class="mm-item-image">
							    		<div class="mm-centered">
							    			<img src="' . esc_url( $result['image'][0] ) . '" />
							    		</div>
							    	</div>
								</div>
							</a>';


						/* Audio */
						} else {
							$output .= '
							<a class="mm-item mm-audio" id="' . esc_attr( $result['ID'] ) . '" title="' . esc_html( basename( $item->guid ) ) . '">
								<div class="mm-item-preview">
							    	<img src="' . esc_url( self::$_args[ 'admin_path' ] ) . '/assets/images/metabox/audio.png" class="mm-audio-icon" />
							    	<div class="mm-filename"><div>' . esc_attr( $result['title'] ) . '</div></div>
								</div>
							</a>';
						}
					}
				} else {
					$output = 'end pages';
				}

			    $this->e_esc( $output );
			    exit;
			}


			/* --- Add Media --- */
			if ( $action == 'add_media' ) {

				/* Variables */
				$items = $_POST['items'];
				$type = $_POST['type'];

				if ( ! isset( $items ) || empty( $items ) ) 
					die();
				if ( isset( $type ) ) {
					if ( $type == 'images' || $type == 'slider' || $type== 'simple_slider' ) 
						$type = 'image';
					else 
						$type = 'audio';
				}

				$output = '';
				foreach( $items as $id ) {

					$item = get_post( $id );

					/* Image */
					if ( $type == 'image' ) {
						$image = wp_get_attachment_image_src( $id );
						$meta = wp_get_attachment_metadata( $id );
						if ( is_array( $meta ) ) {
							$meta_html = esc_html( basename( $item->guid ) ) . ' - ' . $meta['width'] . 'x' . $meta['height'];
						} else {
							$meta_html = '';
						}
						$output .= '
						<a class="mm-item" id="' . esc_attr( $id ) . '" title="' . esc_attr( $meta_html ) . '">
		                	<div class="mm-item-preview">
			                	<div class="mm-item-image">
			                		<div class="mm-centered">
			                			<img src="' . esc_attr( $image[0] ) . '" />
			                		</div>
			                	</div>
		                	</div>
		                	<span class="mm-edit-button"><i class="fa fa-gear"></i></span>
		                </a>';
					}

					/* Audio */
					if ( $type == 'audio' ) {
						$audio = get_post( $id );
						$output .= '
						<a class="mm-item mm-audio" id="' . esc_attr( $id ) . '" title="' . esc_html( basename( $item->guid ) ) . '">
							<div class="mm-item-preview">
						    	<img src="' . esc_url( self::$_args[ 'admin_path' ] ) . '/assets/images/metabox/audio.png" class="mm-audio-icon" />
						    	<div class="mm-filename"><div>' . esc_attr( $audio->post_title ) . '</div></div>
							</div>
							<span class="mm-edit-button"><i class="fa fa-gear"></i></span>
						</a>';
					}
				}

				$this->e_esc( $output );
				exit;
			}


			/* --- Remove Media --- */
			if ( $action == 'remove_media' ) {

				/* Variables */
				$settings = $_POST['settings'];
				$selected_ids = $_POST['selected_ids'];
				$output = '';

				if ( ! isset( $selected_ids ) || empty( $selected_ids ) ) 
					die();
				if ( ! isset( $settings ) ) 
					die();

				foreach ( $selected_ids as $id ) {
					$option_name = $settings['mm_id'] . '_' . $id;
					
					if ( get_post_meta( $settings['post_id'], $option_name ) ) {
						delete_post_meta( $settings['post_id'], $option_name );
					}

				}
				echo 'success';
				exit;
			}


			/* --- Update Media --- */
			if ( $action == 'update_media' ) {

				/* Variables */
				$settings = $_POST['settings'];
				$ids = $_POST['ids'];
				$output = '';
				
				if ( ! isset( $settings ) ) 
					die();

				/* Update post string */
				if ( ! isset( $ids ) || $ids == '' )
					delete_post_meta( $settings['post_id'], $settings['mm_id'] );
				else
			    	update_post_meta( $settings['post_id'], $settings['mm_id'], $ids );
			  	
				echo 'success';
			   	exit;
			}

			echo 'Error: Bad action';
			exit;
		}


		/* Widgets
		---------------------------------------------- */

		/* mm Box */
		function mm_explorer_box() {
		  
			echo '<div id="mm-explorer-box" style="display:none">';
			echo '<input type="hidden" autofocus="autofocus" />';
			echo '<div id="explorer-top">';
			echo '<label for="mm-search">';
			//echo '<span>' . esc_html_x( 'Search:', 'MuttleyBox Class', $this->textdomain ) . '</span>';
			echo '<input type="text" id="mm-search" name="mm-search" tabindex="60" autocomplete="off" value="" placeholder="' . esc_html_x( 'Search', 'MuttleyBox Class', $this->textdomain ) . '" />';
			echo '</label>';
			echo '<label for="mm-select" class="mm-label-select">';
			echo '<span>' . esc_html_x( 'Select All:', 'MuttleyBox Class', $this->textdomain ) . '</span>';
			echo '<input type="checkbox" id="mm-select" name="mm-select"/>';
			echo '</label>';
			echo '<img id="mm-explorer-loader" class="mm-ajax" src="' . esc_url(admin_url('images/wpspin_light.gif')) . '" alt="" />';
			echo '</div>';
			
			/* Results */
			echo '<div class="mm-wrap">';
			echo '</div>';
			echo '<div class="clear"></div>';
			echo '<span class="mm-load-next">' . esc_html_x( 'Load Next 30 Items', 'MuttleyBox Class', $this->textdomain ) . '</span>';

			echo '</div>';

		}


		/* ----- Helper functions ----- */

		/* mm query */
		function mm_query( $args = array() ) {

			/* Media Manager type */
			if ( $args['type'] == 'images' || $args['type'] == 'slider'  || $args['type'] == 'simple_slider' ) 
				$args['type'] = 'image';
			else 
				$args['type'] = 'audio';

			$query = array(
				'post_type'      => 'attachment',
				'order'          => 'DESC',
				'orderby'        => 'post_date',
				'post_status'    => null,
				'post_parent'    => null, // any parent
				'post_mime_type' => $args['type'],
				'numberposts'    => $args['numberposts']
			);
		    
			if ( isset( $args['ids'] ) ) 
				$query['exclude'] = $args['ids'];
			
			$args['pagenum'] = isset( $args['pagenum']) ? absint( $args['pagenum'] ) : 1;

			if ( isset( $args['s'] ) ) $query['s'] = $args['s'];

			$query['offset'] = $args['pagenum'] > 1 ? $query['numberposts'] * ($args['pagenum'] - 1) : 0;

			// Do main query.
			$posts = get_posts( $query );

			// Check if any posts were found.
			if ( ! $posts )
				return false;

			// Build results.
			$results = array();
			foreach ( $posts as $post ) {
				setup_postdata( $post ); 
				$results[] = array(
					'ID' => $post->ID,
					'image' => wp_get_attachment_image_src( $post->ID ),
					'title' => trim( esc_html( strip_tags( get_the_title( $post) ) ) ),
					'permalink' => get_permalink( $post->ID )
				);
			}
			return $results;
		}


		/* ------------------------------------------------------------------------------------------- */

		/*											EDITOR 											   */
		
		/* ------------------------------------------------------------------------------------------- */


		/* Box */
		private function mm_editor_box() {
		  
		    echo '<div id="mm-editor-box" style="display:none">';
		    echo '<input type="hidden" autofocus="autofocus" />';
			echo '<img id="mm-editor-loader" src="' . esc_url(admin_url('images/wpspin_light.gif')) . '" alt="" />';
			echo '<div id="mm-editor-content">';

			echo '</div>';
		    echo '</div>';
		}

		/* Editable content */
		public function mm_editor() {
		
			/* Variables */
			$id = $_POST['item_id'];
			$settings = $_POST['settings'];
			$custom = ($_POST['custom'] === 'true');
			if ( ! isset( $id ) || ! isset( $settings ) ) 
				die();
			$type = $settings['mm_type'];
			$item = get_post( $id );
			$output = '';
			$option_name = $settings[ 'mm_id' ] . '_' . $id;
			$options = get_post_meta( $settings[ 'post_id' ], $option_name, true );

			// If post doesn't exists
			if ( ! $item && ! $custom ) {
					echo '<p class="msg msg-error">' . esc_html_x( 'Error!', 'MuttleyBox Class', $this->textdomain ) . '</p>';
				exit;
				return die();
			}

		   	
			// Include fields

			// Images
			if ( file_exists( plugin_dir_path(__FILE__) .'media_manager_images.php' ) ) {
				require_once( 'media_manager_images.php' );
				if ( function_exists( 'media_manager_images' ) ) {
					$output .= media_manager_images( $type, $id, $item, $options, $this->textdomain, $custom );
				}
			}

			// Simple Slider
			if ( file_exists( plugin_dir_path(__FILE__) .'media_manager_simple_slider.php' ) ) {
				require_once( 'media_manager_simple_slider.php' );
				if ( function_exists( 'media_manager_simple_slider' ) ) {
					$output .= media_manager_simple_slider( $type, $id, $item, $options, $this->textdomain, $custom );
				}
			}

			// Slider
			if ( file_exists( plugin_dir_path(__FILE__) .'media_manager_slider.php' ) ) {
				require_once( 'media_manager_slider.php' );
				if ( function_exists( 'media_manager_slider' ) ) {
					$output .= media_manager_slider( $type, $id, $item, $options, $this->textdomain, $custom );
				}
			}

			// Audio
			if ( file_exists( plugin_dir_path(__FILE__) .'media_manager_audio.php' ) ) {
				require_once( 'media_manager_audio.php' );
				if ( function_exists( 'media_manager_audio' ) ) {
					$output .= media_manager_audio( $type, $id, $item, $options, $this->textdomain, self::$_args[ 'admin_path' ], $custom );
				}
			}

		    $this->e_esc( $output );
		    exit;
		}

	}
}