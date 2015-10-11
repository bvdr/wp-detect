<?php
/**
 * @author Bogdan Dragomir <hi @ bogdandragomir . com>
 * @link https://github.com/bvdr/wp-detect
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

		// geting style.css file header
		$sylecontent = file_get_contents( $styleurl[0] );
		$sh_pattern  = '/\/\*([\s\S]*?)\*\//';
		preg_match( $sh_pattern, $sylecontent, $styleheader );

		// showing header
		echo nl2br( $styleheader[0] );
	}

	function show_wp_version( $url ) {
		$readme = file_get_contents( $url . "/readme.html" );

		if ( $readme ) {
			$wpv_pattern = '/(Version )(\d+\.+)+(\d)?/';
			preg_match( $wpv_pattern, $readme, $wpversion );

			// showing version
			echo "</br>readme.html file found in / and it says: </br>";
			echo "WordPress " . nl2br( $wpversion[0] ) . " from readme.html</br>";
		} else {
			echo "readme.html not in rootfolder";
		}
	}

	function show_generator( $urlcontent ) {

		// geting style.css file url
		$gen_pattern = '/(name="generator" content=")([\w\s\d\.]+)/';
		preg_match_all( $gen_pattern, $urlcontent, $generator );

		foreach ( $generator[2] as $gen_result ) {

			// showing generators
			echo "Generator: " . nl2br( $gen_result ) . "</br>";
		}
	}
}