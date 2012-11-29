<? global $user;
$account = user_load($user->uid);
 ?>

<script type='text/javascript'>
(function ($) {
  $(document).ready(function () {
      // Test for presence of geolocation
  <? if (empty($account->maplendar_geolocation->updated_time) OR $account->maplendar_geolocation->updated_time < strtotime("-5 minutes")){ ?>
          if (navigator && navigator.geolocation) {
              // Attempt to get the geolocation data from HTML 5
              navigator.geolocation.getCurrentPosition(geo_success, geo_error);
          } else {
              alert('Geolocation is not supported.');
          }
  <?
  } ?>
  });

   
  /**
   * Leighton Notes:
   * If we succesfully got the geolocation data from HTML 5, we put that data into
   * an object and send that object to our log location callback maplendar_log_position()
   * via AJAX, which will save the location data in the database.
   */
  function geo_success(position) {
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
      window.location.href = "/group/<?php print $group->id; ?>";
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
    font-size: 12px;
    height: 500px;
    color: black;
    font-style: normal;
  }
  #map .ui-body-a, #map .ui-dialog.ui-overlay-a {
    text-shadow: 0;
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
  .leaflet-popup-content {
    font-weight: normal;
    font-family: Arial;
  }
  .leaflet-popup-content h4 {
    text-shadow: none;
    margin: 2px 0;
    font-size: 14px;
    text-decoration: underline;
    font-weight: bold;
  }
  .leaflet-popup-content p {
    margin: 2px 0;
    font-size: 12px;
    text-shadow: none;
  }
  .leaflet-popup-content p.meta {
    margin: 0;
    font-size: 10px;
    color: #06F;
  }
  .maplendar_marker_div .maplendar_name {
    margin-top: 2px;
    font-size: 9px;
    line-height: 10px;
    text-align: center;
    width: 50px;
    text-shadow: none;
  }
</style>

<h3><?php print $group->name; ?></h3>

<div id="map"></div>

<?php

if (empty($account->maplendar_geolocation)) {
  return;
}

$map_latitude = $account->maplendar_geolocation->latitude;
$map_longitude = $account->maplendar_geolocation->longitude;

$min_lat = $max_lat = $map_latitude;
$min_long = $max_long = $map_longitude;

?>

<script type="text/javascript">
(function ($) {
  $(document).ready(function () {
    $("#map").css('height', $(window).height() / 1.5);
  var map = L.map('map');

  L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
  }).addTo(map);

  <?php
  // Load the group and members of the group
  foreach ($group->members as $member) {
    //drupal_set_message('<pre>' . print_r($member, TRUE) . '</pre>');
    if (empty($member->maplendar_geolocation)) {
      // No location data yet, skip it
      continue;
    }
    $current_event = FALSE;
    $next_event = FALSE;

    // Get current and next events
    foreach ($member->events as $event) {
      if ($event['start'] < REQUEST_TIME AND $event['end'] > REQUEST_TIME) {
        $current_event = $event;
        break;
      }
    }
    foreach ($member->events as $event) {
      if ($event['start'] > REQUEST_TIME) {
        $next_event = $event;
        break;
      }
    }

    $wrapper = file_stream_wrapper_get_instance_by_uri($member->picture->uri);
    $picture_url = $wrapper->getDirectoryPath() . "/" . file_uri_target($member->picture->uri);

    $member_html = "<div class='maplendar_image'><img src='/" . $picture_url . "' /></div><div class='maplendar_name'>" . $member->field_full_name['und'][0]['value'] . "</div>";

    $popup_html = "<a href='" . "/user/" . $member->uid . "/calendar" . "'>" . t('View Calendar') . "</a>";

    if ($current_event) {
      $popup_html .= "<h4 class='maplendar_bubble_heading'>Now: " . $current_event['title'] . "</h4>";
      $popup_html .= "<p>Where: " . ($current_event['where'] ? $current_event['where'] : 'N/A') . "</p>"
        ."<p>Ends: " . date('m/d/y - g:i a', $current_event['end']) . "</p>";
    }

    if ($next_event) {
      $popup_html .= "<h4 class='maplendar_bubble_heading'>Next: " . $next_event['title'] . "</h4>";
      $popup_html .= "<p>Where: " . ($next_event['where'] ? $next_event['where'] : 'N/A') . "</p>"
        ."<p>Starts: " . date('m/d/y - g:i a', $next_event['start']) . "</p>"
        ."<p>Ends: " . date('m/d/y - g:i a', $next_event['end']) . "</p>";
    }

    $popup_html .= "<p class='meta'>Last updated: " . date('M j, Y g:i a', $member->maplendar_geolocation->updated_time) . "</p>";
    $popup_html .= "<p class='meta'>Accuracy: " . round($member->maplendar_geolocation->accuracy) . " meters</p>";

    $mem_lat = $member->maplendar_geolocation->latitude;
    $mem_long = $member->maplendar_geolocation->longitude;

    if ($mem_lat < $min_lat) {
      $min_lat = $mem_lat;
    }
    if ($mem_lat > $max_lat) {
      $max_lat = $mem_lat;
    }
    if ($mem_long < $min_long) {
      $min_long = $mem_long;
    }
    if ($mem_long > $max_long) {
      $max_long = $mem_long;
    }
    ?>
    var myIcon = L.divIcon({
      iconSize: [50, 70],
      iconAnchor: [50, 0],
      className: 'maplendar_marker_div',
      html: "<?php print $member_html; ?>"
    });

    L.marker([<?php print $mem_lat; ?>, <?php print $mem_long; ?>], {icon: myIcon}).addTo(map).bindPopup("<?php print $popup_html; ?>");
  <?php
  }
  ?>
  map.setView([<?php print $map_latitude; ?>, <?php print $map_longitude; ?>], 13);

  map.fitBounds([
    [<?php print $min_lat; ?>, <?php print $min_long; ?>],
    [<?php print $max_lat; ?>, <?php print $max_long; ?>]
  ]);
  });
})(jQuery);
</script>
