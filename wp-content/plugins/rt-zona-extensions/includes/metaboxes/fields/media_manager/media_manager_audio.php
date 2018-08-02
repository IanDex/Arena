<?php

/**
 * Muttley Framework
 *
 * @package     MuttleyBox
 * @subpackage  media_manager_audio.php
 * @author      Mariusz Rek
 * @version     1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'media_manager_audio' ) ):

function media_manager_audio( $type, $id, $item, $options, $textdomain, $admin_path, $custom ) {
   
   /* Display only if the type matches */
  	if ( $type == 'audio' ) {

  		/* Output */
  		$output = '';

  		/* Defaults */
	   	$defaults = array(
			'custom' => $custom,
			'custom_url' => '',
			'title' => '',
			'artists' => '',
			'artists_url' => '',
			'artists_target' => '',
			'links' => '',
			'cover' => '',
			'release_url' => '',
			'release_target' => '',
			'cart_url' => '',
			'cart_target' => '',
			'free_download' => 'no',
			'waveform' => '',
			'track_length' => '',
			'lyrics' => ''
		);

		/* Set default options */
		if ( isset( $options ) && is_array( $options ) ) {
			$options = array_merge( $defaults, $options );
		} else {
			$options = $defaults;
		}

		/* Helpers */

		/* Target options */
		$target_options = array(
			array('name' => esc_html_x( 'Same Window/Tab', 'MuttleyBox Class', $textdomain ), 'value' => '_self'),
			array('name' => esc_html_x( 'New Window/Tab', 'MuttleyBox Class', $textdomain ), 'value' => '_blank')
		);

		/* Yes/No */
		$yes_no_options = array(
			array('name' => esc_html_x( 'No', 'MuttleyBox Class', $textdomain ), 'value' => 'no'),
			array('name' => esc_html_x( 'Yes', 'MuttleyBox Class', $textdomain ), 'value' => 'yes')
		);


		/*  FIELDS
		 ------------------------------------------------------------------------------*/

		$output .= '<fieldset class="muttleybox">';
		/* Loading layer */
		$output .= '<div class="loading-layer"></div>';	
		/* Title */
		if ( $options['title'] == '' && ! $options['custom'] ) {
			$options['title'] = $item->post_title;
		}
		if ( $options['title'] == '' ) {
			$options['title'] = esc_html_x( 'Custom title', 'MuttleyBox Class', $textdomain );
		}
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-audio-title">' . esc_html_x( 'Track Title', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-audio-title" name="title" value="' . esc_attr( $options['title'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Enter track title.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';
		

		/* Custom url */
		if ( $options['custom'] ) {
			$output .= '
				<div class="box-row clearfix">
					<div class="box-row-input">
						<div class="box-tc box-tc-label">
							<label for="mm-audio-custom-url">' . esc_html_x( 'Release/Track URL', 'MuttleyBox Class', $textdomain ) . '</label>
						</div>
						<div class="box-tc box-tc-input">
							<input type="text" id="mm-audio-custom-url" name="custom_url" value="' . esc_attr( $options['custom_url'] ) . '" />
							<p class="help-box">' . esc_html_x( 'Paste here link to the MP3 file or link to Soundcloud track, list, favorite tracks, or paste direct link of music track from following services like: hearthis.at and click on appropriate button. Then the fields will be automatically filled in with the data taken from the selected site.', 'MuttleyBox Class', $textdomain ) . '</p>
							<div class="sub-name services-label">' . esc_html_x( 'Get track data from following services:', 'MuttleyBox Class', $textdomain ) . '</div>
							<div class="box-services-buttons">
								<button class="_button add-hearthis"><i class="fa icon fa-plus"></i>'.esc_html__( 'hearthis.at', 'MuttleyBox Class', $textdomain ).'</button><button class="_button add-googledrive"><i class="fa icon fa-plus"></i>'.esc_html__( 'Google Drive', 'MuttleyBox Class', $textdomain ).'</button>
							</div>
							
							<div class="services-messages">
								<p class="msg msg-warning msg-correct-link">'.esc_html__( 'Please enter a valid link, or select another service..', 'MuttleyBox Class', $textdomain ).'</p>
								<p class="msg msg-warning msg-already-exists">'.esc_html__( 'Link is already converted, please enter a new link.', 'MuttleyBox Class', $textdomain ).'</p>
								<p class="msg msg-error msg-track-error">'.esc_html__( 'Error! Data could not be retrieved. Please try later, service may now be disabled.', 'MuttleyBox Class', $textdomain ).'</p>
								<p class="msg msg-success msg-done">'.esc_html__( 'Done! Data has been downloaded successfully.', 'MuttleyBox Class', $textdomain ).'</p>
							</div>
						</div>
					</div>
					<div class="box-row-line"></div>
				</div>';
		}

		/* Track Length */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-track_length">' . esc_html_x( 'Length (optional)', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-track_length" name="track_length" value="' . esc_attr( $options['track_length'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Track length is displayed in content tracklist.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		/* Artists */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-track_artists">' . esc_html_x( 'Artist', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-track_artists" name="artists" value="' . esc_attr( $options['artists'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Enter track artist(s).', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		/* Artists Link */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-artists_url">' . esc_html_x( 'Artists URL', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-artists_url" name="artists_url" value="' . esc_attr( $options['artists_url'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Paste artist URL.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
			</div>';

		/* Artist Button target */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
					</div>
					<div class="box-tc box-tc-input">
						<div class="sub-name services-label">' . esc_html_x( 'Artists Link Target', 'MuttleyBox Class', $textdomain ) . '</div>
						<select id="mm-artists_target" name="artists_target" size="1" class="box-select">';

			foreach ( $target_options as $option ) {
					
				if ( $options['artists_target'] == $option['value'] ) 
					$selected = 'selected';
				else 
					$selected = '';
				$output .= "<option " . esc_attr( $selected ) . " value='" . esc_attr( $option['value'] ) . "'>" . esc_attr( $option['name'] ) . "</option>";
			}

		$output .= '</select>';
		$output .= '<p class="help-box">' . esc_html_x( 'Select window option.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';


		/* Track Links */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-audio_links">' . esc_html_x( 'Track Links', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<textarea id="mm-audio_links" name="links" style="min-height:120px">'. wp_kses_post( $options['links'] ) .'</textarea>
						<p class="help-box">' . esc_html_x( 'Add player buttons. Button example:
	[track_button link="#" icon="soundcloud" target="_self"].', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';


		/* Cover */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label>' . esc_html_x( 'Cover Image', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">';
						
						/* Source */
						if ( is_numeric( $options['cover'] ) || $options['cover'] == '' ) {
							$media_libary = 'selected="selected"';
							$input_type = 'hidden';
						} else {
							$external_link = 'selected="selected"';
							$input_type = 'text';
							$holder_classes .= ' hidden';
						}

						$output .= '<select size="1" class="image-source-select cover-source" >';

							$output .= "<option $media_libary value='media_libary'>" . _x( 'Media libary', 'MuttleyBox Class', $textdomain ) . "</option>";
							$output .= "<option $external_link value='external_link'>" . _x( 'External link', 'MuttleyBox Class', $textdomain ) . "</option>";
						
						$output .= '</select>';

						$output .= '<input type="' . esc_attr( $input_type ) . '" id="r-cover" name="cover" value="' . esc_attr( $options['cover'] ) . '" class="track-cover image-input" />';

						$image = wp_get_attachment_image_src( $options['cover'], 'thumbnail' );
						$image = $image[0];
						// If image exists
						if ( $image ) {
							$image_html = '<img src="' . esc_url( $image ) . '" alt="' . _x( 'Preview Image', 'MuttleyBox Class', $textdomain ) . '">';
							$is_image = 'is_image'; 
						} else {
							$image_html = '';
							$is_image = ''; 
						}

						$output .= '<div class="image-holder image-holder-cover ' . esc_attr( $is_image ) . ' ' . esc_attr( $holder_classes ) . '" data-placeholder="' . esc_url( $admin_path ) . '/assets/images/metabox/audio.png">';

						// Image
						$output .=  $image_html;

						// Button
						$output .= '<button class="upload-image"><i class="fa icon fa-plus"></i></button>';

						/* Remove image */
						$output .= '<a class="remove-image"><i class="fa icon fa-remove"></i></a>';
						$output .= '</div>';
						
		$output .= '<p class="help-box">' . esc_html_x( 'Add image cover.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		/* Waveform */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label>' . esc_html_x( 'Waveform Image', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">';
						
						/* Source */
						if ( is_numeric( $options['waveform'] ) || $options['waveform'] == '' ) {
							$media_libary = 'selected="selected"';
							$input_type = 'hidden';
						} else {
							$external_link = 'selected="selected"';
							$input_type = 'text';
							$holder_classes .= ' hidden';
						}

						$output .= '<select size="1" class="image-source-select" >';

							$output .= "<option $media_libary value='media_libary'>" . esc_html_x( 'Media libary', 'MuttleyBox Class', $textdomain ) . "</option>";
							$output .= "<option $external_link value='external_link'>" . esc_html_x( 'External link', 'MuttleyBox Class', $textdomain ) . "</option>";
						
						$output .= '</select>';

						$output .= '<input type="' . esc_attr( $input_type ) . '" id="r-waveform" name="waveform" value="' . esc_attr( $options['waveform'] ) . '" class="track-waveform image-input" />';

						$image = wp_get_attachment_image_src( $options['waveform'], 'thumbnail' );
						$image = $image[0];
						// If image exists
						if ( $image ) {
							$image_html = '<img src="' . esc_url( $image ) . '" alt="' . esc_html_x( 'Preview Image', 'MuttleyBox Class', $textdomain ) . '">';
							$is_image = 'is_image'; 
						} else {
							$image_html = '';
							$is_image = ''; 
						}

						$output .= '<div class="image-holder image-holder-waveform ' . esc_attr( $is_image ) . ' ' . esc_attr( $holder_classes ) . '" data-placeholder="' . esc_url( $admin_path ) . '/assets/images/metabox/audio.png">';

						// Image
						$output .=  $image_html;

						// Button
						$output .= '<button class="upload-image"><i class="fa icon fa-plus"></i></button>';

						/* Remove image */
						$output .= '<a class="remove-image"><i class="fa icon fa-remove"></i></a>';
						$output .= '</div>';
						
		$output .= '<p class="help-box">' . esc_html_x( 'Add track waveform, best image is white or black PNG (depends on theme skin) with transparent background. Waveform can be generated on following site:', 'MuttleyBox Class', $textdomain ) . '<br><a href="http://convert.ing-now.com/mp3-audio-waveform-graphic-generator/" target="_blank">Waveform generator</a></p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';


		/* Release Link */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-release_url">' . esc_html_x( 'Release URL', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-release_url" name="release_url" value="' . esc_attr( $options['release_url'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Paste release URL.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
			</div>';

		/* Release target */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
					</div>
					<div class="box-tc box-tc-input">
						<div class="sub-name services-label">' . esc_html_x( 'Release Link Target', 'MuttleyBox Class', $textdomain ) . '</div>
						<select id="mm-release_target" name="release_target" size="1" class="box-select">';

			foreach ( $target_options as $option ) {
					
				if ( $options['release_target'] == $option['value'] ) 
					$selected = 'selected';
				else 
					$selected = '';
				$output .= "<option " . esc_attr( $selected ) . " value='" . esc_attr( $option['value'] ) . "'>" . esc_attr( $option['name'] ) . "</option>";
			}

		$output .= '</select>';
		$output .= '<p class="help-box">' . esc_html_x( 'Select target link.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';


		/* Free download */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-free_download">' . esc_html_x( 'Free Download?', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<select id="mm-free_download" name="free_download" size="1" class="box-select">';

			foreach ( $yes_no_options as $option ) {
					
				if ( $options['free_download'] == $option['value'] ) 
					$selected = 'selected';
				else 
					$selected = '';
				$output .= "<option " . esc_attr( $selected ) . " value='" . esc_attr( $option['value'] ) . "'>" . esc_attr( $option['name'] ) . "</option>";
			}

		$output .= '</select>';
		$output .= '<p class="help-box">' . esc_html_x( 'If you choose this option, "Buy" icon will be replaced on "Download".', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';


		/* Cart Link */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-cart_url">' . esc_html_x( 'Cart URL / Download URL', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-cart_url" name="cart_url" value="' . esc_attr( $options['cart_url'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Paste cart URL or download link.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
			</div>';


		/* Cart target */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
					</div>
					<div class="box-tc box-tc-input">
						<div class="sub-name services-label">' . esc_html_x( 'Cart Link Target', 'MuttleyBox Class', $textdomain ) . '</div>
						<select id="mm-cart_target" name="cart_target" size="1" class="box-select">';

			foreach ( $target_options as $option ) {
					
				if ( $options['cart_target'] == $option['value'] ) 
					$selected = 'selected';
				else 
					$selected = '';
				$output .= "<option " . esc_attr( $selected ) . " value='" . esc_attr( $option['value'] ) . "'>" . esc_attr( $option['name'] ) . "</option>";
			}

		$output .= '</select>';
		$output .= '<p class="help-box">' . esc_html_x( 'Select target link.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		/* Lyrics */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-lyrics">' . esc_html_x( 'Track Lyrics (optional)', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<textarea id="mm-lyrics" name="lyrics" style="min-height:120px">'. wp_kses_post( $options['lyrics'] ) .'</textarea>
						<p class="help-box">' . esc_html_x( 'Track lyrics is displayed in content tracklist/track.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		$output .= '</fieldset>';

		return $output;
	}


}

endif;