/**
 * Plugin Name: 	Zona Extensions
 * Theme Author: 	Mariusz Rek - Rascals Themes
 * Theme URI: 		http://rascalsthemes.com/zona
 * Author URI: 		http://rascalsthemes.com
 * File:			shortcodes.js
 * =========================================================================================================================================
 *
 * @package zona-extensions
 * @since 1.0.0
 */

jQuery(document).ready(function($) {

	"use strict";

	/* Album music player
	 ---------------------------------------------------------------------- */
	(function() {

		if ( $( '.music-album--wrap' ).length <= 0 || $( '#scamp_player' ).length <= 0 ) return;
		

		(function( func ) {
		    $.fn.addClass = function() { // replace the existing function on $.fn
		        func.apply( this, arguments ); // invoke the original function
		        this.trigger('classChanged'); // trigger the custom event
		        return this; // retain jQuery chainability
		    }
		})($.fn.addClass); // pass the original function as an argument
		
		$( '.music-album--wrap' ).each( function(){
		
			var $this = $( this ),
				$list = $this.find('.sp-list'),
				limit = $this.find('.sp-list li').length-1,
				has_cover = false,
				current = 0;

			$this.data( 'current', 0 )

			if ( $this.hasClass( 'is-album-image' ) ) {
				has_cover = true;
			}

			// Check status
			$this.find( '.sp-list li a' ).on('classChanged', function(){ 

				if ( $( this ).hasClass( 'sp-play' ) ) {
					$this.addClass( 'play' ).removeClass( 'pause loading' );
				} else if ( $( this ).hasClass( 'sp-pause' ) ) {
					$this.addClass( 'pause' ).removeClass( 'play loading' );
				} else if ( $( this ).hasClass( 'sp-loading' ) ) {
					$this.addClass( 'loading' ).removeClass( 'play pause' );
				} else {
					$this.removeClass( 'play pause loading' );
				}
				current = $(this).parent().index();

				// Replace details
				if ( $this.data( 'current') != current ) {
					$this.data( 'current', current);

					// Cover
					if ( ! has_cover  ) {
						var cover = $( this ).attr( 'data-cover_full' );
						$this.find( '.music-album--cover' ).addClass('old');
						$this.find( '.music-album--img-holder' ).append( '<div class="music-album--cover temp" style="background-image:url(' + cover + ')"></div>' );
					}

					// Waveform
					var waveform = $( this ).attr( 'data-waveform');
					$this.find( '.music-album--waveform-wrap img' ).addClass('old');
					if ( waveform != '' ) {
						$this.find( '.music-album--waveform-top' ).append( '<img class="temp" src="' + waveform  + '" alt="image waveform" />' );
						$this.find( '.music-album--waveform-bottom' ).append( '<img class="temp" src="' + waveform  + '" alt="image waveform" />' );
					}

					// Meta
					var title = $( this ).find( '.track-title' ).text(),
						artists = $( this ).find( '.artists' ).text();
						$this.find( '.music-album--meta span' ).removeClass( 'is-active' );

					setTimeout(function(){
						$this.find( '.music-album--meta span' ).text( '');
						if ( title != '' ) {
							$this.find( '.music-album--title span' ).text( title ).addClass('is-active');
						}
						if ( artists != '' ) {
							$this.find( '.music-album--artists span' ).text( artists ).addClass('is-active');
						}
					},500);	

					setTimeout(function(){
						if ( ! has_cover  ) {
							$this.find( '.music-album--img-holder' ).addClass('is-active');
						}
					
						$this.find( '.music-album--waveform-wrap' ).addClass('is-active');

							setTimeout(function(){

								// Cover
								if ( ! has_cover  ) {
									$this.find( '.music-album--cover.old' ).remove();
									$this.find( '.music-album--cover.temp' ).removeClass('temp');
									$this.find( '.music-album--img-holder' ).removeClass('is-active');
								}

								// Waveform
								$this.find( '.music-album--waveform-wrap img.old' ).remove();
								$this.find( '.music-album--waveform-wrap img.temp' ).removeClass('temp');
								$this.find( '.music-album--waveform-wrap' ).removeClass('is-active');

							},500);
					},100);

				}

			});

			// Prev
			$( this ).find( '.music-album--prev' ).on('click', function(){
				scamp_player.playerAction( 'stop' );
				current--;
				if ( current <= -1  ) {
					current = limit;
				}
				$list.find( 'li:eq('+current+') a.sp-play-track' ).trigger('click');

			});

			// Next
			$( this ).find( '.music-album--next' ).on('click', function(){
				scamp_player.playerAction( 'stop' );
				current++;
				if ( current > limit  ) {
					current = 0;
				}
				$list.find( 'li:eq('+current+') a.sp-play-track' ).trigger('click');
			});

			// Play
			$( this ).find( '.music-album--play' ).on('click', function(){
				$list.find( 'li:eq('+current+') a.sp-play-track' ).trigger('click');
			});


		});

	})();


	/* Small Functions
	 ---------------------------------------------------------------------- */

	(function() {

		/* Countdown
		 ------------------------- */
		if ( $.fn.countdown ) {
			$( '.countdown' ).each( function(e) {
				var date = $( this ).data( 'event-date' );

		        $( this ).countdown( date, function( event ) {
		            var $this = $( this );

		            switch( event.type ) {
		                case "seconds":
		                case "minutes":
		                case "hours":
		                case "days":
		                case "weeks":
		                case "daysLeft":
		                    $this.find( '.' + event.type ).html( event.value );
		                    break;

		                case "finished":
		              
		                    break;
		            }
		        });
		    });
	    }
	})();

});