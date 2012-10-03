<script type='text/javascript'>
(function ($) {
  $(document).ready(function () {
      // wire up button click
      $('#geo_update').click(function () {
          // test for presence of geolocation
          if (navigator && navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(geo_success, geo_error);
          } else {
              alert('Geolocation is not supported.');
          }
      });
  });
   
  function geo_success(position) {
    //printLatLong(position.coords.latitude, position.coords.longitude);
    // Store the current position data for the user
    data = {
      latitude: position.coords.latitude,
      longitude: position.coords.longitude,
      accuracy: position.coords.accuracy,
      altitude: position.coords.altitude,
      altitude_accuracy: position.coords.altitude_accuracy,
      heading: position.coords.heading,
      speed: position.coords.speed,
    };
    $.post("/maplendar/<?php print $user->uid; ?>/log/<?php print md5($user->created); ?>", data, function(data, textStatus, jqXHR) {
      alert('Updated');
      window.location.href = "/map/<?php print $group->id; ?>";
    });
  }
  
  function geo_error(err) {
      if (err.code == 1) {
          alert('The user denied the request for location information.')
      } else if (err.code == 2) {
          alert('Your location information is unavailable.')
      } else if (err.code == 3) {
          alert('The request to get your location timed out.')
      } else {
          alert('An unknown error occurred while requesting your location.')
      }
  }
})(jQuery);
</script>



<style>
  #map {
    height: 500px;
  }
  .maplendar_marker_div {
    border: 2px solid #333;
    border-radius: 5px;
    padding: 5px;
    background: white;
    height: 50px;
    width: 50px;
  }
  
  .maplendar_image {
    margin: 0;
    padding: 0;
    height: 50px;
    width: 50px;
  }
  
  .maplendar_marker_div .maplendar_image img {
    width: 50px;
    height: 50px;
    margin: 0;
    padding: 0;
  }
  
  .maplendar_marker_div .maplendar_name {
    margin-top: 2px;
    font-size: 9px;
    line-height: 10px;
    text-align: center;
    width: 50px;
  }
</style>

<?php

global $user;

?>
<button id="geo_update">Update</button>
<div id="map"></div>

<script type="text/javascript">
  var map = L.map('map').setView([51.505, -0.09], 13);

  L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
  }).addTo(map);
  
  <?php
  // Load the group and members of the group
  foreach ($group->members as $member) {
    if (empty($member->maplendar_geolocation)) {
      // No location data yet, skip it
      continue;
    }
    // See if this member as a google calendar
    if (!empty($member->field_google_calendar_private_xm['und'][0]['value'])) {
      $events = maplendar_get_google_calendar($member->field_google_calendar_private_xm['und'][0]['value']);
      $current_event = FALSE;
      $next_event = FALSE;
      if (!empty($events)) {
        if ($events[0]['start'] < REQUEST_TIME) {
          $current_event = $events[0];
          $next_event = $events[0];
        }
        else {
          $next_event = $events[0];
        }
      }
      drupal_set_message('<pre>' . print_r($member, TRUE) . '</pre>');
    }
    
    $member_html = "
    <div class='maplendar_image'>
      <img src='" . drupal_realpath($member->picture->uri) . "' />
    </div>
    <div class='maplendar_name'>" . $member->field_full_name['und'][0]['value'] . "</div>";
    
    $popup_html = "";
    
    if ($current_event) {
      $popup_html .= "<h4>Now: " . $current_event['title'] . "</h4>";
      $popup_html .= "<p>Where: " . ($current_event['where'] ? $current_event['where'] : 'N/A') 
        ." Ends: " . date('H:i:s', $current_event['end']) . "</p>";
    }
    
    if ($next_event) {
      $popup_html .= "<h4>Now: " . $next_event['title'] . "</h4>";
      $popup_html .= "<p>Where: " . ($next_event['where'] ? $next_event['where'] : 'N/A') 
        ." Starts: " . date('H:i:s', $next_event['start']) 
        ." Ends: " . date('H:i:s', $next_event['end']) 
        . "</p>";
    }
    ?>
    var myIcon = L.divIcon({
      iconSize: [50, 70],
      iconAnchor: [50, 0],
      className: 'maplendar_marker_div', 
      html: "<?php print $member_html; ?>"
    });
    
    L.marker([<?php print $member->longitude; ?>, <?php print $member->latitude; ?>], {icon: myIcon}).addTo(map).bindPopup("<?php print $popup_html; ?>");
  <?php
  }
  ?>

  

</script>
