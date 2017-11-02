<?php

//Attempting to use songkick instead of Last.FM

//First lets define some variables use

//Adding a new line of text to save for subversion

$SKapi ;

$SKvenueId ;

$SKvenueSearchName ;

$SKvenueSearchURL ; 

$SKvenueCalendarURL ;

// Now lets add any iformation we already know

$SKapi = 'io09K9l3ebJxmxe2' ;

$SKvenueSearchName = str_replace(' ', '%20', $locationName) ;

$SKvenueSearchURL = 'http://api.songkick.com/api/3.0/search/venues.xml?query=' . $SKvenueSearchName . '&apikey=' . $SKapi ;


// Add the wp_http wordpress class and do the request

if( !class_exists( 'WP_Http' ) )
    include_once( ABSPATH . WPINC. '../../../wp-includes/class-http.php' );

$request = new WP_Http;
$SKVenueSearchResponse = $request->request( $SKvenueSearchURL );
$SKVenueSearchResults = $SKVenueSearchResponse['body'];
$SKVenueSearchResults = simplexml_load_string($SKVenueSearchResults) ;

	 
	  /* Just incase we want to see whats going on
	  echo ' ' ;
	  print '<pre>' ;
      print_r ($SKVenueSearchResults->results->venue[0]->attributes()) ;
      print '</pre>' ;
      */

      $SKvenueId = $SKVenueSearchResults->results->venue[0]->attributes()->id ;

      
      echo ' ' ;
      // echo 'venue id: ' . $SKvenueId ;

	$SKvenueCalendarURL = 'http://api.songkick.com/api/3.0/venues/' . $SKvenueId . '/calendar.xml?apikey=' . $SKapi ;

	$request = new WP_Http;
	$SKVenueCalendarResponse = $request->request( $SKvenueCalendarURL );
	$SKVenueCalendarResults = $SKVenueCalendarResponse['body'];
	$SKVenueCalendarResults = simplexml_load_string($SKVenueCalendarResults) ;

  	  /* Just incase we want to see whats going on
	  print '<pre>' ;
      print_r ($SKVenueCalendarResults->results->event[0]) ;
      print '</pre>' ;
       */

    $SKEventTitle = $SKVenueCalendarResults->results->event[0]->attributes()->displayName ;
    $SKEventStartDate = $SKVenueCalendarResults->results->event[0]->start->attributes()->date;
    $SkEventEndDate = $SKVenueCalendarResults->results->event[0]->start->attributes()->date;
    $SKEventStartTime = $SKVenueCalendarResults->results->event[0]->start->attributes()->time;
    $SKEventHeadliner = $SKVenueCalendarResults->results->event[0]->performance->attributes()->displayName;

    if ($SKEventStartTime == '') { 
        
          $SKEventStartTime = "19:00:00";

    } 

/*
    echo '<br>' ;
    echo 'event title: ' . $SKEventTitle ;
    echo '<br>' ;
    echo 'event start date: ' . (string)$SKEventStartDate ;
    echo '<br>' ;
    echo 'headliner: ' . $SKEventHeadliner ;
	echo '<br>' ;
    echo 'event start time: ' . $SKEventStartTime ;

*/

      $EM_Event = new EM_Event();

      $EM_Event->event_name = $SKEventTitle;

      $EM_Event->event_start_date = (string)$SKEventStartDate;

      $EM_Event->event_end_date = (string)$SKEventStartDate;

      $EM_Event->event_start_time = $SKEventStartTime;

      $EM_Event->event_end_time = "23:59:00";

      $EM_Event->event_all_day = 0;

      $EM_Event->event_owner = 1;

      $EM_Event->event_status = 1;

      $EM_Event->event_rsvp = 0;

      $EM_Event->location_id = $locationId;

      $EM_Event->group_id = 0;

      $EM_Event->start = strtotime($EM_Event->event_start_date." ".$EM_Event->event_start_time);

      $EM_Event->end = strtotime($EM_Event->event_end_date." ".$EM_Event->event_end_time);

      $EM_Event->save();

      if ( $locationDistance > '0.05' ) { 
      echo ' ';

      }

      require 'geoPost.php';
      



?>