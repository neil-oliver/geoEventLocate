<?php

        /* 

        Loop through the massive list of LASTFM php files, cause there are many and its easier.

        */


          $files =  __DIR__ . "/LASTFM/*.php";

          foreach(glob($files) as $file) {

            //file url
            $link = $file;

            //file path info
            $info = pathinfo($link);
            //get the file name
            $filename = basename($link, '.'.$info['extension']);

            require $link;
            //require $link.$filename.".php";
          }

        /* loop to php files from specified area */

          $files1 =  __DIR__ . "/LASTFM/cache/*.php";

          foreach(glob($files1) as $file) {

            //file url
            $link = $file;

            //file path info
            $info = pathinfo($link);
            //get the file name
            $filename = basename($link, '.'.$info['extension']);

            require $link;
            //require $link.$filename.".php";
          }

          $files2 =  __DIR__ . "/LASTFM/caller/*.php";

          foreach(glob($files2) as $file) {

            //file url
            $link = $file;

            //file path info
            $info = pathinfo($link);
            //get the file name
            $filename = basename($link, '.'.$info['extension']);

            require $link;
            //require $link.$filename.".php";
          }

 
      // set api key
      CallerFactory::getDefaultCaller()->setApiKey('72cd96125291435b44055e27089f4380');

    

      //Using Last.fm to look up the Venue ID.
      $limit = 1;
      $results = Venue::search($locationName, $limit);

     
      /* Just incase we want to see whats going on.
      print "<pre>";
      print_r($results);
      print "</pre>";
      */

      //echo '<br>';
      while ($venue = $results->current()) {
      //echo "<strong>Venue ID: </strong>" . $venue->getId();


     //Now lets try and use that ID

      $eventResults = Venue::getEvents($venue->getId());

      /* Just incase we want to see whats going on.
      print "<pre>";
      print_r($eventResults[0]);
      print "</pre>";
      */



if (!empty($eventResults)) { 

         //echo ' ';

        $LASTFMartists =  $eventResults[0]->getArtists();
        
         //echo '<strong>Artists: </strong>' . $LASTFMartists[0] . "<br>";   

         $LASTFMeventTitle = $eventResults[0]->getTitle();

         //echo '<strong>Title: </strong>' . $LASTFMeventTitle . "<br>";   
      
         $LASTFMeventStartDate = $eventResults[0]->getStartDate(); 

        //echo '<strong>Start Time: </strong>' . date('d-m-Y H:i:s', $LASTFMeventStartDate);


        if (date('s', $LASTFMeventStartDate) == '01') { 
        
          $LASTFMeventTime = "19:00:00";

        } else {
        
          $LASTFMeventTime = date('H:i:s', $LASTFMeventStartDate);

      }

      
      $EM_Event = new EM_Event();

      $EM_Event->event_name = $LASTFMeventTitle;

      $EM_Event->event_start_date = date('Y-m-d', $LASTFMeventStartDate);

      $EM_Event->event_end_date = date('Y-m-d', $LASTFMeventStartDate);

      $EM_Event->event_start_time = $LASTFMeventTime;

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
      
        
      }
       $venue = $results->next();

      }

      /*
      Right you little fuck face, here is the information that i can see that is in the database to create an event.

      WE HAVE TO LOOK THROUGH THE EVENTS MANAGER FILES AS THEY MUST HAVE SOME CLASSES WE CAN CALL TO LIGHTEN THE WORKLOAD!!!
      
      wp_em_events
      There are loads of columsn in this one but nearly all of them have a default value of NULL asigned and we wont need to change that. We do need the following:

      post_id - each event has to be entered into the post table and the events table. We will need to do the post table information so that we can grab the post_id and link it here.

      event slug - use the last.fm title info and replace spaces with ' - '

      event_owner - set to 1, i would guess thats admin and its the current owner of the other events. 

      event status - set to 1, i think that means its active and not a draft.

      event_name - last.fm title information

      event_end_time - set to 00:00:00 as the last.fm API does not return an end time.

      event_start_time - last.fm event start time

      event_all_day - set to 0 (1 = all day)

      event_start_date - need to split last.fm events start time variable as it contains both date and time.

      event_end_date - same as start date. Need to investigate this further in respect to festivals.

      event_rsvp_time - although we dont want to enable RSVP (the value is set to 0 by default) it looks like we have to put in a time. not sure if 00:00:00 is appropriate or the event start time. 

      location_id - use the location id from the geolocation results.

      event_atributes - not sure what this is, so i hope this makes sense to you, the current events are set to 'a:0:{}' (excluding quotes)

      event_id - set to autonumber so it should create a unique value


      wp_posts
      THIS IS A WORDPRESS TABLE - LETS NOT FUCK THIS BIT UP.

      ID - this is an autonumber but we are going to need to grab this for use in the wp_em_events table. see above.

      post_author - 1

      post_date - $theDate variable containing both date and time.

      post_date_gmt - same as post_date

      post_title - last.fm title information

      post_status - set to publish

      comment status - set to open

      ping status - set to open

      post_name - slug format of last.fm title information

      post_modified - $theDate variable

      post_modified_gmt - same if post_modified

      post_parent - 0

      guid - 'http://bethehype.co.uk/?post_type=event&#038;p=' . $ID (i hope that makes sense)

      post_type - event

      
      wp_postmeta
      GONNA TACKLE THIS BADBOY TOMORROW AS ITS A BITCH AND I AM FALLING ASLEEP.

      */

      //lets play
?>