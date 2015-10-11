<?php
/**
 * WP Detect 
 * class that returns external url WordPress version, generator, theme style.css header
 * 
 * @example new WP_Detect('wordpress.org');
 *
 * @author Bogdan Dragomir <hi @ bogdandragomir . com>
 * @link https://github.com/bvdr/wp-detect
 * @version 0.1
 */

class WP_Detect {
	var $siteurl = '';

	function WP_Detect() {
		$args = func_get_args();
		call_user_func_array( array( &$this, '__construct' ), $args );
	}

	// executes the functions
	function __construct( $url = '' ) {

		if ( ! empty( $url ) ) {
			if ( strpos( $url, "http://" ) !== false ) {
				$url = $url;
			} elseif ( strpos( $url, "https://" ) !== false ) {
				$url = $url;
			} else {
				$url = "http://$url";
			}
			$url = rtrim( $url, "/" );

			$urlcontent = $this->get_url_content( $url );

			$this->show_generator( $urlcontent );
			$this->show_wp_version( $url );
			$this->show_styleheader( $urlcontent );
		}
	}

	// gets url content and returns it
	function get_url_content( $url ) {
		$urlcontent = file_get_contents( $url );

		return $urlcontent;
	}

	// geting stylesheet header and printing it
	function show_styleheader( $urlcontent ) {

		// geting style.css file url
		$sf_pattern = '/((https?:\/\/)(.+\/style.css))/';
		preg_match( $sf_pattern, $urlcontent, $styleurl );

		if( $styleurl ){

			// geting style.css file header
			$sylecontent = file_get_contents( $styleurl[0] );
			$sh_pattern  = '/\/\*([\s\S]*?)\*\//';
			preg_match( $sh_pattern, $sylecontent, $styleheader );
		}

		// showing header
		echo $styleheader[0];
	}

	function show_wp_version( $url ) {
		$readme = file_get_contents( $url . "/readme.html" );

		if ( $readme ) {
			$wpv_pattern = '/(Version )(\d+\.+)+(\d)?/';
			preg_match( $wpv_pattern, $readme, $wpversion );

			// showing version
			echo "\n readme.html file found in / and it says: \n";
			echo "WordPress " . $wpversion[0] . "\n\n";
		} else {
			echo "\n readme.html not in rootfolder \n";
		}
	}

	function show_generator( $urlcontent ) {

		// geting style.css file url
		$gen_pattern = '/(name="generator" content=")([\w\s\d\.]+)/';
		preg_match_all( $gen_pattern, $urlcontent, $generator );

		foreach ( $generator[2] as $gen_result ) {

			// showing generators
			echo "Generator: " . $gen_result . "\n";
		}
	}
}