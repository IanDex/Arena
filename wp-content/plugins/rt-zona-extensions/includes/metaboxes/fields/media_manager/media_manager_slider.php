<?php

/**
 * Muttley Framework
 *
 * @package     MuttleyBox
 * @subpackage  media_manager_slider.php
 * @author      Mariusz Rek
 * @version     1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'media_manager_slider' ) ):

function media_manager_slider( $type, $id, $item, $options, $textdomain, $custom ) {
   
   /* Display only if the type matches */
  	if ( $type == 'slider' ) {

  		/* Output */
  		$output = '';

  		/* Defaults */
	   	$defaults = array(
			'custom' => $custom,
			'title' => '',
			'subtitle' => '',
			'slider_button_url' => '',
			'slider_button_target' => '_self',
			'slider_button_title'  => esc_html_x( 'View More', 'MuttleyBox Class', $textdomain )
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


		/*  IMAGE META 
		 ------------------------------------------------------------------------------*/
		/* Get Image Data */
		$meta = wp_get_attachment_metadata( $id );
		$image_data = wp_get_attachment_image_src( $id );

		$output .= '
			<div class="mm-item mm-item-editor" id="' . esc_attr( $id ) . '">
				<div class="mm-item-preview">
			    	<div class="mm-item-image">
			    		<div class="mm-centered">
			    			<a href="' . esc_attr( $item->guid ) . '" target="_blank"><img src="' . esc_attr( $image_data[0] ) . '" /></a>
			    		</div>
			    	</div>
				</div>
			</div>';
		
		/* Meta */
		$output .= '<div id="mm-editor-meta">';
			$output .= '<span><strong>' . esc_html_x( 'File name:', 'MuttleyBox Class', $textdomain ) . '</strong> ' . esc_html( basename( $item->guid ) ) . '</span>';
			$output .= '<span><strong>' . esc_html_x( 'File type:', 'MuttleyBox Class', $textdomain ) . '</strong> ' . esc_html( $item->post_mime_type ) . '</span>';
			$output .= '<span><strong>' . esc_html_x( 'Upload date:', 'MuttleyBox Class', $textdomain ) . '</strong> ' . mysql2date( get_option( 'date_format' ), $item->post_date ) . '</span>';

			if ( is_array( $meta ) && array_key_exists( 'width', $meta ) && array_key_exists('height', $meta ) ) {
				$output .= '<span><strong>' . esc_html_x( 'Dimensions:', 'MuttleyBox Class', $textdomain ) . '</strong> ' . esc_attr( $meta['width'] ) . ' x ' . esc_attr( $meta['height'] ) . '</span>';
			}

			$output .= '<span><strong>' . _x( 'Image URL:', 'MuttleyBox Class', $textdomain ) . '</strong> <br>
			<a href="' . esc_url( $item->guid ) . '" target="_blank">' . _x( '[IMAGE LINK]', 'MuttleyBox Class', $textdomain ) . '</a>
			</span>';

		$output .= '</div>';


		/*  FIELDS
		 ------------------------------------------------------------------------------*/

		$output .= '<fieldset class="muttleybox">';
			
		/* Title */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-image-title">' . esc_html_x( 'Title', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<textarea id="mm-image-title" name="title" style="min-height:40px">'. wp_kses_post( $options['title'] ) .'</textarea>
						<p class="help-box">' . esc_html_x( 'Image title.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';
		

		/* Subtitle */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-image-subtitle">' . esc_html_x( 'Subtitle', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<textarea id="mm-image-subtitle" name="subtitle" style="min-height:40px">'. wp_kses_post( $options['subtitle'] ) .'</textarea>
						<p class="help-box">' . esc_html_x( 'Image subtitle.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		/* Button Title */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-slider_button_title">' . esc_html_x( 'Slider Button Title', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-slider_button_title" name="slider_button_title" value="' . esc_attr( $options['slider_button_title'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Paste slider button URL.', 'MuttleyBox Class', $textdomain ) . '<br>' . esc_html_x( 'NOTE: Displayed only on Intro slider section.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		/* Button Link */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-slider_button_url">' . esc_html_x( 'Slider Button URL', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<input type="text" id="mm-slider_button_url" name="slider_button_url" value="' . esc_attr( $options['slider_button_url'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Paste slider button URL.', 'MuttleyBox Class', $textdomain ) . '<br>' . esc_html_x( 'NOTE: Displayed only on Intro slider section.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		/* Slider Button target */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-slider-button-target">' . esc_html_x( 'Slider Button Target', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<select id="mm-slider-button-target" name="slider_button_target" size="1" class="box-select">';

			foreach ( $target_options as $option ) {
					
				if ( $options['slider_button_target'] == $option['value'] ) 
					$selected = 'selected';
				else 
					$selected = '';
				$output .= "<option " . esc_attr( $selected ) . " value='" . esc_attr( $option['value'] ) . "'>" . esc_attr( $option['name'] ) . "</option>";
			}

		$output .= '</select>';
		$output .= '<p class="help-box">' . esc_html_x( 'Select buton target.', 'MuttleyBox Class', $textdomain ) . '<br>' . esc_html_x( 'NOTE: Displayed only on Intro slider section.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';



		$output .= '</fieldset>';

		return $output;
	}


}

endif;