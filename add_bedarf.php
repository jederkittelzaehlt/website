<?php namespace ProcessWire;
if($input->post->Ansprechpartner && $input->post->Telefonnummer) {
		
	 //$u = $session->login($username, $pass); 
	// if($u) {
	   // user is logged in, get rid of tmp_pass
		$item = new Page();
		$item->setOutputFormatting(false);
		$item->template = 'bedarf'; // set template
		$item->parent = wire('pages')->get('/bedarf/'); // set the parent
		$dt = date("YmdHis") . "_" . rand(10000,20000);
		$item->name = $dt;
		$item->title = $dt;
		$item->ansprechpartner =  $input->post->text('Ansprechpartner');
		$item->telefonnummer = $input->post->text('Telefonnummer');
		
		$item->anzahl = $input->post->text('Anzahl');
		$item->save();
		$p = wire('pages')->get("/bedarf/" .$dt);
		$p->setOutputFormatting(false);
		 $ps = " Jungfernstieg, Hamburg " ;
		//$p->location->address = $ps;
		$p->location->lat = str_replace(".",",",$input->post->text('cityLat'));
		$p->location->lng = str_replace(".",",",$input->post->text('cityLng'));
		$p->save();

		/*$mail = wireMail(); 
		$mail->to($email); 

		$mail->from('bedarf@jeder-kittel-zählt.de', 'Bedarf wurde eingetragen');  
		$mail->subject("Your registration on ");
		$e_mailtext = "Ihr Bedarf wurde auf unserer Seite eingetragen: <br>\n";
		$mail->body($e_mailtext);
		$mail->bodyHTML('<html><body><h1>' . $e_mailtext . '</h1></body></html>'); 
		//$mail->send(); 
			*/
		$domain = "https://www.xn--jeder-kittel-zhlt-3qb.de/wir-brauchen-kittel/";
	
	   $session->redirect($domain); 
	}
			?>
<!DOCTYPE html>
<html>
  <head>
    <title>Bedarf hinzufügen</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

 
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
    <style>
     
    </style>
  </head>

  <body>


    <!-- Note: The address components in this sample are typical. You might need to adjust them for
               the locations relevant to your app. For more information, see
         https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
    -->
<form method="POST" target="_parent">
	 
  <div class="container" style="  margin-top: 20px;">
   <div class="row">
         <div class="col-md-12">
            <label class="control-label">Ansprechpartner</label>
            <input class="form-control" id="Ansprechpartner" name="Ansprechpartner" required>
         </div>
		 <div class="col-md-8">
            <label class="control-label">Telefonnummer</label>
            <input class="form-control" id="Telefonnummer" name="Telefonnummer" required>
         </div>
         <div class="col-md-4">
            <label class="control-label">Anzahl</label>
            <input class="form-control" id="anzahl" name="Anzahl" >
         </div>
      </div>
<div class="panel">
  <div class="control-label">
    Wo werden die Kittel benötigt?
  </div>
  <div class="panel-body">
   <input id="autocomplete" placeholder="Enter your address"
      onFocus="geolocate()" type="text" class="form-control">
      <br>
   <div id="address" style="display:none">
      <div class="row">
         <div class="col-md-6">
            <label class="control-label">Street address</label>
            <input class="form-control" id="street_number" disabled="true">
         </div>
         <div class="col-md-6">
            <label class="control-label">Route</label>
            <input class="form-control" id="route" disabled="true">
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <label class="control-label">City</label>
            <input class="form-control field" id="locality" disabled="true">
         </div>
         <div class="col-md-6"> 
            <label class="control-label">State</label>
            <input class="form-control" id="administrative_area_level_1" disabled="true">
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <label class="control-label">Zip code</label>
            <input class="form-control" id="postal_code" disabled="true">
         </div>
         <div class="col-md-6">
            <label class="control-label">Country</label>
            <input class="form-control" id="country" disabled="true">
         </div>
      </div>
	  
<input type="text" id="cityLat" name="cityLat" />
<input type="text" id="cityLng" name="cityLng"  /> 
   </div>
</div>
  </div>
   <div class="row">
         <div class="col-md-12">
            <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Ich bin mit der Veröffentlichung meines Bedarfs einverstanden</label>
  </div>
  <button type="submit" class="btn btn-primary">Bedarf eintragen</button>
</form>
         </div>
      </div>
</div>  
    <script>
// This sample uses the Autocomplete widget to help the user select a
// place, then it retrieves the address components associated with that
// place, and then it populates the form fields with those details.
// This sample requires the Places library. Include the libraries=places
// parameter when you first load the API. For example: <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
		//document.getElementById('city2').value = place.name;
		document.getElementById('cityLat').value = place.geometry.location.lat();
		document.getElementById('cityLng').value = place.geometry.location.lng();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
			document.getElementById('cityLat').value = position.coords.latitude;
			document.getElementById('cityLng').value = position.coords.longitude;
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>
	   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBk5MD0G_HnbzfgBvjnjErGydSv9IPW85M&libraries=places&callback=initAutocomplete"
        async defer></script>
  </body>
</html>