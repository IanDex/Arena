<?php

/**
 * Muttley Framework
 *
 * @package     MuttleyBox
 * @subpackage  media_manager_images.php
 * @author      Mariusz Rek
 * @version     1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'media_manager_images' ) ):

function media_manager_images( $type, $id, $item, $options, $textdomain, $custom ) {
   
   /* Display only if the type matches */
  	if ( $type == 'images' ) {

  		/* Output */
  		$output = '';

  		/* Defaults */
	   	$defaults = array(
			'custom' => $custom,
			'title' => '',
			'custom_link' => ''
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
						<input type="text" id="mm-image-title" name="title" value="' . esc_attr( $options['title'] ) . '" />
						<p class="help-box">' . esc_html_x( 'Image title.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';
		

		/* Custom Link */
		$output .= '
			<div class="box-row clearfix">
				<div class="box-row-input">
					<div class="box-tc box-tc-label">
						<label for="mm-image-custom-link">' . esc_html_x( 'Custom Link', 'MuttleyBox Class', $textdomain ) . '</label>
					</div>
					<div class="box-tc box-tc-input">
						<textarea id="mm-custom-link" name="custom_link" style="min-height:40px">'. wp_kses_post( $options['custom_link'] ) .'</textarea>
						<p class="help-box">' . esc_html_x( 'Add custom link to popup window.', 'MuttleyBox Class', $textdomain ) . '</p>
					</div>
				</div>
				<div class="box-row-line"></div>
			</div>';

		$output .= '</fieldset>';

		return $output;
	}


}

endif;