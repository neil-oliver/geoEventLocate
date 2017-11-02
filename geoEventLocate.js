/*****

	External JavaScript file including GeoLocation and Google Map Integration

****/

//var id = document.getElementByID("content2");
function geo_location() {
if (navigator.geolocation) {
		// Use method getCurrentPosition to get coordinates
		navigator.geolocation.getCurrentPosition(function (position) {
			// Access them accordingly
			//document.getElementByID('content').innerHTML=('<p>' . position.coords.latitude . '</p>');
			//(position.coords.longitude);
			var longitude = position.coords.longitude;

			var latitude = position.coords.latitude;

			//document.getElementById('coords').innerHTML += "<input type='hidden' id='latitude' name='latitude' value=" + latitude + " /><br />";
			//document.getElementById('coords').innerHTML += "<input type='hidden' id='longitude' name='longitude' value=" + longitude + " /><br />";

			 jQuery.ajax({
       		 type:"POST",
       		 url: "/wp-content/plugins/geoEventLocate/geoPost.php?",  
      			data: "longitude=" + longitude + "&latitude="+ latitude,
        		success: function(data) {

        			//alert(data.substring(0, 1));

        			if (data.substring(0, 1) == ' ') {
        				document.getElementById("coords").innerHTML += data;
        			} else {
        				window.location = "http://bethehype.co.uk/event/" + data;
        				//holds all the php from geoPost
        				//holds all the php from geoPost
        			}
    				}
   				});
			});
		}			
	}

//$(document).ready(function(){
//$("button").click(function(){
  //$.ajax({url:"demo_test.txt",success:function(result){
    //$("#div1").html(result);
//});
//});