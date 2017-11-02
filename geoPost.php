<?php

ob_start(); //remove session error

//AJAX request coordinates from HTML5 GeoLocation
$lat = $_POST['latitude'];
$lon = $_POST['longitude'];

//Write The Co-ordinates down
//**echo " Your coordinates are: " . $lat . " " . $lon;

global $currentDate, $currentTime;

//define $wpdb as a global variable
global $wpdb, $tableprefix;

if(!isset($wpdb))
{
    require_once('../../../wp-config.php');
    require_once('../../../wp-includes/wp-db.php');
}

//show any sql errors generated from the below sql query
$wpdb->show_errors();

  //select the location with the closest distance to a set of predefined coordinates then print out the details
    $location = $wpdb->get_results("SELECT location_id, location_name, location_slug, location_town, location_address, location_postcode, ((ACOS(SIN($lat * PI() / 180) * SIN(`location_latitude` * PI() / 180) + COS($lat * PI() / 180) * 
				COS(`location_latitude` * PI() / 180) * COS(($lon - `location_longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) 
				AS distance FROM `wp_em_locations` HAVING distance<='20' ORDER BY distance ASC");

    //print the array(if needed)
    //print_r($result);

    //assign responsible variable names to separate parts of the array
    $locationId = $location[0]->location_id;
    $locationName = $location[0]->location_name;
    $locationTown = $location[0]->location_town;
    $locationAddress = $location[0]->location_address;
    $locationPostcode = $location[0]->location_postcode;
    $locationDistance = $location[0]->distance;

    $locationPermalink = $location[0]->location_slug; //location permalink

    //$result is an array of data in the database, print out the values of the array below

    //select the event which is showing at the above location

    $event = $wpdb->get_results("SELECT post_id, event_name, event_slug FROM wp_em_events WHERE location_id = $locationId");

    $event_post_id = $event[0]->post_id;
    $eventName = $event[0]->event_name;
    $eventPermalink = $event[0]->event_slug; //event permalink

    //separate event date notify of the status of the event

    $event_date = $wpdb->get_results("SELECT post_id, event_start_date, event_end_date, event_end_time, event_start_time FROM wp_em_events WHERE location_id = $locationId");

    //foreach ($event_date as $eventDate)

    $eventStartDate = $event_date[0]->event_start_date;
    $eventEndDate = $event_date[0]->event_end_date;
    $eventStartTime = $event_date[0]->event_start_time;
    $eventEndTime = $event_date[0]->event_end_time;

    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

    $theDate = $currentDate . ' ' . $currentTime;
    $beginEvent = $eventStartDate . ' ' . $eventStartTime;
    $finishEvent = $eventEndDate . ' ' . $eventEndTime;

  if ( $locationDistance < '0.05' && $beginEvent === ' ' ) {  // Within a short distance of a venue, but no events are listed

        require 'lastFM.php';

        } else if ( $locationDistance < '0.05' && $beginEvent >= $theDate ) { // Within a short distance of a venue, but no events currently or past events.
      echo ' ' . '<br>';
      echo '<h1> Okay, so you are here: </h1>';
      echo '<strong>Name:</strong> ' . $locationName;
      echo '<br>';
      echo '<strong>Town:</strong> ' . $locationTown;
      echo '<br>';
      echo '<strong>Address:</strong> ' . $locationAddress;
      echo '<br>';
      echo '<strong>PostCode:</strong> ' . $locationPostcode;
      echo '<br>';
      echo 'You are: ' . (round($locationDistance, 2)) . ' <strong>km</strong> away';
      echo '<br>';
      echo '<strong> The next event that is happening here is: </strong> ' . ' ' . $eventName . ' on ' . $eventStartDate;
      echo '<br>';
      echo '<strong> Starting at: </strong>' . $eventStartTime;

        } else if ( $locationDistance < '0.5' && $beginEvent <= $theDate && $theDate <= $finishEvent ) { // Within a short distance of a venue, and an event is currently occuring.
      
      echo $eventPermalink;


      } else if ( $locationDistance > '0.05' && $beginEvent != ' ' ) {  // Not within a short distance of a venue.
      echo ' ' . '<br>';
      echo '<h1> Your Nearest Location is </h1>';
      echo '<strong>Name:</strong> ' . $locationName;
      echo '<br>';
      echo '<strong>Town:</strong> ' . $locationTown;
      echo '<br>';
      echo '<strong>Address:</strong> ' . $locationAddress;
      echo '<br>';
      echo '<strong>PostCode:</strong> ' . $locationPostcode;
      echo '<br>';
      echo 'You are: ' . (round($locationDistance, 2)) . ' <strong>km</strong> away';
      echo '<br>';
      echo '<strong> The next event that is happening here is: </strong> ' . ' ' . $eventName;
      echo '<br>';
      echo '<strong> On: </strong>' . ' ' . $eventStartDate;
      echo '<br>';
      echo '<strong> Starting at: </strong>' . ' ' . $eventStartTime;


        } else if ( $locationDistance > '0.05' ) {  // Not within a short distance of a venue.

          require 'lastFM.php';

        } else {

      echo ' ' . '<br>';
      echo '<strong> Sorry! We havent quite worked out that situation yet!</strong>';
    }






//Shit that is commented out. Needs to be moderated!!!

    //**  echo '<br>';
    //**  echo '<strong> The Event is now happening</strong>';
    //**  echo '<br>';
      //$queried_post = get_post($event_post_id);
      //$title = $queried_post->post_title;
      //echo $title;
      //echo $queried_post->post_content;

    //** echo '<br>';
    //** echo 'Event Date: ' . $eventStartDate;
    //** echo '<br>';
    //** echo 'Event Date: ' . $eventEndDate;
    //** echo '<br>';
    //** echo 'Todays Date: ' . $currentDate;
    //** echo '<br>';

  //** echo '<br>';
  //** echo '<br>';
  //** echo '<strong>Latest Event - </strong>' . $eventName;
  //** echo '<br>';

   //if the person is within a 100m distance of the location the print a message saying you are at the location
   /*
   if ($locationDistance > '10.0') {
      echo 'No venues found.';
     } else if ($locationDistance < '0.1') {
      echo '<br>';
      echo '<br>';
      echo '<h1> You Are At: </h1>';
      echo '<br>';
      echo 'URL: ' . $locationPermalink;
      echo '<br>';
      echo '<strong>Name:</strong> ' . $locationName;
      echo '<br>';
      echo '<strong>Town:</strong> ' . $locationTown;
      echo '<br>';
      echo '<strong>Address:</strong> ' . $locationAddress;
      echo '<br>';
      echo '<strong>PostCode:</strong> ' . $locationPostcode;
      echo '<br>';
      echo 'You are: ' . (round($locationDistance, 2)) . ' <strong>km</strong> away';

   } else if ($locationDistance > '0.1') { //if the person is further than 100m distance of the location the print a message listing the nearest location
      echo '<br>';
      echo '<br>';
      echo '<h1> Your Nearest Location is </h1>';
      echo '<br>';
      echo 'URL: ' . $locationPermalink;
      echo '<br>';
      echo '<strong>Name:</strong> ' . $locationName;
      echo '<br>';
      echo '<strong>Town:</strong> ' . $locationTown;
      echo '<br>';
      echo '<strong>Address:</strong> ' . $locationAddress;
      echo '<br>';
      echo '<strong>PostCode:</strong> ' . $locationPostcode;
      echo '<br>';
      echo 'You are: ' . (round($locationDistance, 2)) . ' <strong>km</strong> away';

   }
*/
?>