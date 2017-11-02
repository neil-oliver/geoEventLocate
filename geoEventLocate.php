<?php
	//bethehype.co.uk
	//GeoEventLocate


		/*
		Plugin Name: GeoEventLocate
		Description: Locates an event based on current time and user location where ever the [geoEventLocate] shortcode is used.
		Version: 0.1
		Author: Nathan Brettell
		Author URI: http://nathanbrettell.com
		*/

	//Allows the plugin to be run via a shortcode from within a WordPress post
	function geoEventLocate_function() {

	//function getGeoLocation() {
		// Check for geolocation support
		$geoLocate = "<script type=\"text/javascript\" src=\"/wp-content/plugins/geoEventLocate/geoEventLocate.js\"></script>"
		. "<script> geo_location(); </script>";

			echo "<div id=\"coords\"></div>";
			echo $geoLocate;

			// SELECT ((ACOS(SIN($lat * PI() / 180) * SIN(lat * PI() / 180) + COS($lat * PI() / 180) * COS(lat * PI() / 180) * COS(($lon - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) 
			// AS `distance` FROM `wp_em_locations` HAVING `distance`<=’10′ ORDER BY `distance` ASC

			//$lon = $_GET['longitude'];
			//$lat = $_GET['latitude'];

			//echo "lat " . $lat . "lon" . $lon; 

		
		$theDate = date("d M Y");
		$theTime = date("H:i");
		echo '<br>';
		echo '<br>';
		echo 'The current date is: ' . $theDate . ' at ' . $theTime;
		

		//require_once('geoPost.php');

	}


add_shortcode( 'geoEventLocate', 'geoEventLocate_function');
	
?>